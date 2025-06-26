<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - RoyalGuest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .signup-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="signup-card p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-crown fa-3x text-primary mb-3"></i>
                        <h2 class="mb-1">RoyalGuest</h2>
                        <p class="text-muted">Create your account</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('signup') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user me-2"></i>Username
                            </label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('email') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag me-2"></i>Role
                            </label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="staff" <?= old('role') == 'staff' ? 'selected' : '' ?>>Staff</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('role')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('role') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Confirm Password
                            </label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('confirm_password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-user-plus me-2"></i>Sign Up
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="<?= base_url('login') ?>" class="text-decoration-none">Sign In</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>