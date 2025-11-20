<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?>Client Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/client-style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 text-white min-vh-100">
                    <a href="/client/dashboard" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline fw-bold">Client Portal</span>
                    </a>
                    
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="nav-item w-100">
                            <a href="/client/dashboard" class="nav-link <?= (uri_string() == 'client/dashboard') ? 'active' : '' ?>">
                                <i class="fas fa-home"></i> <span class="d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        
                        <li class="w-100">
                            <div class="menu-section">PRODUCT & SERVICE</div>
                        </li>
                        
                        <li class="nav-item w-100">
                            <a href="/client/services" class="nav-link <?= (uri_string() == 'client/services') ? 'active' : '' ?>">
                                <i class="fas fa-box"></i> <span class="d-none d-sm-inline">Semua Layanan</span>
                                <span class="info-badge d-none d-sm-inline-flex" title="List all products & services">
                                    <i class="fas fa-question-circle"></i>
                                </span>
                            </a>
                        </li>
                        
                        <li class="w-100">
                            <div class="menu-section">BILLING</div>
                        </li>
                        
                        <li class="nav-item w-100">
                            <a href="/client/invoices" class="nav-link <?= (uri_string() == 'client/invoices') ? 'active' : '' ?>">
                                <i class="fas fa-file-invoice"></i> <span class="d-none d-sm-inline">Invoices</span>
                            </a>
                        </li>
                        
                        <li class="w-100">
                            <div class="menu-section">SUPPORT</div>
                        </li>
                        
                        <li class="nav-item w-100">
                            <a href="/client/support" class="nav-link <?= (uri_string() == 'client/support') ? 'active' : '' ?>">
                                <i class="fas fa-comments"></i> <span class="d-none d-sm-inline">Live Support</span>
                            </a>
                        </li>
                        
                        <li class="nav-item w-100">
                            <a href="/client/tickets" class="nav-link <?= (uri_string() == 'client/tickets') ? 'active' : '' ?>">
                                <i class="fas fa-ticket-alt"></i> <span class="d-none d-sm-inline">Trouble Ticket</span>
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="w-100">
                    
                    <div class="dropdown pb-4 w-100">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-3" id="dropdownUser1" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-2x"></i>
                            <span class="d-none d-sm-inline mx-2"><?= session()->get('full_name') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li><a class="dropdown-item" href="/client/profile"><i class="fas fa-user"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
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
                            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if(alert.classList.contains('alert-dismissible')) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
