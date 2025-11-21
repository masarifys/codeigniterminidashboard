<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Website Monitoring</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="card shadow border-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Websites Up</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $upCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow border-danger">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Websites Down</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $downCount ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
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
                                <th>Domain</th>
                                <th>Monitor URL</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Last Check</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?= esc($service['domain']) ?></td>
                                    <td>
                                        <?php if (!empty($service['uptime_monitor_url'])): ?>
                                            <a href="<?= esc($service['uptime_monitor_url']) ?>" target="_blank">
                                                <?= esc($service['uptime_monitor_url']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($service['full_name']) ?></td>
                                    <td>
                                        <?php
                                        $uptimeStatus = $service['uptime_status'] ?? 'unknown';
                                        $statusClass = [
                                            'up' => 'bg-success',
                                            'down' => 'bg-danger',
                                            'unknown' => 'bg-secondary'
                                        ];
                                        ?>
                                        <span class="badge <?= $statusClass[$uptimeStatus] ?? 'bg-secondary' ?>">
                                            <?= strtoupper($uptimeStatus) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($service['last_uptime_check'])): ?>
                                            <?= date('M d, Y H:i', strtotime($service['last_uptime_check'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Never</span>
                                        <?php endif; ?>
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
                                    <td colspan="6" class="text-center">No monitoring configured</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Note:</strong> Configure uptime monitoring for each service by editing the service and adding an uptime monitor URL.
        </div>
    </div>
</div>
<?= $this->endSection() ?>
