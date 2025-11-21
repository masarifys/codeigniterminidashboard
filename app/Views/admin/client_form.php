<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0"><?= isset($client) ? 'Edit' : 'Create' ?> Client</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form method="POST">
                    <?php if (isset($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!isset($client)): ?>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User <span class="text-danger">*</span></label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">-- Select User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
                                    <?= esc($user['full_name']) ?> (<?= esc($user['username']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="business_name" name="business_name" 
                               value="<?= old('business_name', $client['business_name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" 
                               value="<?= old('contact_person', $client['contact_person'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contact_email" class="form-label">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                               value="<?= old('contact_email', $client['contact_email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                               value="<?= old('contact_phone', $client['contact_phone'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" class="form-control" id="domain" name="domain" 
                               value="<?= old('domain', $client['domain'] ?? '') ?>" 
                               placeholder="example.com">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Project Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="progress" <?= old('status', $client['status'] ?? '') == 'progress' ? 'selected' : '' ?>>Progress</option>
                            <option value="revision" <?= old('status', $client['status'] ?? '') == 'revision' ? 'selected' : '' ?>>Revision</option>
                            <option value="completed" <?= old('status', $client['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= old('status', $client['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4"><?= old('notes', $client['notes'] ?? '') ?></textarea>
                        <small class="text-muted">Special notes for this client</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <a href="/admin/clients" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
