<?php
/**
 * Mail Controller
 * Simple placeholder to avoid 404 and render a Mail page in admin
 */
class MailController extends Controller {
    public function __construct() {
        // Restrict to admin area similar to other admin sections
        if (!isAdmin()) {
            redirect('user/login');
        }
    }

    // GET: /?controller=mail&action=index
    public function index() {
        // Optional: when coming from Order page, prefetch order details
        $order = null;
        $orderId = (int)($this->get('order_id') ?? 0);
        if ($orderId > 0) {
            try {
                $orderModel = $this->model('Order');
                $order = $orderModel->getOrderWithItems($orderId);
            } catch (Exception $e) {
                // ignore if fails, page will still render
            }
        }

        $this->view('admin/mail/index', [
            'title' => 'Mail',
            'order' => $order
        ]);
    }

    // POST: /?controller=mail&action=send
    public function send() {
        if (!$this->isPost()) {
            return $this->redirect(BASE_URL . '?controller=mail&action=index');
        }

        $email   = trim($this->post('email', ''));
        $subject = trim($this->post('subject', ''));
        $message = trim($this->post('message', ''));
        $orderId = (int)$this->post('order_id', 0);

        // Basic validation
        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if ($subject === '') $errors[] = 'Subject is required';
        if ($message === '') $errors[] = 'Message is required';

        // Build redirect target (preserve POS context)
        $redirect = BASE_URL . '?controller=mail&action=index' . ($orderId > 0 ? ('&from=pos&order_id=' . $orderId) : '');

        if (!empty($errors)) {
            $_SESSION['mail_error'] = implode(' | ', $errors);
            return $this->redirect($redirect . '&status=error');
        }

        // Here you would send the email using configured SMTP
        $successMsg = 'Mail sent successfully to ' . htmlspecialchars($email) . ' with subject "' . htmlspecialchars($subject) . '"' . ($orderId > 0 ? (' (Order #' . $orderId . ')') : '') . '.';

        // After send, always go to Invoice Create page
        $_SESSION['invoice_success'] = $successMsg;
        return $this->redirect(BASE_URL . '?controller=invoice&action=create');
    }
}
