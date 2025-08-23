<?php
/**
 * Policy Controller
 * Handles saving and retrieving Privacy Policy, Terms of Service, and FAQ content
 */
class PolicyController extends Controller {
    private $settingModel;

    public function __construct() {
        // Restrict to admin area
        if (!isAdmin()) {
            redirect('user/login');
        }
        $this->settingModel = $this->model('Setting');
    }

    // GET: /?controller=policy&action=get
    // Returns JSON of existing policy contents
    public function get() {
        header('Content-Type: application/json');
        try {
            $privacy = $this->settingModel->getSetting('policy_privacy', '');
            $terms   = $this->settingModel->getSetting('policy_terms', '');
            $faq     = $this->settingModel->getSetting('policy_faq', '');
            echo json_encode([
                'success' => true,
                'data' => [
                    'privacy' => $privacy,
                    'terms' => $terms,
                    'faq' => $faq,
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to load policies.']);
        }
        exit;
    }

    // POST: /?controller=policy&action=save
    // Accepts JSON or form data: key = privacy|terms|faq, content = string
    public function save() {
        if (!$this->isPost()) {
            return $this->redirect(BASE_URL . '?controller=home&action=admin');
        }

        // Support JSON body
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        $key = $json['key'] ?? $this->post('key');
        $content = $json['content'] ?? $this->post('content');

        header('Content-Type: application/json');

        $keyMap = [
            'privacy' => 'policy_privacy',
            'terms'   => 'policy_terms',
            'faq'     => 'policy_faq',
        ];
        if (!$key || !isset($keyMap[$key])) {
            echo json_encode(['success' => false, 'message' => 'Invalid section.']);
            exit;
        }

        $settingKey = $keyMap[$key];
        $safeContent = sanitize($content);

        $ok = $this->settingModel->updateSetting($settingKey, $safeContent);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Saved successfully.']);
        } else {
            $msg = method_exists($this->settingModel, 'getLastError') ? $this->settingModel->getLastError() : 'Save failed.';
            echo json_encode(['success' => false, 'message' => $msg ?: 'Save failed.']);
        }
        exit;
    }
}
