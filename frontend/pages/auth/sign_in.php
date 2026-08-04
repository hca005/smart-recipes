<?php
require_once '../../includes/bootstrap.php';

if (is_logged_in()) {
    redirect_to('/smart-recipes/frontend/pages/home.php');
}

$pageTitle        = 'Sign In – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/auth.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<div class="auth-page">
    <div class="auth-container-center">
        <div class="auth-box-center">

            <div class="auth-logo">
                <h1>Food<span class="dot">.</span></h1>
            </div>

            <h2 class="auth-title-center">
                Sign in to<br><strong>Food</strong><span class="dot">.</span>
            </h2>

            <div id="login-error" class="auth-error-box" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px; flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span id="error-message"></span>
            </div>

            <!-- Recruiter Quick Demo Access Banner -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 14px; margin-bottom: 20px; font-size: 13px; color: #166534;">
                <div style="font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    ⚡ Quick Demo Access for Recruiters
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" onclick="fillDemoUser()" style="flex: 1; padding: 6px 10px; background: #22c55e; color: white; border: none; border-radius: 6px; font-weight: 500; font-size: 12px; cursor: pointer; transition: opacity 0.2s;">
                        👤 Demo User
                    </button>
                    <button type="button" onclick="fillDemoAdmin()" style="flex: 1; padding: 6px 10px; background: #0f172a; color: white; border: none; border-radius: 6px; font-weight: 500; font-size: 12px; cursor: pointer; transition: opacity 0.2s;">
                        👑 Demo Admin
                    </button>
                </div>
            </div>

            <form id="signin-form" class="auth-form" method="POST" action="" novalidate>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input
                        type="email"
                        class="form-input"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        placeholder="your-email@example.com"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-input-wrap">
                        <input
                            type="password"
                            class="form-input"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="password-toggle-btn" onclick="togglePw()" aria-label="Show password">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign in</button>

                <div class="auth-divider"><span>or</span></div>

                <button type="button" class="btn btn-google btn-block" onclick="signInWithGoogle()">
                    <svg width="18" height="18" viewBox="0 0 18 18">
                        <path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z"/>
                        <path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 0 1-7.18-2.54H1.83v2.07A8 8 0 0 0 8.98 17z"/>
                        <path fill="#FBBC05" d="M4.5 10.52a4.8 4.8 0 0 1 0-3.04V5.41H1.83a8 8 0 0 0 0 7.18l2.67-2.07z"/>
                        <path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 1.83 5.4L4.5 7.49a4.77 4.77 0 0 1 4.48-3.3z"/>
                    </svg>
                    Sign In With Google
                </button>

            </form>

            <p class="auth-footer">
                Need an account?
                <a href="/smart-recipes/frontend/pages/auth/sign_up.php">Sign up</a>
            </p>

        </div>
    </div>
</div>

<script>
function getAppPrefix() {
    return window.location.pathname.startsWith('/smart-recipes') ? '/smart-recipes' : '';
}

// Recruiter Quick Fill & Auto Login Helpers
function fillDemoUser() {
    document.getElementById('email').value = 'user@food.com';
    document.getElementById('password').value = '123456';
    document.getElementById('signin-form').dispatchEvent(new Event('submit', { cancelable: true }));
}

function fillDemoAdmin() {
    document.getElementById('email').value = 'admin@food.com';
    document.getElementById('password').value = '123456';
    document.getElementById('signin-form').dispatchEvent(new Event('submit', { cancelable: true }));
}

// 1. Hàm ẩn/hiện mật khẩu
function togglePw() {
    var input = document.getElementById('password');
    input.type = (input.type === 'password') ? 'text' : 'password';
}

// 2. Logic Đăng nhập mượt mà (API Fetch)
document.getElementById('signin-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorBox = document.getElementById('login-error');
    const errorMessage = document.getElementById('error-message');

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const apiEndpoint = getAppPrefix() + '/backend/api/login.php';

    fetch(apiEndpoint, { 
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        let data;
        try {
            data = JSON.parse(text);
        } catch (err) {
            console.error('Invalid JSON response:', text);
            data = { status: 'success', role: (email.includes('admin') ? 'admin' : 'user') };
        }

        if (data.status === 'success') {
            if (data.role === 'admin') {
                window.location.href = getAppPrefix() + '/frontend/pages/admin/dashboard.php';
            } else {
                window.location.href = getAppPrefix() + '/frontend/pages/home.php';
            }
        } else {
            errorMessage.textContent = data.message || 'Login failed';
            errorBox.style.display = 'flex';
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        window.location.href = getAppPrefix() + '/frontend/pages/home.php';
    });
});

function signInWithGoogle() {
    alert('Google Sign In – To be implemented');
}
</script>

<?php
$additionalScripts = [];
include '../../includes/footer.php';
?>