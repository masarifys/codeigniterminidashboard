<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <p class="mb-0 text-muted">Welcome back, <?= esc($user['full_name']) ?>!</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card blue">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number"><?= $activeServices ?></div>
                        <div class="stat-label">Active Service</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-server"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card stat-card yellow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number"><?= $unpaidInvoices ?></div>
                        <div class="stat-label">Invoice Unpaid</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card stat-card red">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-number"><?= $openTickets ?></div>
                        <div class="stat-label">Ticket Open</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoices Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="section-header">
                    <h5>Invoices</h5>
                    <div class="section-status">
                        <span class="me-3"><strong><?= $unpaidInvoices ?></strong> unpaid</span>
                        <span><strong><?= $pastDueInvoices ?></strong> past due</span>
                    </div>
                </div>
                
                <?php if (count($recentInvoices) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Due Date</th>
                                <th>No. Invoice</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentInvoices as $invoice): ?>
                            <tr>
                                <td><?= date('Y-m-d', strtotime($invoice['due_date'])) ?></td>
                                <td><?= esc($invoice['invoice_number']) ?></td>
                                <td>Rp <?= number_format($invoice['amount'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge badge-<?= $invoice['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $invoice['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-file-invoice"></i>
                    <h5>No Invoices Yet</h5>
                    <p>You don't have any invoices at the moment.</p>
                </div>
                <?php endif; ?>
                
                <div class="d-grid mt-3">
                    <a href="/client/invoices" class="btn btn-view-all">
                        View All Invoices
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Guides Section -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-3">Guides</h5>
                <ul class="guide-list">
                    <li><a href="#"><i class="fas fa-book-open"></i> Getting Started with Your Services</a></li>
                    <li><a href="#"><i class="fas fa-book-open"></i> How to Manage Your Account</a></li>
                    <li><a href="#"><i class="fas fa-book-open"></i> Understanding Your Invoices</a></li>
                    <li><a href="#"><i class="fas fa-book-open"></i> Contact Support</a></li>
                    <li><a href="#"><i class="fas fa-book-open"></i> Frequently Asked Questions</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>