<?php
require_once '../../includes/bootstrap.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password) {
        $error = "Nhập đầy đủ thông tin";
    } elseif ($password !== $confirm) {
        $error = "Mật khẩu không khớp";
    } else {

        $otp = rand(100000, 999999);

        $_SESSION['otp'] = $otp;
        $_SESSION['register_data'] = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        // In production: gửi OTP qua email thật
        // Hiện tại lưu vào session, user sẽ thấy OTP qua alert ở trang verify
        $_SESSION['otp_display'] = $otp; // Tạm thời để hiển thị cho dev

        header("Location: verify_email.php");
        exit();
    }
}

$pageTitle = 'Sign Up - Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/auth.css'];

include '../../includes/head.php';
?>

<div class="auth-page">
    <div class="auth-container-split" style="display:flex;">

        <div class="auth-left">
            <div class="auth-form-wrapper">
                <div class="auth-logo">
                    <h1>Food<span class="dot">.</span></h1>
                </div>
                
                <h2 class="auth-title">Create Your Account</h2>
                <p class="auth-subtitle">Join our culinary community and share your delicious creations!</p>

                <?php if ($error): ?>
                    <div style="color:red; margin-bottom:10px;">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-input" name="username" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" name="email" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-input-wrap">
                        <input type="password" class="form-input" name="password" id="password" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                            <i class="fa-regular fa-eye" id="eye-password"></i>
                        </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-input-wrap">
                        <input type="password" class="form-input" name="confirm_password" id="confirm_password" required>
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('confirm_password')">
                            <i class="fa-regular fa-eye" id="eye-confirm_password"></i>
                        </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        CREATE ACCOUNT
                    </button>

                </form>

                <p class="auth-footer">
                    Already have an account?
                    <a href="/smart-recipes/frontend/pages/auth/sign_in.php">Log in</a>
                </p>
            </div>
        </div>

        <div class="auth-right">
            <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800&h=1000&fit=crop">
        </div>

    </div>
</div>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById('eye-' + inputId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        // Đổi icon sang con mắt có gạch chéo
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        // Đổi icon về con mắt bình thường
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>
<?php include '../../includes/footer.php'; ?>