<?php
/**
 * Unit Controller
 */
class UnitController extends Controller {
    private $unitModel;

    public function __construct() {
        $this->unitModel = $this->model('Unit');
        $this->unitModel->ensureSchema();
    }

    /**
     * AJAX create unit (used from product form modal)
     */
    public function create() {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
            return;
        }

        if (!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }

        $name = trim((string)$this->post('unit_name'));
        $shortName = trim((string)$this->post('short_name'));
        $allowDecimal = (int)$this->post('allow_decimal', 0) ? 1 : 0;
        $isMultiple = (int)$this->post('is_multiple', 0) ? 1 : 0;
        $multiplier = $this->post('multiplier');
        $baseUnitId = $this->post('base_unit');

        if ($name === '' || $shortName === '') {
            $this->json(['success' => false, 'message' => 'Unit Name and Short Name are required'], 422);
            return;
        }

        $payload = [
            'name' => sanitize($name),
            'short_name' => sanitize($shortName),
            'allow_decimal' => $allowDecimal,
            'is_multiple' => $isMultiple,
            'multiplier' => null,
            'base_unit_id' => null,
            'status' => 1
        ];

        if ($isMultiple) {
            $mult = is_numeric($multiplier) ? (float)$multiplier : 0;
            $baseId = (int)$baseUnitId;
            if ($mult <= 0 || $baseId <= 0) {
                $this->json(['success' => false, 'message' => 'Please enter valid multiple conversion fields'], 422);
                return;
            }
            $payload['multiplier'] = $mult;
            $payload['base_unit_id'] = $baseId;
        }

        $createdId = $this->unitModel->create($payload);
        if (!$createdId) {
            $this->json([
                'success' => false,
                'message' => $this->unitModel->getLastError() ?: 'Failed to create unit'
            ], 500);
            return;
        }

        $this->json([
            'success' => true,
            'message' => 'Unit created successfully',
            'id' => (int)$createdId,
            'name' => $payload['name'],
            'short_name' => $payload['short_name']
        ]);
    }
}
