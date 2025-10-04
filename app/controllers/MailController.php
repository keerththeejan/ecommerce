<?php
/**
 * Mail Controller
 * Handles sending and testing of emails using PHPMailer + Gmail SMTP
 */

class MailController extends Controller {
    public function __construct() {
        if (!isAdmin()) {
            redirect('user/login');
        }
    }

    // 🔹 GET: /?controller=mail&action=test
    // Tests PHPMailer + SMTP connection
    public function test() {
        if (!isAdmin()) { redirect('user/login'); }

        require_once ROOT_PATH . 'config/mail.php';
        if (!file_exists(ROOT_PATH . 'vendor/autoload.php')) {
            $_SESSION['mail_error'] = 'PHPMailer not installed. Run: composer require phpmailer/phpmailer';
            return $this->redirect(BASE_URL . '?controller=mail&action=index&status=error');
        }

        try {
            require_once ROOT_PATH . 'vendor/autoload.php';
            $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);

            // Gmail SMTP
            $mailer->isSMTP();
            $mailer->Host = SMTP_HOST;
            $mailer->Port = SMTP_PORT;
            $mailer->SMTPSecure = SMTP_ENCRYPTION;
            $mailer->SMTPAuth = true;
            $mailer->Username = SMTP_USERNAME;
            $mailer->Password = SMTP_PASSWORD;

            // From & To
            $mailer->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $mailer->addAddress(MAIL_FROM_ADDRESS);
            $mailer->Subject = 'PHPMailer Gmail Test ' . date('Y-m-d H:i:s');
            $mailer->Body = '✅ PHPMailer test successful! Your Gmail SMTP connection works.';

            $mailer->send();
            $_SESSION['mail_success'] = 'PHPMailer test email sent to ' . MAIL_FROM_ADDRESS;
            return $this->redirect(BASE_URL . '?controller=mail&action=index&status=ok');
        } catch (\Throwable $e) {
            $_SESSION['mail_error'] = 'PHPMailer test failed: ' . $e->getMessage();
            return $this->redirect(BASE_URL . '?controller=mail&action=index&status=error');
        }
    }

    // 🔹 GET: /?controller=mail&action=index
    public function index() {
        $orderId = (int)$this->get('order_id', 0);
        $prefill = [
            'email' => '',
            'subject' => '',
            'message' => '',
            'from_email' => MAIL_FROM_ADDRESS,
            'from_name' => MAIL_FROM_NAME,
        ];

        if ($orderId > 0) {
            try {
                $orderModel = $this->model('Order');
                $orderData = $orderModel->getOrderWithItems($orderId);
                if ($orderData && !empty($orderData['order'])) {
                    $o = $orderData['order'];
                    $items = $orderData['items'] ?? [];

                    $prefill['email'] = $o['email'] ?? '';
                    $prefill['subject'] = 'Order #' . $o['id'] . ' Details';

                    $lines = [];
                    $lines[] = 'Order #' . $o['id'] . ' (' . date('Y-m-d H:i', strtotime($o['created_at'])) . ')';
                    $lines[] = 'Customer: ' . trim(($o['first_name'] ?? '') . ' ' . ($o['last_name'] ?? ''));
                    $lines[] = 'Email: ' . ($o['email'] ?? '');
                    $lines[] = '';
                    $lines[] = 'Items:';
                    foreach ($items as $it) {
                        $name = $it['product_name'] ?? ('Product #' . ($it['product_id'] ?? ''));
                        $qty = (int)($it['quantity'] ?? 0);
                        $price = (float)($it['price'] ?? 0);
                        $lines[] = '- ' . $name . ' x ' . $qty . ' @ ' . number_format($price, 2) . ' = ' . number_format($price * $qty, 2);
                    }
                    $lines[] = '';
                    if (!empty($o['shipping_fee'])) {
                        $lines[] = 'Shipping: ' . number_format((float)$o['shipping_fee'], 2);
                    }
                    if (!empty($o['tax'])) {
                        $lines[] = 'Tax: ' . number_format((float)$o['tax'], 2);
                    }
                    $lines[] = 'Total: ' . number_format((float)$o['total_amount'], 2);
                    $lines[] = '';
                    if (!empty($o['shipping_address'])) {
                        $lines[] = 'Shipping Address:' . "\n" . $o['shipping_address'];
                    }
                    if (!empty($o['billing_address'])) {
                        $lines[] = 'Billing Address:' . "\n" . $o['billing_address'];
                    }
                    $prefill['message'] = implode("\n", $lines);
                }
            } catch (Exception $e) {}
        }

        $this->view('admin/mail/index', [
            'title' => 'Mail',
            'prefill' => $prefill,
        ]);
    }

    // 🔹 POST: /?controller=mail&action=send
    public function send() {
        if (!$this->isPost()) {
            return $this->redirect(BASE_URL . '?controller=mail&action=index');
        }

        require_once ROOT_PATH . 'config/mail.php';

        $email     = trim($this->post('email', ''));
        $subject   = trim($this->post('subject', ''));
        $message   = trim($this->post('message', ''));
        $fromEmail = MAIL_FROM_ADDRESS;
        $fromName  = MAIL_FROM_NAME;
        $orderId   = (int)$this->post('order_id', 0);

        $redirect = BASE_URL . '?controller=mail&action=index' . ($orderId > 0 ? ('&from=pos&order_id=' . $orderId) : '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mail_error'] = 'Invalid customer email';
            return $this->redirect($redirect . '&status=error');
        }

        try {
            require_once ROOT_PATH . 'vendor/autoload.php';
            $mailer = new \PHPMailer\PHPMailer\PHPMailer(true);

            // Gmail SMTP
            $mailer->isSMTP();
            $mailer->Host = SMTP_HOST;
            $mailer->Port = SMTP_PORT;
            $mailer->SMTPSecure = SMTP_ENCRYPTION;
            $mailer->SMTPAuth = true;
            $mailer->Username = SMTP_USERNAME;
            $mailer->Password = SMTP_PASSWORD;

            // Send from admin
            $mailer->setFrom($fromEmail, $fromName);
            $mailer->addAddress($email);
            $mailer->Subject = $subject;
            $mailer->Body = $message;
            $mailer->AltBody = strip_tags($message);

            $mailer->send();
            $_SESSION['mail_success'] = 'Mail sent to ' . htmlspecialchars($email);
            return $this->redirect($redirect . '&status=ok');
        } catch (\Throwable $e) {
            $_SESSION['mail_error'] = 'PHPMailer failed: ' . $e->getMessage();
            return $this->redirect($redirect . '&status=error');
        }
    }
}
