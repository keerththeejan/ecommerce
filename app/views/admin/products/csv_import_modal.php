<?php
/**
 * Modern CSV Import Modal Component
 * Features: Drag-drop upload, file validation, progress bar, preview, batch import
 */
?>
<!-- Modern CSV Import Modal -->
<div class="modal fade" id="csvImportModal" tabindex="-1" aria-labelledby="csvImportModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-primary text-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="import-icon-wrapper">
                        <i class="bi bi-cloud-arrow-up-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-semibold" id="csvImportModalLabel">Import Products from CSV</h5>
                        <small class="opacity-75">Upload and validate your product data</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="csvImportCloseBtn"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">
                <!-- Progress Bar (hidden by default) -->
                <div id="importProgressContainer" class="d-none">
                    <div class="progress" style="height: 4px; border-radius: 0;">
                        <div id="importProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="px-4 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <small class="text-muted" id="importProgressText">Initializing...</small>
                        <small class="text-muted" id="importProgressPercent">0%</small>
                    </div>
                </div>

                <!-- Alert Container -->
                <div id="csvImportAlerts" class="p-3"></div>

                <!-- Step 1: File Upload -->
                <div id="uploadStep" class="p-4">
                    <!-- Drag & Drop Zone -->
                    <div id="dropZone" class="drop-zone mb-4">
                        <input type="file" id="csvFileInput" name="csv_file" accept=".csv,text/csv" class="d-none">
                        <div class="drop-zone-content">
                            <div class="drop-icon mb-3">
                                <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                            </div>
                            <h6 class="fw-semibold mb-2">Drag & drop your CSV file here</h6>
                            <p class="text-muted small mb-3">or click to browse files</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="browseFileBtn">
                                <i class="bi bi-folder-open me-1"></i> Choose File
                            </button>
                        </div>
                    </div>

                    <!-- File Info (shown after selection) -->
                    <div id="fileInfo" class="d-none">
                        <div class="file-info-card d-flex align-items-center gap-3 p-3 rounded-3 border bg-light">
                            <div class="file-icon">
                                <i class="bi bi-filetype-csv fs-2 text-success"></i>
                            </div>
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="mb-1 text-truncate" id="fileName">filename.csv</h6>
                                <small class="text-muted" id="fileSize">0 KB</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFileBtn">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sample Template Download -->
                    <div class="template-section mt-4">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light">
                            <div class="d-flex align-items-center gap-3">
                                <div class="template-icon">
                                    <i class="bi bi-file-earmark-text fs-4 text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Need a template?</h6>
                                    <small class="text-muted">Download sample CSV with all required columns</small>
                                </div>
                            </div>
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=downloadSampleCsv" 
                               class="btn btn-outline-info btn-sm">
                                <i class="bi bi-download me-1"></i> Download Sample
                            </a>
                        </div>
                    </div>

                    <!-- Required Fields Help -->
                    <div class="required-fields-section mt-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-info-circle-fill text-primary"></i>
                            <h6 class="mb-0 fw-semibold">Required CSV Columns</h6>
                        </div>
                        <div class="row g-2">
                            <?php 
                            $requiredFields = [
                                ['name', 'Product Name', 'text', true],
                                ['sku', 'SKU', 'code', true],
                                ['price', 'Base Price', 'currency', true],
                                ['stock_quantity', 'Stock Qty', 'number', true],
                                ['category_id', 'Category ID', 'hash', true],
                                ['description', 'Description', 'text', false],
                                ['sale_price', 'Sale Price', 'currency', false],
                                ['price2', 'Price 2', 'currency', false],
                                ['price3', 'Price 3', 'currency', false],
                                ['brand_id', 'Brand ID', 'hash', false],
                                ['country_id', 'Country ID', 'hash', false],
                                ['supplier', 'Supplier', 'person', false],
                                ['batch_number', 'Batch No', 'number', false],
                                ['status', 'Status', 'toggle', false],
                                ['add_date', 'Add Date', 'calendar', false],
                                ['expiry_date', 'Expiry Date', 'calendar', false],
                                ['tax_id', 'Tax ID', 'hash', false],
                            ];
                            foreach ($requiredFields as $field): 
                                $icon = $field[3] ? 'bi-asterisk text-danger small' : 'bi-circle text-muted small';
                                $badge = $field[3] ? '<span class="badge bg-danger-subtle text-danger ms-1">Required</span>' : '<span class="badge bg-secondary-subtle text-secondary ms-1">Optional</span>';
                            ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="field-item d-flex align-items-center gap-2 p-2 rounded border bg-white">
                                    <i class="bi <?= $icon ?>"></i>
                                    <div class="flex-grow-1">
                                        <code class="fw-semibold"><?= htmlspecialchars($field[0]) ?></code>
                                        <?= $badge ?>
                                    </div>
                                    <i class="bi bi-<?= $field[2] ?> text-muted" data-bs-toggle="tooltip" title="<?= htmlspecialchars($field[1]) ?>"></i>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Data Preview -->
                <div id="previewStep" class="d-none">
                    <div class="preview-header px-4 py-3 border-bottom bg-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1 fw-semibold">
                                    <i class="bi bi-eye me-2 text-primary"></i>Preview Data
                                </h6>
                                <small class="text-muted">Review the first 10 rows before importing</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="backToUploadBtn">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" id="validateDataBtn">
                                    <i class="bi bi-check-circle me-1"></i> Validate & Import
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="preview-table-wrapper p-0" style="max-height: 400px; overflow: auto;">
                        <table class="table table-sm table-hover mb-0" id="previewTable">
                            <thead class="table-light sticky-top">
                                <tr id="previewTableHead"></tr>
                            </thead>
                            <tbody id="previewTableBody"></tbody>
                        </table>
                    </div>
                    <div class="preview-footer px-4 py-2 border-top bg-light">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Showing first 10 rows. Total rows: <span id="previewTotalRows" class="fw-semibold">-</span>
                        </small>
                    </div>
                </div>

                <!-- Step 3: Import Results -->
                <div id="resultsStep" class="d-none">
                    <div class="results-header px-4 py-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div id="resultIcon" class="result-icon">
                                <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-semibold" id="resultTitle">Import Complete!</h5>
                                <p class="mb-0 text-muted" id="resultSubtitle">Your products have been successfully imported.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="results-summary px-4 py-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="summary-card text-center p-3 rounded-3 border bg-light">
                                    <div class="summary-icon mb-2">
                                        <i class="bi bi-list-ol fs-3 text-primary"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold" id="summaryTotal">0</h4>
                                    <small class="text-muted">Total Rows</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card text-center p-3 rounded-3 border bg-success-subtle">
                                    <div class="summary-icon mb-2">
                                        <i class="bi bi-check-lg fs-3 text-success"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold text-success" id="summarySuccess">0</h4>
                                    <small class="text-success">Imported</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card text-center p-3 rounded-3 border bg-danger-subtle">
                                    <div class="summary-icon mb-2">
                                        <i class="bi bi-x-lg fs-3 text-danger"></i>
                                    </div>
                                    <h4 class="mb-1 fw-bold text-danger" id="summaryFailed">0</h4>
                                    <small class="text-danger">Failed</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Details -->
                    <div id="errorDetails" class="d-none">
                        <div class="px-4 py-3 border-top">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0 fw-semibold text-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Error Details
                                </h6>
                                <button type="button" class="btn btn-outline-danger btn-sm" id="downloadErrorReportBtn">
                                    <i class="bi bi-download me-1"></i> Download Error Report
                                </button>
                            </div>
                            <div class="error-table-wrapper" style="max-height: 250px; overflow: auto;">
                                <table class="table table-sm table-hover" id="errorTable">
                                    <thead class="table-danger">
                                        <tr>
                                            <th>Row</th>
                                            <th>SKU</th>
                                            <th>Error</th>
                                        </tr>
                                    </thead>
                                    <tbody id="errorTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Undo Section -->
                    <div id="undoSection" class="px-4 py-3 border-top bg-light d-none">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1 fw-semibold">
                                    <i class="bi bi-arrow-counterclockwise me-2 text-warning"></i>Undo Import
                                </h6>
                                <small class="text-muted">You can rollback this import within 24 hours</small>
                            </div>
                            <button type="button" class="btn btn-outline-warning" id="undoImportBtn">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Undo Import
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div id="footerStatus" class="text-muted small">
                        <i class="bi bi-shield-check me-1"></i> Secure file upload
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="cancelImportBtn">Cancel</button>
                        <button type="button" class="btn btn-primary d-none" id="previewDataBtn" disabled>
                            <span class="spinner-border spinner-border-sm d-none" id="previewSpinner"></span>
                            <span class="btn-text"><i class="bi bi-eye me-1"></i> Preview Data</span>
                        </button>
                        <button type="button" class="btn btn-success d-none" id="startImportBtn" disabled>
                            <span class="spinner-border spinner-border-sm d-none" id="importSpinner"></span>
                            <span class="btn-text"><i class="bi bi-upload me-1"></i> Import Products</span>
                        </button>
                        <button type="button" class="btn btn-primary d-none" id="doneBtn" data-bs-dismiss="modal">
                            <i class="bi bi-check-lg me-1"></i> Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSV Import Styles -->
<style>
/* Modern Import Modal Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.import-icon-wrapper {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Drop Zone */
.drop-zone {
    border: 2px dashed var(--bs-border-color);
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background: var(--bs-body-bg);
}

.drop-zone:hover {
    border-color: #0d6efd;
    background: rgba(13, 110, 253, 0.02);
}

.drop-zone.dragover {
    border-color: #0d6efd;
    background: rgba(13, 110, 253, 0.08);
    transform: scale(1.01);
}

.drop-zone.dragover .drop-icon i {
    transform: translateY(-5px);
}

.drop-icon i {
    transition: transform 0.3s ease;
}

.drop-zone-content {
    pointer-events: none;
}

/* File Info Card */
.file-info-card {
    transition: all 0.3s ease;
}

.file-info-card:hover {
    border-color: #0d6efd !important;
}

/* Field Items */
.field-item {
    transition: all 0.2s ease;
}

.field-item:hover {
    border-color: #0d6efd !important;
    transform: translateX(2px);
}

/* Summary Cards */
.summary-card {
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Preview Table */
.preview-table-wrapper::-webkit-scrollbar,
.error-table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.preview-table-wrapper::-webkit-scrollbar-thumb,
.error-table-wrapper::-webkit-scrollbar-thumb {
    background: var(--bs-border-color);
    border-radius: 4px;
}

.preview-table-wrapper::-webkit-scrollbar-thumb:hover,
.error-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: var(--bs-secondary);
}

#previewTable th,
#previewTable td {
    white-space: nowrap;
    font-size: 0.85rem;
}

#previewTable th {
    font-weight: 600;
    background: var(--bs-light);
}

/* Validation States */
.validation-valid {
    color: #198754;
}

.validation-invalid {
    color: #dc3545;
}

.validation-warning {
    color: #ffc107;
}

/* Progress Animation */
@keyframes pulse-progress {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.importing .progress-bar {
    animation: pulse-progress 1.5s ease-in-out infinite;
}

/* Dark Mode Compatibility */
[data-theme="dark"] .drop-zone {
    background: rgba(255,255,255,0.02);
}

[data-theme="dark"] .file-info-card,
[data-theme="dark"] .template-section .d-flex,
[data-theme="dark"] .field-item {
    background: rgba(255,255,255,0.05) !important;
}

[data-theme="dark"] #previewTable th {
    background: rgba(255,255,255,0.08);
}
</style>

<!-- CSV Import JavaScript -->
<script>
(function() {
    'use strict';

    // CSV Import Manager
    const CSVImport = {
        // State
        state: {
            file: null,
            csvData: null,
            headers: [],
            rows: [],
            validationErrors: [],
            importId: null,
            isProcessing: false
        },

        // Configuration
        config: {
            maxFileSize: 50 * 1024 * 1024, // 50MB
            allowedTypes: ['text/csv', 'application/vnd.ms-excel', 'application/csv'],
            allowedExtensions: ['.csv'],
            chunkSize: 100, // Process 100 rows at a time
            previewRows: 10,
            requiredColumns: ['name', 'sku', 'price', 'stock_quantity', 'category_id'],
            allColumns: ['name', 'description', 'sku', 'price', 'sale_price', 'price2', 'price3', 
                        'stock_quantity', 'category_id', 'brand_id', 'country_id', 'supplier', 
                        'batch_number', 'status', 'add_date', 'expiry_date', 'tax_id']
        },

        // DOM Elements
        elements: {},

        // Initialize
        init() {
            this.cacheElements();
            this.bindEvents();
            this.reset();
        },

        cacheElements() {
            this.elements = {
                modal: document.getElementById('csvImportModal'),
                dropZone: document.getElementById('dropZone'),
                fileInput: document.getElementById('csvFileInput'),
                browseBtn: document.getElementById('browseFileBtn'),
                fileInfo: document.getElementById('fileInfo'),
                fileName: document.getElementById('fileName'),
                fileSize: document.getElementById('fileSize'),
                removeFileBtn: document.getElementById('removeFileBtn'),
                previewBtn: document.getElementById('previewDataBtn'),
                importBtn: document.getElementById('startImportBtn'),
                validateBtn: document.getElementById('validateDataBtn'),
                cancelBtn: document.getElementById('cancelImportBtn'),
                doneBtn: document.getElementById('doneBtn'),
                backBtn: document.getElementById('backToUploadBtn'),
                
                // Steps
                uploadStep: document.getElementById('uploadStep'),
                previewStep: document.getElementById('previewStep'),
                resultsStep: document.getElementById('resultsStep'),
                
                // Preview
                previewTableHead: document.getElementById('previewTableHead'),
                previewTableBody: document.getElementById('previewTableBody'),
                previewTotalRows: document.getElementById('previewTotalRows'),
                
                // Progress
                progressContainer: document.getElementById('importProgressContainer'),
                progressBar: document.getElementById('importProgressBar'),
                progressText: document.getElementById('importProgressText'),
                progressPercent: document.getElementById('importProgressPercent'),
                
                // Results
                resultIcon: document.getElementById('resultIcon'),
                resultTitle: document.getElementById('resultTitle'),
                resultSubtitle: document.getElementById('resultSubtitle'),
                summaryTotal: document.getElementById('summaryTotal'),
                summarySuccess: document.getElementById('summarySuccess'),
                summaryFailed: document.getElementById('summaryFailed'),
                errorDetails: document.getElementById('errorDetails'),
                errorTableBody: document.getElementById('errorTableBody'),
                downloadErrorBtn: document.getElementById('downloadErrorReportBtn'),
                undoSection: document.getElementById('undoSection'),
                undoBtn: document.getElementById('undoImportBtn'),
                
                // Alerts
                alerts: document.getElementById('csvImportAlerts')
            };
        },

        bindEvents() {
            const { dropZone, fileInput, browseBtn, removeFileBtn, previewBtn, 
                    importBtn, validateBtn, backBtn, undoBtn, downloadErrorBtn } = this.elements;

            // Drop zone events
            dropZone.addEventListener('click', () => fileInput.click());
            dropZone.addEventListener('dragover', (e) => this.handleDragOver(e));
            dropZone.addEventListener('dragleave', (e) => this.handleDragLeave(e));
            dropZone.addEventListener('drop', (e) => this.handleDrop(e));

            // File input
            fileInput.addEventListener('change', (e) => this.handleFileSelect(e.target.files[0]));

            // Buttons
            browseBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });
            removeFileBtn.addEventListener('click', () => this.reset());
            previewBtn.addEventListener('click', () => this.showPreview());
            validateBtn.addEventListener('click', () => this.startImport());
            importBtn.addEventListener('click', () => this.startImport());
            backBtn.addEventListener('click', () => this.showUpload());
            undoBtn.addEventListener('click', () => this.undoImport());
            downloadErrorBtn.addEventListener('click', () => this.downloadErrorReport());

            // Modal close handler
            this.elements.modal.addEventListener('hidden.bs.modal', () => this.reset());
        },

        // Event Handlers
        handleDragOver(e) {
            e.preventDefault();
            e.stopPropagation();
            this.elements.dropZone.classList.add('dragover');
        },

        handleDragLeave(e) {
            e.preventDefault();
            e.stopPropagation();
            this.elements.dropZone.classList.remove('dragover');
        },

        handleDrop(e) {
            e.preventDefault();
            e.stopPropagation();
            this.elements.dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFileSelect(files[0]);
            }
        },

        handleFileSelect(file) {
            this.clearAlerts();

            // Validate file type
            const ext = '.' + file.name.split('.').pop().toLowerCase();
            if (!this.config.allowedExtensions.includes(ext)) {
                this.showAlert('Please upload a valid CSV file (.csv)', 'danger');
                return;
            }

            // Validate file size
            if (file.size > this.config.maxFileSize) {
                this.showAlert('File size exceeds 50MB limit', 'danger');
                return;
            }

            this.state.file = file;
            this.showFileInfo();
            this.parseCSV();
        },

        // File Processing
        showFileInfo() {
            const { file } = this.state;
            const size = file.size < 1024 * 1024 
                ? (file.size / 1024).toFixed(1) + ' KB'
                : (file.size / (1024 * 1024)).toFixed(1) + ' MB';

            this.elements.fileName.textContent = file.name;
            this.elements.fileSize.textContent = size;
            this.elements.fileInfo.classList.remove('d-none');
            this.elements.previewBtn.classList.remove('d-none');
            this.elements.previewBtn.disabled = false;
        },

        parseCSV() {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const content = e.target.result;
                const lines = content.split(/\r\n|\n/).filter(line => line.trim());
                
                if (lines.length < 2) {
                    this.showAlert('CSV file appears to be empty or has no data rows', 'warning');
                    return;
                }

                // Parse headers
                this.state.headers = this.parseCSVLine(lines[0]).map(h => h.trim().toLowerCase());
                
                // Parse rows
                this.state.rows = [];
                for (let i = 1; i < lines.length; i++) {
                    const values = this.parseCSVLine(lines[i]);
                    if (values.length >= this.state.headers.length) {
                        const row = {};
                        this.state.headers.forEach((header, idx) => {
                            row[header] = values[idx] ? values[idx].trim() : '';
                        });
                        this.state.rows.push(row);
                    }
                }

                // Validate headers
                this.validateHeaders();
            };

            reader.onerror = () => {
                this.showAlert('Error reading file. Please try again.', 'danger');
            };

            reader.readAsText(this.state.file);
        },

        parseCSVLine(line) {
            const result = [];
            let current = '';
            let inQuotes = false;
            
            for (let i = 0; i < line.length; i++) {
                const char = line[i];
                
                if (char === '"') {
                    if (inQuotes && line[i + 1] === '"') {
                        current += '"';
                        i++;
                    } else {
                        inQuotes = !inQuotes;
                    }
                } else if (char === ',' && !inQuotes) {
                    result.push(current.trim());
                    current = '';
                } else {
                    current += char;
                }
            }
            result.push(current.trim());
            return result;
        },

        validateHeaders() {
            const missing = this.config.requiredColumns.filter(col => 
                !this.state.headers.includes(col)
            );

            if (missing.length > 0) {
                this.showAlert(
                    `Missing required columns: ${missing.join(', ')}`, 
                    'danger'
                );
                this.elements.previewBtn.disabled = true;
                return;
            }

            // Check for optional but recommended columns
            const optionalMissing = ['description', 'status', 'add_date'].filter(col =>
                !this.state.headers.includes(col)
            );

            if (optionalMissing.length > 0) {
                this.showAlert(
                    `Optional columns not found: ${optionalMissing.join(', ')}. These will use default values.`,
                    'info'
                );
            }
        },

        // UI Navigation
        showUpload() {
            this.elements.uploadStep.classList.remove('d-none');
            this.elements.previewStep.classList.add('d-none');
            this.elements.resultsStep.classList.add('d-none');
            this.elements.previewBtn.classList.remove('d-none');
            this.elements.importBtn.classList.add('d-none');
            this.elements.doneBtn.classList.add('d-none');
            this.hideProgress();
        },

        showPreview() {
            this.renderPreviewTable();
            this.elements.uploadStep.classList.add('d-none');
            this.elements.previewStep.classList.remove('d-none');
            this.elements.resultsStep.classList.add('d-none');
            this.elements.previewBtn.classList.add('d-none');
            this.elements.importBtn.classList.remove('d-none');
            this.elements.importBtn.disabled = false;
        },

        showResults(data) {
            this.elements.uploadStep.classList.add('d-none');
            this.elements.previewStep.classList.add('d-none');
            this.elements.resultsStep.classList.remove('d-none');
            this.elements.previewBtn.classList.add('d-none');
            this.elements.importBtn.classList.add('d-none');
            this.elements.doneBtn.classList.remove('d-none');
            this.hideProgress();

            // Update summary
            this.elements.summaryTotal.textContent = data.total_rows || 0;
            this.elements.summarySuccess.textContent = data.imported || 0;
            this.elements.summaryFailed.textContent = data.failed || 0;

            // Show/hide error details
            if (data.errors && data.errors.length > 0) {
                this.renderErrorTable(data.errors);
                this.elements.errorDetails.classList.remove('d-none');
                this.elements.resultIcon.innerHTML = '<i class="bi bi-exclamation-circle-fill fs-2 text-warning"></i>';
                this.elements.resultTitle.textContent = 'Import Completed with Errors';
                this.elements.resultSubtitle.textContent = `${data.imported} products imported, ${data.failed} failed.`;
            } else {
                this.elements.errorDetails.classList.add('d-none');
                this.elements.resultIcon.innerHTML = '<i class="bi bi-check-circle-fill fs-2 text-success"></i>';
                this.elements.resultTitle.textContent = 'Import Successful!';
                this.elements.resultSubtitle.textContent = `All ${data.imported} products have been imported successfully.`;
            }

            // Show undo option if import ID available
            if (data.import_id) {
                this.state.importId = data.import_id;
                this.elements.undoSection.classList.remove('d-none');
            }
        },

        renderPreviewTable() {
            const { headers, rows } = this.state;
            const previewRows = rows.slice(0, this.config.previewRows);

            // Render headers
            this.elements.previewTableHead.innerHTML = headers.map(h => 
                `<th>${this.escapeHtml(h)}</th>`
            ).join('');

            // Render rows
            this.elements.previewTableBody.innerHTML = previewRows.map(row => {
                const cells = headers.map(h => {
                    const value = row[h] || '';
                    const display = value.length > 30 ? value.substring(0, 30) + '...' : value;
                    return `<td>${this.escapeHtml(display)}</td>`;
                }).join('');
                return `<tr>${cells}</tr>`;
            }).join('');

            this.elements.previewTotalRows.textContent = rows.length.toLocaleString();
        },

        renderErrorTable(errors) {
            this.elements.errorTableBody.innerHTML = errors.map(err => `
                <tr>
                    <td>${err.row}</td>
                    <td><code>${this.escapeHtml(err.sku || '-')}</code></td>
                    <td class="text-danger">${this.escapeHtml(err.error)}</td>
                </tr>
            `).join('');
        },

        // Import Process
        async startImport() {
            if (this.state.isProcessing) return;
            this.state.isProcessing = true;

            this.showProgress();
            this.updateProgress(0, 'Validating data...');

            try {
                // Prepare form data
                const formData = new FormData();
                formData.append('csv_file', this.state.file);
                formData.append('csrf_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                this.updateProgress(30, 'Uploading file...');

                // Send to server
                const response = await fetch('<?php echo BASE_URL; ?>?controller=product&action=importCsvAjax', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                this.updateProgress(70, 'Processing...');

                const result = await response.json();

                this.updateProgress(100, 'Complete');

                if (result.success) {
                    this.showResults(result.data);
                } else {
                    throw new Error(result.message || 'Import failed');
                }

            } catch (error) {
                this.showAlert(error.message, 'danger');
                this.hideProgress();
                this.state.isProcessing = false;
            }
        },

        async undoImport() {
            if (!this.state.importId) return;

            if (!confirm('Are you sure you want to undo this import? This will delete all products imported in this batch.')) {
                return;
            }

            try {
                this.elements.undoBtn.disabled = true;
                this.elements.undoBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Undoing...';

                const response = await fetch(`<?php echo BASE_URL; ?>?controller=product&action=undoImport&id=${this.state.importId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    this.showAlert('Import has been successfully undone.', 'success');
                    this.elements.undoSection.classList.add('d-none');
                } else {
                    throw new Error(result.message || 'Undo failed');
                }
            } catch (error) {
                this.showAlert(error.message, 'danger');
            } finally {
                this.elements.undoBtn.disabled = false;
                this.elements.undoBtn.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Undo Import';
            }
        },

        downloadErrorReport() {
            if (!this.state.lastErrors) return;

            let csv = 'Row,SKU,Error\n';
            this.state.lastErrors.forEach(err => {
                csv += `"${err.row}","${err.sku || ''}","${err.error}"\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `import_errors_${new Date().toISOString().slice(0,10)}.csv`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },

        // Progress UI
        showProgress() {
            this.elements.progressContainer.classList.remove('d-none');
            document.body.classList.add('importing');
        },

        hideProgress() {
            this.elements.progressContainer.classList.add('d-none');
            document.body.classList.remove('importing');
        },

        updateProgress(percent, text) {
            this.elements.progressBar.style.width = percent + '%';
            this.elements.progressBar.setAttribute('aria-valuenow', percent);
            this.elements.progressPercent.textContent = percent + '%';
            if (text) this.elements.progressText.textContent = text;
        },

        // Alerts
        showAlert(message, type = 'info') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${this.escapeHtml(message)}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            this.elements.alerts.appendChild(alert);
        },

        clearAlerts() {
            this.elements.alerts.innerHTML = '';
        },

        // Utilities
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        reset() {
            this.state = {
                file: null,
                csvData: null,
                headers: [],
                rows: [],
                validationErrors: [],
                importId: null,
                isProcessing: false
            };
            
            this.elements.fileInput.value = '';
            this.elements.fileInfo.classList.add('d-none');
            this.elements.previewBtn.classList.add('d-none');
            this.elements.importBtn.classList.add('d-none');
            this.elements.doneBtn.classList.add('d-none');
            this.elements.errorDetails.classList.add('d-none');
            this.elements.undoSection.classList.add('d-none');
            this.clearAlerts();
            this.showUpload();
        }
    };

    // Initialize when modal is shown
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('csvImportModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', () => {
                CSVImport.init();
            });
        }

        // Make CSVImport globally accessible for button triggers
        window.CSVImport = CSVImport;
    });
})();
</script>
