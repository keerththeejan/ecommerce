<?php
/**
 * Lightweight SMTP Mailer (no external deps)
 * Supports SSL (465) and STARTTLS (587)
 */
class Mailer {
    private function readLine($conn) {
        $data = '';
        while ($str = fgets($conn, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == ' ') break;
        }
        return $data;
    }

    private function sendCmd($conn, $cmd, $expect = null) {
        if ($cmd !== null) {
            fwrite($conn, $cmd . "\r\n");
        }
        $resp = $this->readLine($conn);
        if ($expect !== null && strpos($resp, (string)$expect) !== 0) {
            throw new Exception("SMTP command failed: cmd={$cmd}, resp={$resp}");
        }
        return $resp;
    }

    public function sendSMTP($host, $port, $encryption, $username, $password, $fromEmail, $fromName, $toEmail, $subject, $body) {
        $encryption = strtolower((string)$encryption);
        $remote = '';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ]);

        if ($encryption === 'ssl' || ($port == 465 && $encryption !== 'tls')) {
            $remote = 'ssl://' . $host . ':' . (int)$port;
        } else {
            $remote = $host . ':' . (int)$port;
        }

        $conn = @stream_socket_client($remote, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        if (!$conn) {
            throw new Exception('SMTP connect failed: ' . $errstr . ' (' . $errno . ')');
        }

        stream_set_timeout($conn, 30);
        $this->sendCmd($conn, null, '220');

        $hostname = gethostname() ?: 'localhost';
        $this->sendCmd($conn, 'EHLO ' . $hostname, '250');

        if ($encryption === 'tls' || (int)$port === 587) {
            // Start TLS session
            $this->sendCmd($conn, 'STARTTLS', '220');
            if (!stream_socket_enable_crypto($conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new Exception('Failed to enable TLS on SMTP connection');
            }
            // EHLO again after STARTTLS
            $this->sendCmd($conn, 'EHLO ' . $hostname, '250');
        }

        if (!empty($username)) {
            $this->sendCmd($conn, 'AUTH LOGIN', '334');
            $this->sendCmd($conn, base64_encode($username), '334');
            $this->sendCmd($conn, base64_encode($password), '235');
        }

        $this->sendCmd($conn, 'MAIL FROM: <' . $fromEmail . '>', '250');
        $this->sendCmd($conn, 'RCPT TO: <' . $toEmail . '>', '250');
        $this->sendCmd($conn, 'DATA', '354');

        $date = date('r');
        $messageId = '<' . uniqid('', true) . '@' . ($hostname ?: 'localhost') . '>';
        $headers = [];
        $headers[] = 'Date: ' . $date;
        $headers[] = 'From: ' . ($fromName ? (sprintf('"%s" <%s>', addslashes($fromName), $fromEmail)) : $fromEmail);
        $headers[] = 'Reply-To: ' . $fromEmail;
        $headers[] = 'Message-ID: ' . $messageId;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $headers[] = 'Subject: ' . $this->encodeHeader($subject);
        $headers[] = 'To: <' . $toEmail . '>';

        $bodyNorm = preg_replace("/\r\n|\r|\n/", "\r\n", (string)$body);
        $bodyNorm = str_replace(["\r\n.\r\n", "\n.\n"], ["\r\n..\r\n", "\n..\n"], $bodyNorm); // dot-stuffing

        $data = implode("\r\n", $headers) . "\r\n\r\n" . $bodyNorm . "\r\n.";
        $this->sendCmd($conn, $data, '250');
        $this->sendCmd($conn, 'QUIT', '221');
        fclose($conn);
        return true;
    }

    private function encodeHeader($str) {
        if (preg_match('/[^\x20-\x7E]/', $str)) {
            return '=?UTF-8?B?' . base64_encode($str) . '?=';
        }
        return $str;
    }
}
