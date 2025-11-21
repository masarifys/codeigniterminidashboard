<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0"><?= esc($client['business_name']) ?></h1>
        <div>
            <a href="/admin/client/<?= $client['id'] ?>/edit" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="/admin/clients" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Client Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Business Name:</th>
                        <td><?= esc($client['business_name']) ?></td>
                    </tr>
                    <tr>
                        <th>Contact Person:</th>
                        <td><?= esc($client['contact_person'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Contact Email:</th>
                        <td><?= esc($client['contact_email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Contact Phone:</th>
                        <td><?= esc($client['contact_phone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Domain:</th>
                        <td><?= esc($client['domain'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <?php
                            $badgeClass = [
                                'progress' => 'bg-info',
                                'revision' => 'bg-warning',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger'
                            ];
                            ?>
                            <span class="badge <?= $badgeClass[$client['status']] ?? 'bg-secondary' ?>">
                                <?= ucfirst($client['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>User Account:</th>
                        <td><?= esc($client['full_name']) ?> (<?= esc($client['username']) ?>)</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td><?= date('M d, Y', strtotime($client['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($client['notes'])): ?>
                    <p><?= nl2br(esc($client['notes'])) ?></p>
                <?php else: ?>
                    <p class="text-muted">No notes available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-server"></i> Services</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Domain</th>
                                <th>Price</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= esc($service['product_name']) ?></td>
                                    <td><?= esc($service['domain']) ?></td>
                                    <td>Rp <?= number_format($service['price'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($service['due_date'])) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'active' => 'bg-success',
                                            'suspended' => 'bg-warning',
                                            'cancelled' => 'bg-danger',
                                            'pending' => 'bg-info'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$service['status']] ?? 'bg-secondary' ?>">
                                            <?= ucfirst($service['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/service/<?= $service['id'] ?>/edit" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No services found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Invoices</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invoices)): ?>
                                <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?= esc($invoice['invoice_number']) ?></td>
                                    <td>Rp <?= number_format($invoice['amount'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($invoice['due_date'])) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'paid' => 'bg-success',
                                            'unpaid' => 'bg-warning',
                                            'past_due' => 'bg-danger',
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
                                    <td colspan="4" class="text-center">No invoices found</td>
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
