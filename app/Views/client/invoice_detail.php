<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Back Button -->
<div class="row mb-3">
    <div class="col-12">
        <a href="<?= base_url('client/invoices') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> All Invoices
        </a>
    </div>
</div>

<!-- Invoice Header -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-0">INVOICE #<?= esc($invoice['invoice_number']) ?></h2>
            </div>
            <div class="col-md-6 text-end">
                <?php if ($invoice['status'] === 'paid'): ?>
                    <span class="badge badge-paid fs-5 px-4 py-2">PAID</span>
                <?php else: ?>
                    <span class="badge badge-unpaid fs-5 px-4 py-2">UNPAID</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Information -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Invoiced To:</h5>
                <p class="mb-1"><strong><?= esc($user['full_name']) ?></strong></p>
                <p class="mb-1"><?= esc($user['email']) ?></p>
            </div>
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Pay To:</h5>
                <p class="mb-1"><strong>Your Company Name</strong></p>
                <p class="mb-1">Company Address Line 1</p>
                <p class="mb-1">Company Address Line 2</p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="mb-2"><strong>Invoice Date:</strong></p>
                <p><?= date('d/m/Y', strtotime($invoice['created_at'])) ?></p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>Payment Method:</strong></p>
                <p><?= $invoice['status'] === 'paid' && isset($transaction['gateway']) ? esc($transaction['gateway']) : 'Bank Transfer / Payment Gateway' ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="card shadow mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Invoice Items</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="70%">Description</th>
                        <th width="30%" class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    if (count($items) > 0): 
                        foreach ($items as $item): 
                            $subtotal += $item['amount'];
                    ?>
                    <tr>
                        <td><?= esc($item['description']) ?></td>
                        <td class="text-end">Rp <?= number_format($item['amount'], 2, ',', '.') ?></td>
                    </tr>
                    <?php 
                        endforeach;
                    else:
                        // If no items, show invoice amount as single item
                        $subtotal = $invoice['amount'];
                    ?>
                    <tr>
                        <td>Payment for Invoice <?= esc($invoice['invoice_number']) ?></td>
                        <td class="text-end">Rp <?= number_format($invoice['amount'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end"><strong>Sub Total:</strong></td>
                        <td class="text-end">Rp <?= number_format($subtotal, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="text-end"><strong>Credit:</strong></td>
                        <td class="text-end">Rp 0,00</td>
                    </tr>
                    <tr class="table-active">
                        <td class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-end"><strong>Rp <?= number_format($invoice['amount'], 2, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Transaction Details (only for paid invoices) -->
<?php if ($invoice['status'] === 'paid' && $transaction): ?>
<div class="card shadow mb-4">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Transaction Details</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Gateway</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= date('d/m/Y H:i:s', strtotime($transaction['transaction_date'])) ?></td>
                        <td><?= esc($transaction['gateway']) ?></td>
                        <td><?= esc($transaction['transaction_id']) ?></td>
                        <td>Rp <?= number_format($transaction['amount'], 2, ',', '.') ?></td>
                        <td>Rp 0,00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <?php if ($invoice['status'] === 'unpaid' || $invoice['status'] === 'past_due'): ?>
        <a href="<?= base_url('client/invoice/' . $invoice['id'] . '/pay') ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-credit-card"></i> Pay Now
        </a>
        <?php endif; ?>
        
        <?php if ($invoice['status'] === 'paid'): ?>
        <button class="btn btn-success btn-lg" onclick="alert('PDF download feature will be implemented soon')">
            <i class="fas fa-download"></i> Download PDF
        </button>
        <?php endif; ?>
        
        <a href="<?= base_url('client/invoices') ?>" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Back to All Invoices
        </a>
    </div>
</div>
<?= $this->endSection() ?>
