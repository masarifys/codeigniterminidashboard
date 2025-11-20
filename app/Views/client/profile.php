<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Client Portal</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="/client/dashboard" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/client/profile" class="nav-link active">
                            <i class="fas fa-user"></i> <span class="ms-1 d-none d-sm-inline">Profile</span>
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-2x"></i>
                        <span class="d-none d-sm-inline mx-1"><?= session()->get('full_name') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="/client/profile"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
            <div class="container-fluid py-4">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
                        <p class="mb-0">Manage your personal information</p>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>