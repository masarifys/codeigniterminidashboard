<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">List all products &amp; services</h1>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="/client/services">
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
                <label class="form-label">Search domain name</label>
                <input type="text" class="form-control" name="search" placeholder="Search...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="suspended">Suspended</option>
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

<!-- Services Table -->
<div class="card shadow">
    <div class="card-body">
        <?php if (count($services) > 0): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Product or Services
                            <i class="fas fa-sort text-muted"></i>
                        </th>
                        <th>Price</th>
                        <th>Registration Date</th>
                        <th>
                            Due Date
                            <i class="fas fa-sort text-muted"></i>
                        </th>
                        <th>IP</th>
                        <th>
                            Status
                            <i class="fas fa-sort text-muted"></i>
                        </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td>
                            <strong><?= esc($service['product_name']) ?></strong><br>
                            <small class="text-muted"><?= esc($service['domain']) ?></small>
                        </td>
                        <td>
                            <strong>Rp <?= number_format($service['price'], 2, ',', '.') ?></strong><br>
                            <small class="text-muted"><?= ucfirst($service['billing_cycle']) ?></small>
                        </td>
                        <td><?= date('Y-m-d', strtotime($service['registration_date'])) ?></td>
                        <td><?= date('Y-m-d', strtotime($service['due_date'])) ?></td>
                        <td><?= $service['ip_address'] ? esc($service['ip_address']) : '-' ?></td>
                        <td>
                            <span class="badge badge-<?= $service['status'] ?>">
                                <?= ucfirst($service['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-manage">
                                Manage <i class="fas fa-arrow-right"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="showCancelled">
                <label class="form-check-label" for="showCancelled">
                    Show cancelled service/domain
                </label>
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
            <i class="fas fa-box-open"></i>
            <h5>No Services Yet</h5>
            <p>You don't have any services at the moment.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
