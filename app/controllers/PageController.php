<?php
/**
 * Public Pages Controller
 * Serves Privacy Policy, Terms of Service, and FAQ from settings
 */
class PageController extends Controller {
    private $settingModel;

    public function __construct() {
        parent::__construct();
        $this->settingModel = $this->model('Setting');
    }

    // GET: ?controller=page&action=privacy
    public function privacy() {
        $content = $this->settingModel->getSetting('policy_privacy', '');
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'title' => 'Privacy Policy',
                'content' => $content,
            ]);
            exit;
        }
        $this->view('customer/page/privacy', [
            'title' => 'Privacy Policy',
            'content' => $content
        ]);
    }

    // GET: ?controller=page&action=terms
    public function terms() {
        $content = $this->settingModel->getSetting('policy_terms', '');
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'title' => 'Terms of Service',
                'content' => $content,
            ]);
            exit;
        }
        $this->view('customer/page/terms', [
            'title' => 'Terms of Service',
            'content' => $content
        ]);
    }

    // GET: ?controller=page&action=faq
    public function faq() {
        $content = $this->settingModel->getSetting('policy_faq', '');
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'title' => 'Frequently Asked Questions',
                'content' => $content,
            ]);
            exit;
        }
        $this->view('customer/page/faq', [
            'title' => 'Frequently Asked Questions',
            'content' => $content
        ]);
    }
}
