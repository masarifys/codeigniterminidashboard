<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Billing Management</h1>
        <a href="/admin/invoice/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Invoice
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4 mb-3">
        <div class="card shadow border-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unpaid</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $unpaidCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card shadow border-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Paid</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $paidCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="card shadow border-danger">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Past Due</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pastDueCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Paid Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invoices)): ?>
                                <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?= esc($invoice['invoice_number']) ?></td>
                                    <td><?= esc($invoice['full_name']) ?></td>
                                    <td>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <?php if (!empty($invoice['paid_date'])): ?>
                                            <?= date('M d, Y', strtotime($invoice['paid_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'paid' => 'bg-success',
                                            'unpaid' => 'bg-warning',
                                            'past_due' => 'bg-danger',
                                            'pending' => 'bg-info',
                                            'cancelled' => 'bg-secondary'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$invoice['status']] ?? 'bg-secondary' ?>">
                                            <?= strtoupper($invoice['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No invoices found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
