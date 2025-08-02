<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Store Settings</h2>

            <?php flash('setting_success'); ?>
            <?php flash('setting_error', '', 'alert alert-danger'); ?>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="store-tab" data-bs-toggle="tab" data-bs-target="#store" type="button" role="tab" aria-controls="store" aria-selected="false">Store</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">Email</button>
                        </li>
                       
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateGeneral" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="site_name" class="form-label">Site Name</label>
                                        <input type="text" class="form-control <?php echo isset($errors['site_name']) ? 'is-invalid' : ''; ?>" id="site_name" name="site_name" value="<?php echo isset($generalSettings['site_name']) ? $generalSettings['site_name'] : ''; ?>" required>
                                        <?php if (isset($errors['site_name'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['site_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="site_email" class="form-label">Site Email</label>
                                        <input type="email" class="form-control <?php echo isset($errors['site_email']) ? 'is-invalid' : ''; ?>" id="site_email" name="site_email" value="<?php echo isset($generalSettings['site_email']) ? $generalSettings['site_email'] : ''; ?>" required>
                                        <?php if (isset($errors['site_email'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['site_email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="site_description" class="form-label">Site Description</label>
                                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo isset($generalSettings['site_description']) ? htmlspecialchars($generalSettings['site_description']) : ''; ?></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="site_logo" class="form-label">Site Logo</label>
                                        <?php
                                        $logoFile = !empty($generalSettings['site_logo']) ? $generalSettings['site_logo'] : '';
                                        $logoPath = UPLOAD_PATH . $logoFile;
                                        $logoUrl = !empty($logoFile) ? BASE_URL . 'public/uploads/' . $logoFile : '';
                                        ?>
                                        <?php if (!empty($logoFile) && file_exists($logoPath)): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo $logoUrl; ?>" alt="Current Logo" class="img-thumbnail mb-2" style="max-height: 100px;">
                                                <div class="form-text">
                                                    File: <?php echo htmlspecialchars($logoFile); ?>
                                                    <br>Path: <?php echo htmlspecialchars($logoPath); ?>
                                                    <br>URL: <a href="<?php echo $logoUrl; ?>" target="_blank"><?php echo htmlspecialchars($logoUrl); ?></a>
                                                </div>
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                                                    <label class="form-check-label" for="remove_logo">Remove logo</label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <input type="hidden" name="current_logo" value="<?php echo !empty($generalSettings['site_logo']) ? htmlspecialchars($generalSettings['site_logo']) : ''; ?>">
                                        <div class="form-text">Recommended size: 200x50px. Max size: 2MB. Allowed formats: JPG, PNG, GIF, WEBP</div>
                                    </div>
                                </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="site_phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="site_phone" name="site_phone" value="<?php echo isset($generalSettings['site_phone']) ? $generalSettings['site_phone'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="site_logo" class="form-label">Logo URL</label>
                                <input type="text" class="form-control" id="site_logo" name="site_logo" value="<?php echo isset($generalSettings['site_logo']) ? $generalSettings['site_logo'] : ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="site_address" class="form-label">Address</label>
                            <textarea class="form-control" id="site_address" name="site_address" rows="3"><?php echo isset($generalSettings['site_address']) ? $generalSettings['site_address'] : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="site_favicon" class="form-label">Favicon URL</label>
                            <input type="text" class="form-control" id="site_favicon" name="site_favicon" value="<?php echo isset($generalSettings['site_favicon']) ? $generalSettings['site_favicon'] : ''; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save General Settings</button>
                        </form>
                    </div>

                    <!-- Store Settings -->
                    <div class="tab-pane fade" id="store" role="tabpanel" aria-labelledby="store-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateStore" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="store_currency" class="form-label">Currency</label>
                                    <input type="text" class="form-control <?php echo isset($errors['store_currency']) ? 'is-invalid' : ''; ?>" id="store_currency" name="store_currency" value="<?php echo isset($storeSettings['store_currency']) ? $storeSettings['store_currency'] : 'INR'; ?>" required>
                                    <?php if (isset($errors['store_currency'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['store_currency']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="store_currency_symbol" class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control <?php echo isset($errors['store_currency_symbol']) ? 'is-invalid' : ''; ?>" id="store_currency_symbol" name="store_currency_symbol" value="<?php echo isset($storeSettings['store_currency_symbol']) ? $storeSettings['store_currency_symbol'] : '₹'; ?>" required>
                                    <?php if (isset($errors['store_currency_symbol'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['store_currency_symbol']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Save Store Settings</button>
                        </form>
                    </div>

                    <!-- Payment Settings -->
                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updatePayment" method="POST">
                            <div class="card mb-3">
                                <div class="card-header">Cash on Delivery</div>
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="payment_cod_enabled" name="payment_cod_enabled" value="1" <?php echo (isset($paymentSettings['payment_cod_enabled']) && $paymentSettings['payment_cod_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_cod_enabled">Enable Cash on Delivery</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">Bank Transfer</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="payment_bank_transfer_enabled" name="payment_bank_transfer_enabled" value="1" <?php echo (isset($paymentSettings['payment_bank_transfer_enabled']) && $paymentSettings['payment_bank_transfer_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_bank_transfer_enabled">Enable Bank Transfer</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_bank_details" class="form-label">Bank Account Details</label>
                                        <textarea class="form-control" id="payment_bank_details" name="payment_bank_details" rows="3"><?php echo isset($paymentSettings['payment_bank_details']) ? $paymentSettings['payment_bank_details'] : ''; ?></textarea>
                                        <div class="form-text">Enter the bank account details that will be shown to customers.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">PayPal</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="payment_paypal_enabled" name="payment_paypal_enabled" value="1" <?php echo (isset($paymentSettings['payment_paypal_enabled']) && $paymentSettings['payment_paypal_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_paypal_enabled">Enable PayPal</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_paypal_email" class="form-label">PayPal Email</label>
                                        <input type="email" class="form-control <?php echo isset($errors['payment_paypal_email']) ? 'is-invalid' : ''; ?>" id="payment_paypal_email" name="payment_paypal_email" value="<?php echo isset($paymentSettings['payment_paypal_email']) ? $paymentSettings['payment_paypal_email'] : ''; ?>">
                                        <?php if (isset($errors['payment_paypal_email'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['payment_paypal_email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="payment_paypal_sandbox" name="payment_paypal_sandbox" value="1" <?php echo (isset($paymentSettings['payment_paypal_sandbox']) && $paymentSettings['payment_paypal_sandbox'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_paypal_sandbox">PayPal Sandbox Mode</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Payment Settings</button>
                        </form>
                    </div>

                    <!-- Email Settings -->
                    <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateEmail" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email_from_name" class="form-label">From Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['email_from_name']) ? 'is-invalid' : ''; ?>" id="email_from_name" name="email_from_name" value="<?php echo isset($emailSettings['email_from_name']) ? $emailSettings['email_from_name'] : ''; ?>" required>
                                    <?php if (isset($errors['email_from_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email_from_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="email_from_address" class="form-label">From Email Address</label>
                                    <input type="email" class="form-control <?php echo isset($errors['email_from_address']) ? 'is-invalid' : ''; ?>" id="email_from_address" name="email_from_address" value="<?php echo isset($emailSettings['email_from_address']) ? $emailSettings['email_from_address'] : ''; ?>" required>
                                    <?php if (isset($errors['email_from_address'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email_from_address']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">SMTP Settings</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_smtp_enabled" name="email_smtp_enabled" value="1" <?php echo (isset($emailSettings['email_smtp_enabled']) && $emailSettings['email_smtp_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_smtp_enabled">Use SMTP for sending emails</label>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email_smtp_host" class="form-label">SMTP Host</label>
                                            <input type="text" class="form-control <?php echo isset($errors['email_smtp_host']) ? 'is-invalid' : ''; ?>" id="email_smtp_host" name="email_smtp_host" value="<?php echo isset($emailSettings['email_smtp_host']) ? $emailSettings['email_smtp_host'] : ''; ?>">
                                            <?php if (isset($errors['email_smtp_host'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['email_smtp_host']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email_smtp_port" class="form-label">SMTP Port</label>
                                            <input type="text" class="form-control <?php echo isset($errors['email_smtp_port']) ? 'is-invalid' : ''; ?>" id="email_smtp_port" name="email_smtp_port" value="<?php echo isset($emailSettings['email_smtp_port']) ? $emailSettings['email_smtp_port'] : ''; ?>">
                                            <?php if (isset($errors['email_smtp_port'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['email_smtp_port']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email_smtp_username" class="form-label">SMTP Username</label>
                                            <input type="text" class="form-control" id="email_smtp_username" name="email_smtp_username" value="<?php echo isset($emailSettings['email_smtp_username']) ? $emailSettings['email_smtp_username'] : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email_smtp_password" class="form-label">SMTP Password</label>
                                            <input type="password" class="form-control" id="email_smtp_password" name="email_smtp_password" value="<?php echo isset($emailSettings['email_smtp_password']) ? $emailSettings['email_smtp_password'] : ''; ?>">
                                            <div class="form-text">Leave empty to keep the current password.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_smtp_encryption" class="form-label">Encryption</label>
                                        <select class="form-select" id="email_smtp_encryption" name="email_smtp_encryption">
                                            <option value="" <?php echo (!isset($emailSettings['email_smtp_encryption']) || $emailSettings['email_smtp_encryption'] == '') ? 'selected' : ''; ?>>None</option>
                                            <option value="tls" <?php echo (isset($emailSettings['email_smtp_encryption']) && $emailSettings['email_smtp_encryption'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                                            <option value="ssl" <?php echo (isset($emailSettings['email_smtp_encryption']) && $emailSettings['email_smtp_encryption'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Email Settings</button>

                            
                        </form>

                        
                    </div>

                   
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>