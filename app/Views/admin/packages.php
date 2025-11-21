<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Service Package Management</h1>
        <a href="/admin/package/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Package
        </a>
    </div>
</div>

<div class="row">
    <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $package): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-<?= $package['is_active'] ? 'primary' : 'secondary' ?> text-white">
                    <h5 class="mb-0"><?= esc($package['name']) ?></h5>
                </div>
                <div class="card-body">
                    <h3 class="text-primary">Rp <?= number_format($package['price'], 0, ',', '.') ?></h3>
                    <p class="text-muted">per <?= ucfirst($package['billing_cycle']) ?></p>
                    
                    <?php if (!empty($package['description'])): ?>
                        <p class="card-text"><?= esc($package['description']) ?></p>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <ul class="list-unstyled">
                        <?php if (!empty($package['storage'])): ?>
                            <li><i class="fas fa-check text-success"></i> Storage: <?= esc($package['storage']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($package['bandwidth'])): ?>
                            <li><i class="fas fa-check text-success"></i> Bandwidth: <?= esc($package['bandwidth']) ?></li>
                        <?php endif; ?>
                    </ul>
                    
                    <?php if (!empty($package['features'])): ?>
                        <hr>
                        <h6>Features:</h6>
                        <p class="small"><?= nl2br(esc($package['features'])) ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($package['notes'])): ?>
                        <hr>
                        <h6>Admin Notes:</h6>
                        <p class="small text-muted"><?= nl2br(esc($package['notes'])) ?></p>
                    <?php endif; ?>
                    
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge <?= $package['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $package['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex gap-2">
                        <a href="/admin/package/<?= $package['id'] ?>/edit" class="btn btn-sm btn-warning flex-grow-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="/admin/package/<?= $package['id'] ?>/delete" class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this package?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No service packages found. <a href="/admin/package/create">Create one now</a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
