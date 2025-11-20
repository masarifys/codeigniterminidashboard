<?= $this->extend('layouts/client_layout') ?>

<?= $this->section('content') ?>
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Live Support</h1>
        <p class="mb-0 text-muted">Chat with our support team in real-time</p>
    </div>
</div>

<!-- Live Support Card -->
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="text-center mb-4">
                    <i class="fas fa-comments fa-5x text-primary mb-3"></i>
                    <h4>Need Help?</h4>
                    <p class="text-muted">Our support team is here to help you 24/7</p>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Live chat support is available Monday to Friday, 9:00 AM - 5:00 PM
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg" onclick="alert('Live chat feature will be available soon!')">
                        <i class="fas fa-comment-dots"></i> Start Live Chat
                    </button>
                    
                    <a href="/client/tickets" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-ticket-alt"></i> Create Support Ticket
                    </a>
                </div>
                
                <div class="mt-4">
                    <h5>Other Ways to Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope text-primary"></i> Email: support@example.com</li>
                        <li class="mb-2"><i class="fas fa-phone text-primary"></i> Phone: +62 123 4567 890</li>
                        <li class="mb-2"><i class="fas fa-clock text-primary"></i> Response Time: Within 24 hours</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
