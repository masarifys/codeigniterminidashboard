<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Client Management</h1>
        <a href="/admin/client/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </a>
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
                                <th>Business Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clients)): ?>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= esc($client['business_name']) ?></td>
                                    <td><?= esc($client['contact_person'] ?? '-') ?></td>
                                    <td><?= esc($client['contact_email'] ?? '-') ?></td>
                                    <td><?= esc($client['domain'] ?? '-') ?></td>
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
                                    <td><?= esc($client['full_name']) ?></td>
                                    <td>
                                        <a href="/admin/client/<?= $client['id'] ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/client/<?= $client['id'] ?>/edit" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/client/<?= $client['id'] ?>/delete" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this client?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No clients found</td>
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
