<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Invoices</h1>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="/client/invoices">
        <div class="row align-items-end">
            <div class="col-md-2">
                <label class="form-label">Show Entries</label>
                <select class="form-select" name="perPage">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Search invoice</label>
                <input type="text" class="form-control" name="search" placeholder="Search invoice number...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="past_due">Past Due</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Find
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Invoices Table -->
<div class="card shadow">
    <div class="card-body">
        <?php if (count($invoices) > 0): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Due Date</th>
                        <th>Invoice Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= date('Y-m-d', strtotime($invoice['due_date'])) ?></td>
                        <td><strong><?= esc($invoice['invoice_number']) ?></strong></td>
                        <td><strong>Rp <?= number_format($invoice['amount'], 2, ',', '.') ?></strong></td>
                        <td>
                            <span class="badge badge-<?= $invoice['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $invoice['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <?= $invoice['paid_date'] ? date('Y-m-d', strtotime($invoice['paid_date'])) : '-' ?>
                        </td>
                        <td>
                            <?php if ($invoice['status'] === 'unpaid' || $invoice['status'] === 'past_due'): ?>
                            <button class="btn btn-sm btn-primary">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-manage">
                                View <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing <?= count($invoices) ?> entries
            </div>
            
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <li class="page-item"><a class="page-link" href="#">First</a></li>
                    <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    <li class="page-item"><a class="page-link" href="#">Last</a></li>
                </ul>
            </nav>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-file-invoice"></i>
            <h5>No Invoices Yet</h5>
            <p>You don't have any invoices at the moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
