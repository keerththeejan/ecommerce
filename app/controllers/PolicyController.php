<?php
/**
 * Policy Controller
 * Renders a simple admin page for policies so the sidebar link works.
 */
class PolicyController extends Controller {
    private $settingModel;

    public function __construct() {
        // Restrict to admin area similar to other admin sections
        if (!isAdmin()) {
            redirect('user/login');
        }
        $this->settingModel = $this->model('Setting');
    }

    // GET: /?controller=policy&action=index
    public function index() {
        // Load existing contents from settings
        $privacy = $this->settingModel->getSetting('policy_privacy', '');
        $terms   = $this->settingModel->getSetting('policy_terms', '');
        $faq     = $this->settingModel->getSetting('policy_faq', '');

        $this->view('admin/policy/index', [
            'title' => 'Policy',
            'privacy' => $privacy,
            'terms' => $terms,
            'faq' => $faq
        ]);
    }

    // POST: /?controller=policy&action=save
    public function save() {
        if (!$this->isPost()) {
            return $this->redirect(BASE_URL . '?controller=policy&action=index');
        }

        // Preserve whitespace exactly as entered (no trim)
        $privacy = (string)$this->post('privacy', '');
        $terms   = (string)$this->post('terms', '');
        $faq     = (string)$this->post('faq', '');

        // Persist settings
        $ok = true;
        $ok = $ok && $this->settingModel->updateSetting('policy_privacy', $privacy);
        $ok = $ok && $this->settingModel->updateSetting('policy_terms', $terms);
        $ok = $ok && $this->settingModel->updateSetting('policy_faq', $faq);

        if ($ok) {
            $_SESSION['policy_success'] = 'Policy content saved successfully';
        } else {
            $_SESSION['policy_error'] = 'Failed to save one or more fields. Please try again.';
        }

        return $this->redirect(BASE_URL . '?controller=policy&action=index');
    }
}
