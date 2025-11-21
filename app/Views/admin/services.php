<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Service Management</h1>
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
                                <th>Client</th>
                                <th>Product</th>
                                <th>Domain</th>
                                <th>Price</th>
                                <th>Due Date</th>
                                <th>Domain Expiry</th>
                                <th>SSL Status</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= esc($service['full_name']) ?></td>
                                    <td><?= esc($service['product_name']) ?></td>
                                    <td><?= esc($service['domain']) ?></td>
                                    <td>Rp <?= number_format($service['price'], 0, ',', '.') ?></td>
                                    <td><?= date('M d, Y', strtotime($service['due_date'])) ?></td>
                                    <td>
                                        <?php if (!empty($service['domain_expiry_date'])): ?>
                                            <?= date('M d, Y', strtotime($service['domain_expiry_date'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $sslClass = [
                                            'active' => 'bg-success',
                                            'inactive' => 'bg-secondary',
                                            'expiring_soon' => 'bg-warning'
                                        ];
                                        ?>
                                        <span class="badge <?= $sslClass[$service['ssl_status']] ?? 'bg-secondary' ?>">
                                            <?= ucfirst(str_replace('_', ' ', $service['ssl_status'])) ?>
                                        </span>
                                    </td>
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
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No services found</td>
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
