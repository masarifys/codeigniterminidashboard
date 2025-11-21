<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<link href="/assets/css/service-detail.css" rel="stylesheet">

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0"><?= esc($service['product_name']) ?></h1>
        <small class="text-muted"><?= esc($service['domain']) ?></small>
    </div>
</div>

<div class="row">
    <!-- Left Sidebar - Navigation Menu -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item bg-light">
                        <strong>Navigation</strong>
                    </div>
                    <a href="#information" class="list-group-item list-group-item-action active">
                        <i class="fas fa-info-circle"></i> Overview
                    </a>
                    <a href="#actions" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog"></i> Actions
                    </a>
                </div>
                
                <div class="list-group list-group-flush mt-3">
                    <div class="list-group-item bg-light">
                        <strong>Information</strong>
                    </div>
                    <div class="list-group-item">
                        <span class="badge badge-<?= $service['status'] ?>">
                            <?= ucfirst($service['status']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="list-group list-group-flush mt-3">
                    <div class="list-group-item bg-light">
                        <strong>Sub-menu</strong>
                    </div>
                    <a href="/client/service/<?= $service['id'] ?>/upgrade" class="list-group-item list-group-item-action">
                        <i class="fas fa-arrow-up"></i> Upgrade
                    </a>
                    <a href="/client/service/<?= $service['id'] ?>/renew" class="list-group-item list-group-item-action">
                        <i class="fas fa-sync"></i> Perpanjang
                    </a>
                    <a href="#" class="list-group-item list-group-item-action text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times-circle"></i> Batalkan Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-6 mb-4">
        <!-- Credentials Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-key"></i> Credentials</h5>
            </div>
            <div class="card-body">
                <div class="credential-item mb-3">
                    <label class="form-label"><strong>Username:</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= esc($service['username'] ?? 'Not set') ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('<?= esc($service['username'] ?? '') ?>')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="credential-item mb-3">
                    <label class="form-label"><strong>Password:</strong></label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="passwordField" value="<?= esc($service['password'] ?? 'Not set') ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('<?= esc($service['password'] ?? '') ?>')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="credential-item mb-3">
                    <label class="form-label"><strong>Server:</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= esc($service['server'] ?? 'Not set') ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('<?= esc($service['server'] ?? '') ?>')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow">
            <div class="card-body text-center">
                <a href="/client/support" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-headset"></i> Hubungi Kami
                </a>
                <?php if (!empty($service['panel_url'])): ?>
                <a href="<?= esc($service['panel_url']) ?>" target="_blank" class="btn btn-success btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Login Panel
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Status Card -->
    <div class="col-lg-3 mb-4">
        <div class="card shadow mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Status</h5>
            </div>
            <div class="card-body">
                <div class="status-item mb-3">
                    <small class="text-muted">Status</small>
                    <div>
                        <span class="badge badge-<?= $service['status'] ?> badge-lg">
                            <?= ucfirst($service['status']) ?>
                        </span>
                    </div>
                </div>

                <div class="status-item mb-3">
                    <small class="text-muted">Package</small>
                    <div><strong><?= esc($service['product_name']) ?></strong></div>
                </div>

                <div class="status-item mb-3">
                    <small class="text-muted">Registration Date</small>
                    <div><?= date('d M Y', strtotime($service['registration_date'])) ?></div>
                </div>

                <div class="status-item mb-3">
                    <small class="text-muted">Due Date</small>
                    <div><strong><?= date('d M Y', strtotime($service['due_date'])) ?></strong></div>
                </div>

                <div class="status-item mb-3">
                    <small class="text-muted">Billing Cycle</small>
                    <div><?= ucfirst($service['billing_cycle']) ?></div>
                </div>

                <div class="status-item mb-3">
                    <small class="text-muted">Price</small>
                    <div><strong>Rp <?= number_format($service['price'], 2, ',', '.') ?></strong></div>
                </div>

                <hr>

                <div class="status-item">
                    <small class="text-muted">Related Invoice</small>
                    <div>
                        <a href="/client/invoices" class="btn btn-sm btn-outline-primary w-100 mt-2">
                            <i class="fas fa-file-invoice"></i> View Invoices
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Domain & Hosting Info Card -->
        <?php if (!empty($service['registrar']) || !empty($service['hosting_provider']) || !empty($service['ssl_status'])): ?>
        <div class="card shadow mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-globe"></i> Domain & Hosting</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($service['registrar'])): ?>
                <div class="status-item mb-3">
                    <small class="text-muted">Registrar</small>
                    <div><?= esc($service['registrar']) ?></div>
                </div>
                <?php endif; ?>

                <?php if (!empty($service['domain_expiry_date'])): ?>
                <div class="status-item mb-3">
                    <small class="text-muted">Domain Expiry</small>
                    <div>
                        <?= date('d M Y', strtotime($service['domain_expiry_date'])) ?>
                        <?php
                        $daysUntilExpiry = floor((strtotime($service['domain_expiry_date']) - time()) / (60 * 60 * 24));
                        if ($daysUntilExpiry <= 30 && $daysUntilExpiry > 0):
                        ?>
                            <span class="badge bg-warning text-dark">Expires in <?= $daysUntilExpiry ?> days</span>
                        <?php elseif ($daysUntilExpiry <= 0): ?>
                            <span class="badge bg-danger">Expired</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($service['hosting_provider'])): ?>
                <div class="status-item mb-3">
                    <small class="text-muted">Hosting Provider</small>
                    <div><?= esc($service['hosting_provider']) ?></div>
                </div>
                <?php endif; ?>

                <?php if (!empty($service['hosting_renewal_date'])): ?>
                <div class="status-item mb-3">
                    <small class="text-muted">Hosting Renewal</small>
                    <div>
                        <?= date('d M Y', strtotime($service['hosting_renewal_date'])) ?>
                        <?php
                        $daysUntilRenewal = floor((strtotime($service['hosting_renewal_date']) - time()) / (60 * 60 * 24));
                        if ($daysUntilRenewal <= 30 && $daysUntilRenewal > 0):
                        ?>
                            <span class="badge bg-warning text-dark">Renews in <?= $daysUntilRenewal ?> days</span>
                        <?php elseif ($daysUntilRenewal <= 0): ?>
                            <span class="badge bg-danger">Expired</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="status-item mb-3">
                    <small class="text-muted">SSL Status</small>
                    <div>
                        <?php
                        $sslClass = [
                            'active' => 'bg-success',
                            'inactive' => 'bg-secondary',
                            'expiring_soon' => 'bg-warning'
                        ];
                        ?>
                        <span class="badge <?= $sslClass[$service['ssl_status'] ?? 'inactive'] ?>">
                            <?= ucfirst(str_replace('_', ' ', $service['ssl_status'] ?? 'inactive')) ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($service['ssl_expiry_date'])): ?>
                <div class="status-item mb-3">
                    <small class="text-muted">SSL Expiry</small>
                    <div><?= date('d M Y', strtotime($service['ssl_expiry_date'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Uptime Status Card -->
        <?php if (!empty($service['uptime_monitor_url'])): ?>
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-heartbeat"></i> Uptime Status</h5>
            </div>
            <div class="card-body">
                <div class="status-item mb-3">
                    <small class="text-muted">Status</small>
                    <div>
                        <?php
                        $uptimeClass = [
                            'up' => 'bg-success',
                            'down' => 'bg-danger',
                            'unknown' => 'bg-secondary'
                        ];
                        ?>
                        <span class="badge <?= $uptimeClass[$service['uptime_status'] ?? 'unknown'] ?>">
                            <?= strtoupper($service['uptime_status'] ?? 'unknown') ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($service['last_uptime_check'])): ?>
                <div class="status-item">
                    <small class="text-muted">Last Check</small>
                    <div><?= date('d M Y H:i', strtotime($service['last_uptime_check'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Cancel Service Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/client/service/<?= $service['id'] ?>/cancel" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cancellation Type</label>
                        <select class="form-select" name="cancellation_type" required>
                            <option value="end_of_billing_period">End of Billing Period</option>
                            <option value="immediate">Immediate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Cancellation</label>
                        <textarea class="form-control" name="reason" rows="4" placeholder="Please tell us why you want to cancel this service"></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Warning:</strong> Once submitted, this cancellation request will be reviewed by our team.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit Cancellation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('passwordField');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function copyToClipboard(text) {
    if (!text || text === 'Not set') {
        alert('No value to copy');
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        
        setTimeout(function() {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }).catch(function(err) {
        alert('Failed to copy text');
    });
}
</script>

<?= $this->endSection() ?>
