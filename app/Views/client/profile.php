<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
        <p class="mb-0 text-muted">Manage your personal information</p>
    </div>
</div>

<!-- Profile Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user-edit"></i> Edit Profile</h6>
                            </div>
                            <div class="card-body">
                                <?= form_open('/client/profile') ?>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   value="<?= old('full_name', $user['full_name']) ?>" required>
                                            <?php if (isset($validation) && $validation->hasError('full_name')): ?>
                                                <div class="text-danger small mt-1"><?= $validation->getError('full_name') ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" value="<?= esc($user['username']) ?>" disabled>
                                            <small class="text-muted">Username cannot be changed</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?= old('email', $user['email']) ?>" required>
                                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                                            <div class="text-danger small mt-1"><?= $validation->getError('email') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="role" class="form-label">Role</label>
                                            <input type="text" class="form-control" id="role" value="<?= ucfirst($user['role']) ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="created_at" class="form-label">Member Since</label>
                                            <input type="text" class="form-control" id="created_at" 
                                                   value="<?= date('F j, Y', strtotime($user['created_at'])) ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <a href="/client/dashboard" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Profile
                                        </button>
                                    </div>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Profile Info</h6>
                            </div>
                            <div class="card-body text-center">
                                <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                                <h5><?= esc($user['full_name']) ?></h5>
                                <p class="text-muted"><?= esc($user['username']) ?></p>
                                <span class="badge bg-primary mb-3"><?= ucfirst($user['role']) ?></span>
                                <hr>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Last updated: <?= date('M j, Y', strtotime($user['updated_at'])) ?>
                                </small>
                            </div>
                        </div>

                        <div class="card shadow mt-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-shield-alt"></i> Security</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Keep your account secure:</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Strong password</li>
                                    <li><i class="fas fa-check text-success"></i> Email verified</li>
                                    <li><i class="fas fa-check text-success"></i> Account active</li>
                                </ul>
                                <button class="btn btn-outline-warning btn-sm" onclick="alert('Feature coming soon!')">
                                    <i class="fas fa-key"></i> Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
<?= $this->endSection() ?>