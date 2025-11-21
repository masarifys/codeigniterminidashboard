<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0"><?= isset($package) ? 'Edit' : 'Create' ?> Service Package</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Package Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= old('name', $package['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $package['description'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" id="storage" name="storage" 
                                   value="<?= old('storage', $package['storage'] ?? '') ?>" 
                                   placeholder="e.g., 10 GB, Unlimited">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bandwidth" class="form-label">Bandwidth</label>
                            <input type="text" class="form-control" id="bandwidth" name="bandwidth" 
                                   value="<?= old('bandwidth', $package['bandwidth'] ?? '') ?>" 
                                   placeholder="e.g., 100 GB, Unlimited">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                   value="<?= old('price', $package['price'] ?? '') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                            <select class="form-select" id="billing_cycle" name="billing_cycle" required>
                                <option value="monthly" <?= old('billing_cycle', $package['billing_cycle'] ?? '') == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="quarterly" <?= old('billing_cycle', $package['billing_cycle'] ?? '') == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                                <option value="semi-annually" <?= old('billing_cycle', $package['billing_cycle'] ?? '') == 'semi-annually' ? 'selected' : '' ?>>Semi-Annually</option>
                                <option value="annually" <?= old('billing_cycle', $package['billing_cycle'] ?? '') == 'annually' ? 'selected' : '' ?>>Annually</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="features" class="form-label">Features</label>
                        <textarea class="form-control" id="features" name="features" rows="4"><?= old('features', $package['features'] ?? '') ?></textarea>
                        <small class="text-muted">List package features, one per line</small>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Admin Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes', $package['notes'] ?? '') ?></textarea>
                        <small class="text-muted">Internal notes for admin reference only</small>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                               <?= old('is_active', $package['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            Active (visible to clients for upgrade)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <a href="/admin/packages" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
