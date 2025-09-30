<?php
// Staff only
if(!isStaff()) { redirect('user/login'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Session</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>?controller=pos">POS System</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos">POS</a></li>
      <li class="nav-item"><a class="nav-link active" href="<?php echo BASE_URL; ?>?controller=pos&action=session">Session</a></li>
    </ul>
  </div>
</nav>
<div class="container py-4">
  <?php flash('pos_success'); ?>
  <?php flash('pos_error', '', 'alert alert-danger'); ?>

  <?php if(!empty($session)) : ?>
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">Active Session</div>
      <div class="card-body">
        <p class="mb-3">Session ID: <strong><?php echo $session['id']; ?></strong></p>
        <form method="post" action="<?php echo BASE_URL; ?>?controller=pos&action=session">
          <input type="hidden" name="action" value="close">
          <div class="mb-3">
            <label class="form-label">Closing Balance</label>
            <input type="number" step="0.01" name="closing_balance" class="form-control" required>
            <?php if(!empty($errors['closing_balance'])) echo '<div class="text-danger">'.$errors['closing_balance'].'</div>'; ?>
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
          <button class="btn btn-danger">Close Session</button>
          <a href="<?php echo BASE_URL; ?>?controller=pos" class="btn btn-secondary ms-2">Go to POS</a>
        </form>
      </div>
    </div>
  <?php else: ?>
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">Open New Session</div>
      <div class="card-body">
        <form method="post" action="<?php echo BASE_URL; ?>?controller=pos&action=session">
          <div class="mb-3">
            <label class="form-label">Opening Balance</label>
            <input type="number" step="0.01" name="opening_balance" class="form-control" value="<?php echo htmlspecialchars($data['opening_balance']); ?>" required>
            <?php if(!empty($errors['opening_balance'])) echo '<div class="text-danger">'.$errors['opening_balance'].'</div>'; ?>
          </div>
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea class="form-control" name="notes" rows="2"><?php echo htmlspecialchars($data['notes']); ?></textarea>
          </div>
          <button class="btn btn-primary">Open Session</button>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
