<?php require APP_PATH . 'views/inc/admin/header.php'; ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><?php echo $data['title']; ?></h1>
        <a href="<?php echo URLROOT; ?>/admin/contact-info" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php
                $item = $data['item'] ?? null;
                $id = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
                $address = is_array($item) ? ($item['address'] ?? '') : ($item->address ?? '');
                $map_embed = is_array($item) ? ($item['map_embed'] ?? '') : ($item->map_embed ?? '');
                $phone = is_array($item) ? ($item['phone'] ?? '') : ($item->phone ?? '');
                $email = is_array($item) ? ($item['email'] ?? '') : ($item->email ?? '');
                $hours_weekdays = is_array($item) ? ($item['hours_weekdays'] ?? '') : ($item->hours_weekdays ?? '');
                $hours_weekends = is_array($item) ? ($item['hours_weekends'] ?? '') : ($item->hours_weekends ?? '');
            ?>
            <form action="<?php echo ($id !== null && $id !== '') ? URLROOT . '/admin/contact-info/update/' . $id : URLROOT . '/admin/contact-info/store'; ?>" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control <?php echo isset($data['errors']['address']) ? 'is-invalid' : ''; ?>" rows="3"><?php echo htmlspecialchars($address); ?></textarea>
                        <?php if (isset($data['errors']['address'])): ?><div class="invalid-feedback"><?php echo $data['errors']['address']; ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Map Embed (optional)</label>
                        <textarea name="map_embed" class="form-control" rows="3" placeholder="Paste Google Maps embed iframe HTML here"><?php echo htmlspecialchars($map_embed); ?></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control <?php echo isset($data['errors']['phone']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($phone); ?>">
                        <?php if (isset($data['errors']['phone'])): ?><div class="invalid-feedback"><?php echo $data['errors']['phone']; ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?php echo isset($data['errors']['email']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>">
                        <?php if (isset($data['errors']['email'])): ?><div class="invalid-feedback"><?php echo $data['errors']['email']; ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Weekday Hours</label>
                        <input type="text" name="hours_weekdays" class="form-control <?php echo isset($data['errors']['hours_weekdays']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($hours_weekdays); ?>" placeholder="e.g., Mon - Fri: 9:00 AM - 8:00 PM">
                        <?php if (isset($data['errors']['hours_weekdays'])): ?><div class="invalid-feedback"><?php echo $data['errors']['hours_weekdays']; ?></div><?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Weekend Hours</label>
                        <input type="text" name="hours_weekends" class="form-control <?php echo isset($data['errors']['hours_weekends']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($hours_weekends); ?>" placeholder="e.g., Sat - Sun: 10:00 AM - 6:00 PM">
                        <?php if (isset($data['errors']['hours_weekends'])): ?><div class="invalid-feedback"><?php echo $data['errors']['hours_weekends']; ?></div><?php endif; ?>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_PATH . 'views/inc/admin/footer.php'; ?>
