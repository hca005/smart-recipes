<?php
require_once '../../includes/bootstrap.php';

$pageTitle = 'Verify Email - Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/auth.css'];

include '../../includes/head.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Lấy mã OTP từ input hidden (đã được JS gom lại)
    $otp_input = $_POST['otp'] ?? '';

    // 2. So sánh với OTP thực trong Session
    $expected_otp = (string)($_SESSION['otp'] ?? '');

    if ($otp_input === $expected_otp && !empty($expected_otp)) {
        
        if (isset($_SESSION['register_data'])) {
            $data = $_SESSION['register_data'];

            // 3. Chuẩn bị câu lệnh SQL (Khớp chính xác với bảng users của Linh)
            // Lưu ý: Các cột khác như display_name, role... sẽ nhận giá trị mặc định hoặc NULL
            $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sss", $data['username'], $data['email'], $data['password']);

                if ($stmt->execute()) {
                    // Xóa session sau khi lưu thành công
                    unset($_SESSION['otp']);
                    unset($_SESSION['register_data']);

                    // Hiển thị thông báo và chuyển hướng
                    echo "<script>
                            alert('Registration successful! Welcome " . $data['username'] . "');
                            window.location.href = 'sign_in.php';
                          </script>";
                    exit();
                } else {
                    // Lỗi thực thi (thường là do trùng Email hoặc Username)
                    if ($conn->errno == 1062) {
                        $error = "Email or username already exists!";
                    } else {
                        $error = "System error: " . $stmt->error;
                    }
                }
                $stmt->close();
            } else {
                $error = "SQL error: " . $conn->error;
            }
        } else {
            $error = "Session expired. Please register again!";
        }

    } else {
        $error = "Incorrect verification code! Please try again.";
    }
}
?>

<div class="auth-page">
    <div class="auth-container-center">
        <div class="auth-box-center">
            <div class="auth-logo">
                <h1>Food<span class="dot">.</span></h1>
            </div>
            
            <div class="verification-container" style="text-align: center;">
                <h2 class="auth-title-center">Check your email</h2>
                <p class="auth-subtitle">
                    The code has been sent to you. Please check the email<br>
                    you provided: <strong id="user-email"><?= isset($_SESSION['register_data']['email']) ? substr($_SESSION['register_data']['email'], 0, 4) . '*****@gmail.com' : 'your email' ?></strong>
                </p>

                <?php if (isset($_SESSION['otp_display'])): ?>
                <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px 16px;margin-bottom:1rem;font-size:0.85rem;color:#92400e;">
                    <strong>Dev mode:</strong> Your OTP is <strong><?= $_SESSION['otp_display'] ?></strong>
                </div>
                <?php unset($_SESSION['otp_display']); ?>
                <?php endif; ?>
            
                <form method="POST" id="otp-form">
                    <div class="otp-inputs">
                        <input type="text" class="otp-field" id="otp1" maxlength="1" required>
                        <input type="text" class="otp-field" id="otp2" maxlength="1" required>
                        <input type="text" class="otp-field" id="otp3" maxlength="1" required>
                        <input type="text" class="otp-field" id="otp4" maxlength="1" required>
                        <input type="text" class="otp-field" id="otp5" maxlength="1" required>
                        <input type="text" class="otp-field" id="otp6" maxlength="1" required>
                    </div>
                    
                    <p class="timer-text">OTP will be expired in <span id="timer">2:03</span></p>
                    <p class="resend-text">Don't receive the code? <a href="#" onclick="resendCode()">Resend</a></p>
            
                    <input type="hidden" name="otp" id="otp-hidden">
                    <button type="submit" class="btn btn-verify-submit">Submit</button>
                </form>
            
                <p class="auth-footer" style="margin-top: 1.5rem;">
                    Already have account? <a href="/smart-recipes/frontend/pages/auth/sign_in.php">Sign Here</a>
                </p>
            
                <button type="button" class="btn btn-google btn-block" style="margin-top: 1rem;">
                    <svg width="18" height="18" viewBox="0 0 18 18" style="margin-right: 8px;">
                        <path fill="#4285F4" d="M17.64 9.2c0-.63-.06-1.25-.16-1.84H9v3.49h4.84a4.14 4.14 0 0 1-1.8 2.71v2.26h2.91a8.78 8.78 0 0 0 2.69-6.62z"></path>
                        <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.91-2.26c-.8.54-1.83.86-3.05.86-2.34 0-4.33-1.58-5.04-3.71H.95v2.3A9 9 0 0 0 9 18z"></path>
                        <path fill="#FBBC05" d="M3.96 10.71a5.4 5.4 0 0 1 0-3.42V5l-3.01 2.3a9 9 0 0 0 0 7.4l3.01-2.29z"></path>
                        <path fill="#EA4335" d="M9 3.58c1.32 0 2.5.45 3.44 1.35L15 2.47A8.96 8.96 0 0 0 9 0 9 9 0 0 0 .95 5L3.96 7.29c.7-2.13 2.7-3.71 5.04-3.71z"></path>
                    </svg>
                    Sign Up With Google
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const inputs = document.querySelectorAll('.otp-field');

inputs.forEach((input, index) => {
    // 1. Tự động nhảy sang ô tiếp theo khi gõ xong 1 số
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    // 2. Nhấn Backspace để quay lại ô trước đó
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

// 3. Gom 5 số lại thành 1 chuỗi OTP để gửi lên server
document.getElementById('otp-form').addEventListener('submit', function(e) {
    const otp = Array.from(inputs).map(input => input.value).join('');
    
    if (otp.length < 6) {
        e.preventDefault();
        alert('Please enter all 6 OTP digits');
        return;
    }
    
    document.getElementById('otp-hidden').value = otp;
});

// 4. Bộ đếm ngược thời gian (Timer)
let timeLeft = 123; // 2:03 (tổng cộng 123 giây)
const timerElement = document.getElementById('timer');

const countdown = setInterval(() => {
    if(timeLeft > 0) {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
    } else {
        clearInterval(countdown);
        timerElement.textContent = "0:00";
        alert("OTP has expired!");
    }
}, 1000);

// 5. Hàm gửi lại mã
function resendCode() {
    alert('New OTP has been sent!');
    location.reload(); // Load lại trang để reset timer
}
</script>

<?php include '../../includes/footer.php'; ?>