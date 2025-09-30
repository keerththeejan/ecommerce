<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fs-3 mb-0"><?php echo $mode === 'edit' ? 'Edit Address' : 'Add Address'; ?></h1>
    <a href="<?php echo BASE_URL; ?>?controller=address" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-1"></i> Back to Addresses
    </a>
  </div>

  <?php flash('address_error', '', 'alert alert-danger'); ?>

  <form action="<?php echo $action; ?>" method="post" class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Type</label>
          <select name="type" class="form-select <?php echo isset($errors['type']) ? 'is-invalid' : ''; ?>">
            <option value="shipping" <?php echo $data['type'] === 'shipping' ? 'selected' : ''; ?>>Shipping</option>
            <option value="billing" <?php echo $data['type'] === 'billing' ? 'selected' : ''; ?>>Billing</option>
          </select>
          <?php if(isset($errors['type'])): ?><div class="invalid-feedback"><?php echo $errors['type']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" value="<?php echo htmlspecialchars($data['first_name']); ?>" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['first_name'])): ?><div class="invalid-feedback"><?php echo $errors['first_name']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" value="<?php echo htmlspecialchars($data['last_name']); ?>" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['last_name'])): ?><div class="invalid-feedback"><?php echo $errors['last_name']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
          <label class="form-label">Company (optional)</label>
          <input type="text" name="company" value="<?php echo htmlspecialchars($data['company']); ?>" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone (optional)</label>
          <input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Address Line 1</label>
          <input type="text" name="address1" value="<?php echo htmlspecialchars($data['address1']); ?>" class="form-control <?php echo isset($errors['address1']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['address1'])): ?><div class="invalid-feedback"><?php echo $errors['address1']; ?></div><?php endif; ?>
        </div>
        <div class="col-12">
          <label class="form-label">Address Line 2 (optional)</label>
          <input type="text" name="address2" value="<?php echo htmlspecialchars($data['address2']); ?>" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">City</label>
          <input type="text" name="city" value="<?php echo htmlspecialchars($data['city']); ?>" class="form-control <?php echo isset($errors['city']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['city'])): ?><div class="invalid-feedback"><?php echo $errors['city']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label">State/Province</label>
          <input type="text" name="state" value="<?php echo htmlspecialchars($data['state']); ?>" class="form-control <?php echo isset($errors['state']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['state'])): ?><div class="invalid-feedback"><?php echo $errors['state']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label">Postal Code</label>
          <input type="text" name="postal_code" value="<?php echo htmlspecialchars($data['postal_code']); ?>" class="form-control <?php echo isset($errors['postal_code']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['postal_code'])): ?><div class="invalid-feedback"><?php echo $errors['postal_code']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
          <label class="form-label">Country</label>
          <input type="text" name="country" value="<?php echo htmlspecialchars($data['country']); ?>" class="form-control <?php echo isset($errors['country']) ? 'is-invalid' : ''; ?>">
          <?php if(isset($errors['country'])): ?><div class="invalid-feedback"><?php echo $errors['country']; ?></div><?php endif; ?>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" <?php echo !empty($data['is_default']) ? 'checked' : ''; ?>>
            <label class="form-check-label" for="is_default">Set as default for this type</label>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
      <a href="<?php echo BASE_URL; ?>?controller=address" class="btn btn-outline-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary"><?php echo $mode === 'edit' ? 'Update' : 'Create'; ?></button>
    </div>
  </form>
</div>
<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
