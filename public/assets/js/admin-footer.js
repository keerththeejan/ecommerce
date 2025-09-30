/**
 * Admin Footer Management
 * Handles AJAX loading of footer management content in the admin dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Load footer management content when the page loads
    loadFooterManagement();
    
    // Handle clicks on the footer management link
    document.querySelector('a[href*="admin/footer"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        loadFooterManagement();
    });
    
    // Handle browser history navigation (back/forward buttons)
    window.addEventListener('popstate', function(event) {
        if (window.location.href.includes('admin/footer')) {
            loadFooterManagement();
        }
    });
});

/**
 * Load the footer management content via AJAX
 */
function loadFooterManagement() {
    const contentArea = document.querySelector('.main-content');
    if (!contentArea) return;
    
    // Show loading state
    const loadingHtml = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading footer management...</p>
        </div>
    `;
    contentArea.innerHTML = loadingHtml;
    
    // Update browser URL without reloading the page
    const newUrl = `${BASE_URL}admin/footer`;
    window.history.pushState({ path: newUrl }, '', newUrl);
    
    // Load the footer management content
    fetch(`${BASE_URL}admin/footer`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        // Update the content area with the loaded HTML
        contentArea.innerHTML = html;
        
        // Initialize any components in the loaded content
        initializeFooterComponents();
    })
    .catch(error => {
        console.error('Error loading footer management:', error);
        contentArea.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Failed to load footer management. Please try again.
                <button class="btn btn-link p-0 ms-2" onclick="loadFooterManagement()">
                    <i class="fas fa-sync-alt"></i> Retry
                </button>
            </div>
        `;
    });
}

/**
 * Initialize components for the footer management page
 */
function initializeFooterComponents() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize sortable functionality if it exists
    if (typeof $.fn.sortable === 'function' && document.getElementById('sortable')) {
        $('#sortable').sortable({
            update: function(event, ui) {
                updateSectionOrder();
            }
        });
    }
    
    // Handle status toggles
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', handleStatusToggle);
    });
    
    // Handle save order button
    const saveOrderBtn = document.getElementById('saveOrderBtn');
    if (saveOrderBtn) {
        saveOrderBtn.addEventListener('click', updateSectionOrder);
    }
    
    // Handle add new section button
    const addNewBtn = document.querySelector('a[href*="/admin/footer/add"]');
    if (addNewBtn) {
        addNewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href; // Navigate to the add page
        });
    }
}

/**
 * Handle status toggle for footer sections
 */
function handleStatusToggle(e) {
    const toggle = e.target;
    const sectionId = toggle.getAttribute('data-id');
    const status = toggle.checked ? 'active' : 'inactive';
    
    // Show loading state
    const originalState = toggle.checked;
    toggle.disabled = true;
    
    fetch(`${BASE_URL}admin/footer/update-status/${sectionId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            // Revert the toggle if update failed
            toggle.checked = !originalState;
            showAlert('danger', data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toggle.checked = !originalState; // Revert on error
        showAlert('danger', 'An error occurred. Please try again.');
    })
    .finally(() => {
        toggle.disabled = false;
    });
}

/**
 * Update the order of footer sections
 */
function updateSectionOrder() {
    const order = [];
    document.querySelectorAll('#sortable tr').forEach((row, index) => {
        const sectionId = row.getAttribute('data-id');
        if (sectionId) {
            order.push({
                id: sectionId,
                sort_order: index + 1
            });
        }
    });
    
    // Show loading state
    const saveBtn = document.getElementById('saveOrderBtn');
    const originalText = saveBtn ? saveBtn.innerHTML : '';
    
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';
    }
    
    // Send AJAX request to update order
    fetch(`${BASE_URL}admin/footer/update-order`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ order: order })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Section order updated successfully');
        } else {
            showAlert('danger', data.message || 'Failed to update section order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred while saving the order');
    })
    .finally(() => {
        // Reset button state
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    });
}

/**
 * Show an alert message
 */
function showAlert(type, message) {
    // Remove any existing alerts
    const existingAlerts = document.querySelectorAll('.alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create alert element
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert after the page header or at the top of the content area
    const header = document.querySelector('.card-header') || document.querySelector('.main-content');
    if (header) {
        header.insertAdjacentHTML('afterend', alertHtml);
    }
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-dismissible');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Make functions available globally
window.loadFooterManagement = loadFooterManagement;
