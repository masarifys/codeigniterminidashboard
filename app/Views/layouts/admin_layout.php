<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'Admin Panel' ?> - CodeIgniter Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="/assets/css/admin-style.css" rel="stylesheet">
    
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/admin/dashboard" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Admin Panel</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="/admin/dashboard" class="nav-link <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                                <i class="fas fa-tachometer-alt"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/clients" class="nav-link <?= (strpos(uri_string(), 'admin/client') !== false) ? 'active' : '' ?>">
                                <i class="fas fa-briefcase"></i> <span class="ms-1 d-none d-sm-inline">Clients</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/services" class="nav-link <?= (strpos(uri_string(), 'admin/service') !== false) ? 'active' : '' ?>">
                                <i class="fas fa-server"></i> <span class="ms-1 d-none d-sm-inline">Services</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/packages" class="nav-link <?= (strpos(uri_string(), 'admin/package') !== false) ? 'active' : '' ?>">
                                <i class="fas fa-box"></i> <span class="ms-1 d-none d-sm-inline">Packages</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/monitoring" class="nav-link <?= (strpos(uri_string(), 'admin/monitoring') !== false) ? 'active' : '' ?>">
                                <i class="fas fa-heartbeat"></i> <span class="ms-1 d-none d-sm-inline">Monitoring</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/billing" class="nav-link <?= (strpos(uri_string(), 'admin/billing') !== false || strpos(uri_string(), 'admin/invoice') !== false) ? 'active' : '' ?>">
                                <i class="fas fa-file-invoice-dollar"></i> <span class="ms-1 d-none d-sm-inline">Billing</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/users" class="nav-link <?= (uri_string() == 'admin/users') ? 'active' : '' ?>">
                                <i class="fas fa-users"></i> <span class="ms-1 d-none d-sm-inline">Users</span>
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="/admin/gmail-setup" class="nav-link <?= (strpos(uri_string(), 'admin/gmail-setup') !== false) ? 'active' : '' ?>">
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
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('info')): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('info') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
