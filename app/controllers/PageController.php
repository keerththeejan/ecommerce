<?php
/**
 * PageController
 * Public endpoints to display policy pages (Privacy, Terms, FAQ)
 */
class PageController extends Controller {
    private $settingModel;

    public function __construct() {
        // Public controller (no admin restriction)
        $this->settingModel = $this->model('Setting');
    }

    // GET: /?controller=page&action=privacy
    public function privacy() {
        $this->renderPolicy('Privacy Policy', 'policy_privacy');
    }

    // GET: /?controller=page&action=terms
    public function terms() {
        $this->renderPolicy('Terms of Service', 'policy_terms');
    }

    // GET: /?controller=page&action=faq
    public function faq() {
        $this->renderPolicy('FAQ', 'policy_faq');
    }

    private function renderPolicy($title, $key) {
        header('Content-Type: text/html; charset=UTF-8');
        try {
            $content = $this->settingModel->getSetting($key, '');
        } catch (Exception $e) {
            $content = '';
        }

        // Minimal HTML snippet intended for modal consumption
        echo '<div class="policy-wrapper">';
        echo '<h3 class="mb-3">' . htmlspecialchars($title) . '</h3>';
        // Content is already sanitized on save; output as-is to allow basic formatting
        echo '<div class="policy-content">' . $content . '</div>';
        echo '</div>';
        exit;
    }
}
