<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Create Invoice</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select Client <span class="text-danger">*</span></label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">-- Select Client --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
                                    <?= esc($user['full_name']) ?> (<?= esc($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Related Service (Optional)</label>
                        <select class="form-select" id="service_id" name="service_id">
                            <option value="">-- Select Service --</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>" <?= old('service_id') == $service['id'] ? 'selected' : '' ?>>
                                    <?= esc($service['product_name']) ?> - <?= esc($service['domain']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Select a service if this invoice is for a specific service</small>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                               value="<?= old('amount') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="due_date" name="due_date" 
                               value="<?= old('due_date') ?>" required>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> Invoice number will be auto-generated. Status will be set to "unpaid" by default.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Invoice
                        </button>
                        <a href="/admin/billing" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
