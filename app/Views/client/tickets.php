<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">Trouble Tickets</h1>
            <p class="mb-0 text-muted">View and manage your support tickets</p>
        </div>
        <button class="btn btn-primary" onclick="alert('Create ticket feature will be available soon!')">
            <i class="fas fa-plus"></i> New Ticket
        </button>
    </div>
</div>

<!-- Tickets Table -->
<div class="card shadow">
    <div class="card-body">
        <?php if (count($tickets) > 0): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Department</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><strong>#<?= $ticket['id'] ?></strong></td>
                        <td><?= esc($ticket['subject']) ?></td>
                        <td><?= esc($ticket['department']) ?></td>
                        <td>
                            <?php
                            $priorityClass = [
                                'low' => 'secondary',
                                'medium' => 'warning',
                                'high' => 'danger'
                            ];
                            ?>
                            <span class="badge bg-<?= $priorityClass[$ticket['priority']] ?>">
                                <?= ucfirst($ticket['priority']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= $ticket['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($ticket['created_at'])) ?></td>
                        <td>
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
                Showing <?= count($tickets) ?> entries
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
            <i class="fas fa-ticket-alt"></i>
            <h5>No Tickets Yet</h5>
            <p>You don't have any support tickets at the moment.</p>
            <button class="btn btn-primary mt-3" onclick="alert('Create ticket feature will be available soon!')">
                <i class="fas fa-plus"></i> Create Your First Ticket
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
