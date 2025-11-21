<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="container-fluid vh-100">
    <div class="row h-100">
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="text-center text-white">
                <i class="fas fa-users fa-5x mb-4"></i>
                <h2>Welcome Back!</h2>
                <p class="lead">Sign in to access your dashboard</p>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="card shadow-lg border-0" style="width: 100%; max-width: 400px;">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Login</h4>
                </div>
                <div class="card-body p-4">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?= form_open('/auth/login') ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= old('username') ?>" required>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </button>
                        </div>
                    <?= form_close() ?>

                    <div class="text-center">
                        <p class="mb-2">
                            <a href="/auth/forgot-password" class="text-primary text-decoration-none">
                                <i class="fas fa-key"></i> Forgot Password?
                            </a>
                        </p>
                        <p class="mb-0">Don't have an account? 
                            <a href="/auth/register" class="text-primary text-decoration-none">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    var passwordField = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
<?= $this->endSection() ?>