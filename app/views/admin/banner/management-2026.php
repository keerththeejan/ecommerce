<?php
// Banner Management - Modern 2026 Design
require_once APP_PATH . 'views/admin/layouts/header.php';

// Get banner data
try {
    if (!class_exists('Database')) {
        require_once APP_PATH . 'config/Database.php';
    }
    
    $db = new Database();
    $db->query("SELECT * FROM banners ORDER BY sort_order ASC, created_at DESC");
    $banners = $db->resultSet();
    
    // Get banner settings
    $db->query("SELECT * FROM banner_settings WHERE id = 1");
    $settings = $db->single();
    
} catch (Exception $e) {
    error_log('Banner management error: ' . $e->getMessage());
    $banners = [];
    $settings = [
        'auto_slide' => 1,
        'slide_interval' => 5000,
        'transition_effect' => 'fade',
        'show_arrows' => 1,
        'show_dots' => 1
    ];
}
?>

<style>
/* Banner Management 2026 Styles */
.banner-management-2026 {
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

.banner-header-2026 {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
    position: relative;
    overflow: hidden;
}

.banner-header-2026::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(30px, -30px) rotate(120deg); }
    66% { transform: translate(-20px, 20px) rotate(240deg); }
}

.banner-grid-2026 {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    margin-bottom: 2rem;
}

.banner-list-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.banner-preview-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.banner-card-2026 {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    cursor: move;
}

.banner-card-2026:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    border-color: #3b82f6;
}

.banner-card-2026.dragging {
    opacity: 0.5;
    transform: rotate(2deg);
}

.banner-card-2026 .banner-image {
    width: 100%;
    height: 200px;
    background: #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    margin-bottom: 1rem;
}

.banner-card-2026 .banner-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-card-2026 .banner-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.banner-status.active {
    background: #10b981;
    color: white;
}

.banner-status.inactive {
    background: #ef4444;
    color: white;
}

.banner-status.scheduled {
    background: #f59e0b;
    color: white;
}

.banner-card-2026 .banner-info {
    margin-bottom: 1rem;
}

.banner-card-2026 .banner-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.banner-card-2026 .banner-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.banner-card-2026 .banner-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-banner-2026 {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-banner-2026.primary {
    background: #3b82f6;
    color: white;
}

.btn-banner-2026.primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

.btn-banner-2026.secondary {
    background: #f3f4f6;
    color: #4b5563;
}

.btn-banner-2026.secondary:hover {
    background: #e5e7eb;
}

.btn-banner-2026.danger {
    background: #fef2f2;
    color: #dc2626;
}

.btn-banner-2026.danger:hover {
    background: #fee2e2;
}

.banner-preview-2026 {
    background: #f8fafc;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    position: relative;
    height: 250px;
}

.banner-preview-2026 img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-preview-controls {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.banner-settings-2026 {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
}

.setting-group-2026 {
    margin-bottom: 1.5rem;
}

.setting-group-2026 label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.setting-group-2026 input,
.setting-group-2026 select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.setting-group-2026 input:focus,
.setting-group-2026 select:focus {
    outline: none;
    border-color: #3b82f6;
}

.toggle-switch-2026 {
    position: relative;
    width: 60px;
    height: 30px;
    background: #e5e7eb;
    border-radius: 15px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.toggle-switch-2026.active {
    background: #3b82f6;
}

.toggle-switch-2026::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 3px;
    width: 24px;
    height: 24px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-switch-2026.active::after {
    transform: translateX(30px);
}

.upload-zone-2026 {
    border: 3px dashed #d1d5db;
    border-radius: 16px;
    padding: 3rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fafafa;
}

.upload-zone-2026:hover {
    border-color: #3b82f6;
    background: #eff6ff;
}

.upload-zone-2026.dragover {
    border-color: #3b82f6;
    background: #dbeafe;
    transform: scale(1.02);
}

.upload-zone-2026 i {
    font-size: 3rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.upload-zone-2026 h3 {
    color: #374151;
    margin-bottom: 0.5rem;
}

.upload-zone-2026 p {
    color: #6b7280;
    font-size: 0.875rem;
}

.banner-analytics-2026 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}

.analytics-card-2026 {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
}

.analytics-card-2026 .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.analytics-card-2026 .label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.empty-state-2026 {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-state-2026 i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

@media (max-width: 1024px) {
    .banner-grid-2026 {
        grid-template-columns: 1fr;
    }
    
    .banner-preview-section {
        position: static;
    }
}

@media (max-width: 768px) {
    .banner-management-2026 {
        padding: 1rem;
    }
    
    .banner-header-2026 {
        padding: 1.5rem;
    }
    
    .banner-list-section,
    .banner-preview-section {
        padding: 1.5rem;
    }
    
    .banner-analytics-2026 {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="banner-management-2026">
    <!-- Header -->
    <div class="banner-header-2026">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h2 mb-2">
                    <i class="fas fa-images me-3"></i>Banner Management
                </h1>
                <p class="mb-0 opacity-90">Manage your homepage banners, promotional sliders, and marketing campaigns</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <button class="btn-banner-2026 primary me-2" onclick="showUploadModal()">
                    <i class="fas fa-plus"></i> Add New Banner
                </button>
                <button class="btn-banner-2026 secondary" onclick="showSettingsModal()">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="banner-grid-2026">
        <!-- Banner List Section -->
        <div class="banner-list-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">All Banners</h2>
                <div class="btn-group btn-group-sm">
                    <button class="btn-banner-2026 secondary active" onclick="filterBanners('all')">All</button>
                    <button class="btn-banner-2026 secondary" onclick="filterBanners('active')">Active</button>
                    <button class="btn-banner-2026 secondary" onclick="filterBanners('scheduled')">Scheduled</button>
                    <button class="btn-banner-2026 secondary" onclick="filterBanners('inactive')">Inactive</button>
                </div>
            </div>

            <div id="bannerList">
                <?php if (!empty($banners)): ?>
                    <?php foreach ($banners as $index => $banner): ?>
                        <div class="banner-card-2026" data-banner-id="<?php echo $banner['id']; ?>" data-status="<?php echo $banner['status']; ?>">
                            <div class="banner-image">
                                <?php if (!empty($banner['image'])): ?>
                                    <img src="<?php echo BASE_URL . $banner['image']; ?>" alt="<?php echo htmlspecialchars($banner['title']); ?>">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <span class="banner-status <?php echo $banner['status']; ?>">
                                    <?php echo ucfirst($banner['status']); ?>
                                </span>
                            </div>
                            
                            <div class="banner-info">
                                <h3 class="banner-title"><?php echo htmlspecialchars($banner['title']); ?></h3>
                                <div class="banner-meta">
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($banner['created_at'])); ?></span>
                                    <span><i class="fas fa-eye"></i> <?php echo number_format($banner['impressions'] ?? 0); ?> views</span>
                                    <span><i class="fas fa-mouse-pointer"></i> <?php echo number_format($banner['clicks'] ?? 0); ?> clicks</span>
                                </div>
                            </div>
                            
                            <div class="banner-actions">
                                <button class="btn-banner-2026 primary" onclick="editBanner(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-banner-2026 secondary" onclick="previewBanner(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button class="btn-banner-2026 secondary" onclick="toggleBannerStatus(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-power-off"></i> <?php echo $banner['status'] == 'active' ? 'Deactivate' : 'Activate'; ?>
                                </button>
                                <button class="btn-banner-2026 danger" onclick="deleteBanner(<?php echo $banner['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state-2026">
                        <i class="fas fa-images"></i>
                        <h3>No Banners Found</h3>
                        <p>Create your first banner to get started</p>
                        <button class="btn-banner-2026 primary mt-3" onclick="showUploadModal()">
                            <i class="fas fa-plus"></i> Create Your First Banner
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Banner Preview Section -->
        <div class="banner-preview-section">
            <h3 class="h4 mb-3">Live Preview</h3>
            
            <div class="banner-preview-2026" id="bannerPreview">
                <?php if (!empty($banners) && $banners[0]['image']): ?>
                    <img src="<?php echo BASE_URL . $banners[0]['image']; ?>" alt="Banner Preview">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Select a banner to preview</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="banner-preview-controls">
                <button class="btn-banner-2026 secondary" onclick="previousBanner()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn-banner-2026 secondary" onclick="toggleAutoSlide()">
                    <i class="fas fa-play" id="playPauseIcon"></i>
                </button>
                <button class="btn-banner-2026 secondary" onclick="nextBanner()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Banner Settings -->
            <div class="banner-settings-2026">
                <h4 class="h5 mb-3">Slider Settings</h4>
                
                <div class="setting-group-2026">
                    <label>Auto Slide</label>
                    <div class="toggle-switch-2026 <?php echo $settings['auto_slide'] ? 'active' : ''; ?>" onclick="toggleSetting('auto_slide')"></div>
                </div>
                
                <div class="setting-group-2026">
                    <label>Slide Interval (ms)</label>
                    <input type="number" value="<?php echo $settings['slide_interval']; ?>" onchange="updateSetting('slide_interval', this.value)">
                </div>
                
                <div class="setting-group-2026">
                    <label>Transition Effect</label>
                    <select onchange="updateSetting('transition_effect', this.value)">
                        <option value="fade" <?php echo $settings['transition_effect'] == 'fade' ? 'selected' : ''; ?>>Fade</option>
                        <option value="slide" <?php echo $settings['transition_effect'] == 'slide' ? 'selected' : ''; ?>>Slide</option>
                        <option value="zoom" <?php echo $settings['transition_effect'] == 'zoom' ? 'selected' : ''; ?>>Zoom</option>
                    </select>
                </div>
                
                <div class="setting-group-2026">
                    <label>Show Arrows</label>
                    <div class="toggle-switch-2026 <?php echo $settings['show_arrows'] ? 'active' : ''; ?>" onclick="toggleSetting('show_arrows')"></div>
                </div>
                
                <div class="setting-group-2026">
                    <label>Show Dots</label>
                    <div class="toggle-switch-2026 <?php echo $settings['show_dots'] ? 'active' : ''; ?>" onclick="toggleSetting('show_dots')"></div>
                </div>
            </div>
            
            <!-- Analytics -->
            <div class="banner-analytics-2026">
                <div class="analytics-card-2026">
                    <div class="value"><?php echo number_format(array_sum(array_column($banners, 'impressions') ?? [0])); ?></div>
                    <div class="label">Total Impressions</div>
                </div>
                <div class="analytics-card-2026">
                    <div class="value"><?php echo number_format(array_sum(array_column($banners, 'clicks') ?? [0])); ?></div>
                    <div class="label">Total Clicks</div>
                </div>
                <div class="analytics-card-2026">
                    <div class="value"><?php 
                        $totalClicks = array_sum(array_column($banners, 'clicks') ?? [0]);
                        $totalImpressions = array_sum(array_column($banners, 'impressions') ?? [0]);
                        $ctr = $totalImpressions > 0 ? ($totalClicks / $totalImpressions) * 100 : 0;
                        echo number_format($ctr, 2) . '%';
                    ?></div>
                    <div class="label">CTR</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Zone -->
    <div class="upload-zone-2026" id="uploadZone" onclick="document.getElementById('bannerUpload').click()" style="display: none;">
        <i class="fas fa-cloud-upload-alt"></i>
        <h3>Upload Banner Image</h3>
        <p>Drag and drop your banner image here or click to browse</p>
        <p class="text-muted">Recommended size: 1920x800px. Max file size: 5MB</p>
        <input type="file" id="bannerUpload" accept="image/*" style="display: none;" onchange="handleFileUpload(event)">
    </div>
</div>

<!-- Banner Upload Modal -->
<div class="modal fade" id="bannerUploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bannerForm">
                    <div class="mb-3">
                        <label class="form-label">Banner Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Banner Image</label>
                        <div class="upload-zone-2026" onclick="document.getElementById('modalBannerUpload').click()">
                            <i class="fas fa-image"></i>
                            <h4>Choose Banner Image</h4>
                            <p>Click to browse or drag and drop</p>
                            <input type="file" id="modalBannerUpload" accept="image/*" style="display: none;" onchange="previewUploadedImage(event)">
                        </div>
                        <div id="imagePreview" style="display: none; margin-top: 1rem;">
                            <img id="previewImg" style="width: 100%; border-radius: 12px;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CTA Button Text</label>
                            <input type="text" class="form-control" name="cta_text" placeholder="Shop Now">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CTA Button Link</label>
                            <input type="url" class="form-control" name="cta_link" placeholder="https://example.com/product">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" name="end_date">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveBanner()">Save Banner</button>
            </div>
        </div>
    </div>
</div>

<script>
// Banner Management JavaScript
class BannerManagement2026 {
    constructor() {
        this.banners = <?php echo json_encode($banners); ?>;
        this.currentBannerIndex = 0;
        this.autoSlideInterval = null;
        this.isAutoSliding = false;
        this.init();
    }
    
    init() {
        this.setupDragAndDrop();
        this.setupPreviewControls();
        this.loadSettings();
    }
    
    setupDragAndDrop() {
        const bannerList = document.getElementById('bannerList');
        const uploadZone = document.getElementById('uploadZone');
        
        // Make banner cards draggable
        const bannerCards = bannerList.querySelectorAll('.banner-card-2026');
        bannerCards.forEach(card => {
            card.draggable = true;
            
            card.addEventListener('dragstart', (e) => {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', e.target.innerHTML);
                e.target.classList.add('dragging');
            });
            
            card.addEventListener('dragend', (e) => {
                e.target.classList.remove('dragging');
            });
            
            card.addEventListener('dragover', (e) => {
                e.preventDefault();
                const draggingCard = bannerList.querySelector('.dragging');
                const afterElement = this.getDragAfterElement(bannerList, e.clientY);
                if (afterElement == null) {
                    bannerList.appendChild(draggingCard);
                } else {
                    bannerList.insertBefore(draggingCard, afterElement);
                }
            });
        });
        
        // Upload zone drag and drop
        if (uploadZone) {
            uploadZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadZone.classList.add('dragover');
            });
            
            uploadZone.addEventListener('dragleave', () => {
                uploadZone.classList.remove('dragover');
            });
            
            uploadZone.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleFileUpload(files[0]);
                }
            });
        }
    }
    
    getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.banner-card-2026:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
    
    setupPreviewControls() {
        // Previous/Next buttons
        window.previousBanner = () => {
            this.currentBannerIndex = (this.currentBannerIndex - 1 + this.banners.length) % this.banners.length;
            this.updatePreview();
        };
        
        window.nextBanner = () => {
            this.currentBannerIndex = (this.currentBannerIndex + 1) % this.banners.length;
            this.updatePreview();
        };
        
        window.toggleAutoSlide = () => {
            this.isAutoSliding = !this.isAutoSliding;
            const icon = document.getElementById('playPauseIcon');
            
            if (this.isAutoSliding) {
                icon.className = 'fas fa-pause';
                this.startAutoSlide();
            } else {
                icon.className = 'fas fa-play';
                this.stopAutoSlide();
            }
        };
    }
    
    updatePreview() {
        const preview = document.getElementById('bannerPreview');
        if (this.banners.length > 0 && this.banners[this.currentBannerIndex]) {
            const banner = this.banners[this.currentBannerIndex];
            preview.innerHTML = `<img src="${BASE_URL}${banner.image}" alt="${banner.title}">`;
        }
    }
    
    startAutoSlide() {
        const interval = parseInt(document.querySelector('input[name="slide_interval"]').value) || 5000;
        this.autoSlideInterval = setInterval(() => {
            this.nextBanner();
        }, interval);
    }
    
    stopAutoSlide() {
        if (this.autoSlideInterval) {
            clearInterval(this.autoSlideInterval);
            this.autoSlideInterval = null;
        }
    }
    
    loadSettings() {
        // Settings are loaded from PHP variables
    }
    
    handleFileUpload(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
}

// Global functions
window.showUploadModal = () => {
    const modal = new bootstrap.Modal(document.getElementById('bannerUploadModal'));
    modal.show();
};

window.previewUploadedImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
};

window.saveBanner = () => {
    // Implement banner save functionality
    const formData = new FormData(document.getElementById('bannerForm'));
    // ... save logic
    console.log('Saving banner:', formData);
};

window.editBanner = (id) => {
    console.log('Editing banner:', id);
};

window.previewBanner = (id) => {
    console.log('Previewing banner:', id);
};

window.toggleBannerStatus = (id) => {
    console.log('Toggling banner status:', id);
};

window.deleteBanner = (id) => {
    if (confirm('Are you sure you want to delete this banner?')) {
        console.log('Deleting banner:', id);
    }
};

window.filterBanners = (status) => {
    const cards = document.querySelectorAll('.banner-card-2026');
    cards.forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
};

window.showSettingsModal = () => {
    console.log('Show settings modal');
};

window.toggleSetting = (setting) => {
    console.log('Toggle setting:', setting);
};

window.updateSetting = (setting, value) => {
    console.log('Update setting:', setting, value);
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.bannerManagement = new BannerManagement2026();
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
