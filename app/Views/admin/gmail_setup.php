<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="#" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Admin Panel</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="/admin/dashboard" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/users" class="nav-link">
                            <i class="fas fa-users"></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin/gmail-setup" class="nav-link active">
                            <i class="fas fa-envelope"></i> <span class="ms-1 d-none d-sm-inline">Gmail Setup</span>
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
                        <h1 class="h3 mb-0 text-gray-800">Gmail OAuth Setup</h1>
                        <p class="mb-0">Configure Gmail OAuth for sending emails</p>
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

                <!-- Gmail Status -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fab fa-google"></i> Gmail OAuth Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <?php if ($isAuthorized): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                                                <div>
                                                    <h5 class="mb-1 text-success">Gmail OAuth Authorized</h5>
                                                    <p class="mb-0 text-muted">Your application is authorized to send emails via Gmail</p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle text-warning fa-2x me-3"></i>
                                                <div>
                                                    <h5 class="mb-1 text-warning">Not Authorized</h5>
                                                    <p class="mb-0 text-muted">You need to authorize Gmail OAuth to send emails</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <?php if ($isAuthorized): ?>
                                            <a href="/admin/gmail-setup/test" class="btn btn-success me-2">
                                                <i class="fas fa-paper-plane"></i> Test Email
                                            </a>
                                            <a href="/admin/gmail-setup/revoke" class="btn btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to revoke Gmail authorization?')">
                                                <i class="fas fa-ban"></i> Revoke
                                            </a>
                                        <?php else: ?>
                                            <a href="/admin/gmail-setup/authorize" class="btn btn-primary">
                                                <i class="fab fa-google"></i> Authorize Gmail
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Setup Instructions -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-cog"></i> Setup Instructions</h6>
                            </div>
                            <div class="card-body">
                                <h6>📋 Prerequisites:</h6>
                                <ol>
                                    <li>Google Cloud Console project dengan Gmail API enabled</li>
                                    <li>OAuth2 Client ID sudah dibuat</li>
                                    <li>Redirect URI sudah dikonfigurasi</li>
                                    <li>Client ID dan Secret sudah dimasukkan di config</li>
                                </ol>

                                <h6 class="mt-3">🔐 OAuth Flow:</h6>
                                <ol>
                                    <li>Klik "Authorize Gmail" untuk memulai OAuth flow</li>
                                    <li>Login ke Google dan berikan permission</li>
                                    <li>Aplikasi akan redirect kembali dengan authorization code</li>
                                    <li>Access token akan disimpan untuk penggunaan selanjutnya</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Current Configuration</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>From Email:</strong></td>
                                        <td><?= config('Email')->fromEmail ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>From Name:</strong></td>
                                        <td><?= config('Email')->fromName ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Client ID:</strong></td>
                                        <td><?= substr(config('Email')->googleClientId, 0, 20) ?>...</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Redirect URI:</strong></td>
                                        <td><?= config('Email')->googleRedirectUri ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($isAuthorized): ?>
                                                <span class="badge bg-success">Authorized</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Not Authorized</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!$isAuthorized): ?>
                <!-- Authorization Help -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Need Help?</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Jika tombol "Authorize Gmail" tidak berfungsi:</strong></p>
                                <ol>
                                    <li>Pastikan Client ID dan Client Secret sudah benar di <code>app/Config/Email.php</code></li>
                                    <li>Pastikan Redirect URI sudah ditambahkan di Google Cloud Console</li>
                                    <li>Pastikan Gmail API sudah di-enable di Google Cloud Console</li>
                                    <li>Cek browser console untuk error JavaScript</li>
                                </ol>
                                
                                <div class="alert alert-warning mt-3">
                                    <strong>Manual Authorization URL:</strong><br>
                                    <small>Jika ada masalah, Anda bisa copy link ini ke browser:</small><br>
                                    <code style="word-break: break-all;"><?= $authUrl ?></code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>