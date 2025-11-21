<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Upgrade Service</h1>
        <p class="text-muted">Current: <?= esc($service['product_name']) ?></p>
    </div>
</div>

<div class="row">
    <?php if (!empty($packages)): ?>
        <?php foreach ($packages as $package): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
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
                        <div class="small"><?= nl2br(esc($package['features'])) ?></div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light">
                    <a href="/client/support" class="btn btn-primary w-100">
                        <i class="fas fa-arrow-up"></i> Request Upgrade
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No upgrade packages available at this time. Please contact support for custom upgrades.
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row mt-4">
    <div class="col-12">
        <a href="/client/service/<?= $service['id'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Service Details
        </a>
    </div>
</div>
<?= $this->endSection() ?>
