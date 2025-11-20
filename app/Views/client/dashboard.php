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
                        <a href="/client/dashboard" class="nav-link active">
                            <i class="fas fa-tachometer-alt"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/client/profile" class="nav-link">
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
                        <h1 class="h3 mb-0 text-gray-800">Client Dashboard</h1>
                        <p class="mb-0">Welcome back, <?= session()->get('full_name') ?>!</p>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-star"></i> Welcome to Your Dashboard</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6>Hello, <?= esc($user['full_name']) ?>!</h6>
                                        <p class="text-muted">You're logged in as a client user. Here you can manage your profile and access your personalized content.</p>
                                        <a href="/client/profile" class="btn btn-primary">
                                            <i class="fas fa-user-edit"></i> Edit Profile
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Account Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Full Name:</strong></td>
                                        <td><?= esc($user['full_name']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Username:</strong></td>
                                        <td><?= esc($user['username']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?= esc($user['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role:</strong></td>
                                        <td><span class="badge bg-primary"><?= ucfirst($user['role']) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Member Since:</strong></td>
                                        <td><?= date('F j, Y', strtotime($user['created_at'])) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Quick Stats</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-12 mb-3">
                                        <div class="border rounded p-3">
                                            <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                            <h5><?= date('Y-m-d', strtotime($user['created_at'])) != date('Y-m-d') ? floor((time() - strtotime($user['created_at'])) / (60 * 60 * 24)) : 0 ?></h5>
                                            <p class="text-muted mb-0">Days as Member</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i> Your account is secure and active
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-lightning-bolt"></i> Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="d-grid">
                                            <a href="/client/profile" class="btn btn-outline-primary">
                                                <i class="fas fa-user-edit"></i><br>
                                                Edit Profile
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-info" onclick="alert('Feature coming soon!')">
                                                <i class="fas fa-cog"></i><br>
                                                Settings
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="d-grid">
                                            <button class="btn btn-outline-success" onclick="alert('Feature coming soon!')">
                                                <i class="fas fa-question-circle"></i><br>
                                                Support
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>