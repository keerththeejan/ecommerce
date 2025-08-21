<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<?php
// Helper to safely fetch value from array/object
function ci_value($item, $key, $default = '') {
    if (is_array($item)) return isset($item[$key]) ? $item[$key] : $default;
    if (is_object($item)) return isset($item->$key) ? $item->$key : $default;
    return $default;
}

$isEdit = isset($data['item']) && (is_array($data['item']) ? !empty($data['item']['id']) : !empty($data['item']->id));
$actionUrl = BASE_URL . '?controller=contactinfo&action=' . ($isEdit ? 'update&id=' . (int)ci_value($data['item'], 'id') : 'store');
$errors = isset($data['errors']) ? $data['errors'] : [];
$item = isset($data['item']) ? $data['item'] : [];
?>

<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><?php echo $isEdit ? 'Edit Contact Info' : 'Add Contact Info'; ?></h3>
    <a href="<?php echo BASE_URL; ?>?controller=contactinfo&action=index" class="btn btn-secondary">
      <i class="fas fa-arrow-left me-1"></i> Back
    </a>
  </div>

  <?php flash('contact_info_message'); ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="<?php echo $actionUrl; ?>" method="post">
        <div class="mb-3">
          <label class="form-label">Address <span class="text-danger">*</span></label>
          <textarea name="address" class="form-control <?php echo isset($errors['address']) ? 'is-invalid' : ''; ?>" rows="3"><?php echo htmlspecialchars(ci_value($item, 'address')); ?></textarea>
          <?php if (isset($errors['address'])): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['address']); ?></div><?php endif; ?>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars(ci_value($item, 'phone')); ?>">
            <?php if (isset($errors['phone'])): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['phone']); ?></div><?php endif; ?>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars(ci_value($item, 'email')); ?>">
            <?php if (isset($errors['email'])): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div><?php endif; ?>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Business Hours (Weekdays) <span class="text-danger">*</span></label>
            <input type="text" name="hours_weekdays" class="form-control <?php echo isset($errors['hours_weekdays']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars(ci_value($item, 'hours_weekdays')); ?>" placeholder="e.g., Mon-Fri: 9am - 6pm">
            <?php if (isset($errors['hours_weekdays'])): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['hours_weekdays']); ?></div><?php endif; ?>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Business Hours (Weekends) <span class="text-danger">*</span></label>
            <input type="text" name="hours_weekends" class="form-control <?php echo isset($errors['hours_weekends']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars(ci_value($item, 'hours_weekends')); ?>" placeholder="e.g., Sat-Sun: 10am - 4pm">
            <?php if (isset($errors['hours_weekends'])): ?><div class="invalid-feedback"><?php echo htmlspecialchars($errors['hours_weekends']); ?></div><?php endif; ?>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Google Map Embed (optional)</label>
          <textarea name="map_embed" class="form-control" rows="4" placeholder="Paste your Google Map embed iframe here"><?php echo htmlspecialchars(ci_value($item, 'map_embed')); ?></textarea>
          <div class="form-text">You can get the embed HTML from Google Maps > Share > Embed a map.</div>
        </div>

        <div class="d-flex justify-content-end">
          <a href="<?php echo BASE_URL; ?>?controller=contactinfo&action=index" class="btn btn-outline-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update' : 'Create'; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
