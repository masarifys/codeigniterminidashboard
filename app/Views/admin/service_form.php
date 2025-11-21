<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Edit Service</h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" 
                                       value="<?= old('product_name', $service['product_name'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="domain" class="form-label">Domain</label>
                                <input type="text" class="form-control" id="domain" name="domain" 
                                       value="<?= old('domain', $service['domain'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                       value="<?= old('price', $service['price'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="billing_cycle" class="form-label">Billing Cycle</label>
                                <select class="form-select" id="billing_cycle" name="billing_cycle">
                                    <option value="monthly" <?= old('billing_cycle', $service['billing_cycle'] ?? '') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                    <option value="quarterly" <?= old('billing_cycle', $service['billing_cycle'] ?? '') == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                    <option value="semi-annually" <?= old('billing_cycle', $service['billing_cycle'] ?? '') == 'semi-annually' ? 'selected' : '' ?>>Semi-Annually</option>
                                    <option value="annually" <?= old('billing_cycle', $service['billing_cycle'] ?? '') == 'annually' ? 'selected' : '' ?>>Annually</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="registration_date" class="form-label">Registration Date</label>
                                <input type="date" class="form-control" id="registration_date" name="registration_date" 
                                       value="<?= old('registration_date', $service['registration_date'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" 
                                       value="<?= old('due_date', $service['due_date'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input type="text" class="form-control" id="ip_address" name="ip_address" 
                                       value="<?= old('ip_address', $service['ip_address'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= old('status', $service['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="suspended" <?= old('status', $service['status'] ?? '') == 'suspended' ? 'selected' : '' ?>>Suspended</option>
                                    <option value="cancelled" <?= old('status', $service['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    <option value="pending" <?= old('status', $service['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Credentials</h5>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= old('username', $service['username'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" 
                                       value="<?= old('password', $service['password'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="server" class="form-label">Server</label>
                                <input type="text" class="form-control" id="server" name="server" 
                                       value="<?= old('server', $service['server'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="panel_url" class="form-label">Panel URL</label>
                                <input type="url" class="form-control" id="panel_url" name="panel_url" 
                                       value="<?= old('panel_url', $service['panel_url'] ?? '') ?>">
                            </div>

                            <h5 class="mb-3 mt-4">Domain & Hosting Info</h5>

                            <div class="mb-3">
                                <label for="registrar" class="form-label">Domain Registrar</label>
                                <input type="text" class="form-control" id="registrar" name="registrar" 
                                       value="<?= old('registrar', $service['registrar'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="domain_expiry_date" class="form-label">Domain Expiry Date</label>
                                <input type="date" class="form-control" id="domain_expiry_date" name="domain_expiry_date" 
                                       value="<?= old('domain_expiry_date', $service['domain_expiry_date'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="hosting_provider" class="form-label">Hosting Provider</label>
                                <input type="text" class="form-control" id="hosting_provider" name="hosting_provider" 
                                       value="<?= old('hosting_provider', $service['hosting_provider'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="hosting_renewal_date" class="form-label">Hosting Renewal Date</label>
                                <input type="date" class="form-control" id="hosting_renewal_date" name="hosting_renewal_date" 
                                       value="<?= old('hosting_renewal_date', $service['hosting_renewal_date'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="ssl_status" class="form-label">SSL Status</label>
                                <select class="form-select" id="ssl_status" name="ssl_status">
                                    <option value="active" <?= old('ssl_status', $service['ssl_status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= old('ssl_status', $service['ssl_status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="expiring_soon" <?= old('ssl_status', $service['ssl_status'] ?? '') == 'expiring_soon' ? 'selected' : '' ?>>Expiring Soon</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="ssl_expiry_date" class="form-label">SSL Expiry Date</label>
                                <input type="date" class="form-control" id="ssl_expiry_date" name="ssl_expiry_date" 
                                       value="<?= old('ssl_expiry_date', $service['ssl_expiry_date'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="uptime_monitor_url" class="form-label">Uptime Monitor URL</label>
                                <input type="url" class="form-control" id="uptime_monitor_url" name="uptime_monitor_url" 
                                       value="<?= old('uptime_monitor_url', $service['uptime_monitor_url'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="/admin/services" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
