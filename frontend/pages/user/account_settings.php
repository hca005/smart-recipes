<?php
require_once '../../includes/bootstrap.php';
require_login();

$sessionUser = current_user();

// Lấy thông tin user đầy đủ từ DB
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $sessionUser['id']);
$stmt->execute();
$fullUser = $stmt->get_result()->fetch_assoc() ?? $sessionUser;

$notification_prefs = json_decode($fullUser['notification_prefs'] ?? '{}', true);
$privacy_prefs = json_decode($fullUser['privacy_prefs'] ?? '{}', true);

$tab = $_GET['tab'] ?? 'password';

$pageTitle = 'Account Settings – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/profile.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<style>
/* ── Account Settings Specific Styles ── */
.settings-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
    min-height: calc(100vh - 200px);
}

.settings-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #111;
}

.settings-header h1 {
    font-size: 1.75rem;
    font-weight: 800;
    color: #111;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.settings-header h1 i {
    color: #FCD34D;
}

.settings-layout {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 2rem;
    align-items: start;
}

/* Sidebar */
.settings-sidebar {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.25rem;
    position: sticky;
    top: 100px;
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.settings-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.75rem 1rem;
    color: #6B7280;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s;
}

.settings-nav-item i {
    width: 18px;
    text-align: center;
}

.settings-nav-item:hover {
    background: #f3f4f6;
    color: #111;
}

.settings-nav-item.active {
    background: #FCD34D;
    color: #000;
    font-weight: 600;
}

.settings-nav-item.danger {
    color: #DC2626;
}

.settings-nav-item.danger:hover {
    background: #FEE2E2;
}

/* Main Content */
.settings-main {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 2rem;
}

.settings-section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111;
    margin: 0 0 0.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.settings-section-title i {
    color: #FCD34D;
}

.settings-section-desc {
    font-size: 0.875rem;
    color: #6B7280;
    margin: 0 0 1.5rem;
    line-height: 1.5;
}

/* Form Styles */
.settings-form {
    max-width: 450px;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #FCD34D;
    box-shadow: 0 0 0 3px rgba(252, 211, 77, 0.2);
}

.form-group .input-hint {
    font-size: 0.8rem;
    color: #9CA3AF;
    margin-top: 0.35rem;
}

.form-group .input-with-icon {
    position: relative;
}

.form-group .input-with-icon input {
    padding-right: 40px;
}

.form-group .input-with-icon .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9CA3AF;
    cursor: pointer;
    padding: 0;
}

.form-group .input-with-icon .toggle-password:hover {
    color: #6B7280;
}

/* Buttons */
.btn-primary {
    background: #FCD34D;
    color: #000;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #F59E0B;
}

.btn-primary:disabled {
    background: #E5E7EB;
    color: #9CA3AF;
    cursor: not-allowed;
}

.btn-danger {
    background: #DC2626;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #B91C1C;
}

.btn-outline {
    background: #fff;
    color: #374151;
    border: 1px solid #D1D5DB;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-outline:hover {
    background: #F3F4F6;
}

/* Toggle Switch */
.toggle-group {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 0;
    border-bottom: 1px solid #F3F4F6;
}

.toggle-group:last-child {
    border-bottom: none;
}

.toggle-info h4 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #111;
    margin: 0 0 0.25rem;
}

.toggle-info p {
    font-size: 0.8rem;
    color: #6B7280;
    margin: 0;
}

.toggle-switch {
    position: relative;
    width: 48px;
    height: 26px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #D1D5DB;
    border-radius: 26px;
    transition: 0.3s;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: white;
    border-radius: 50%;
    transition: 0.3s;
}

.toggle-switch input:checked + .toggle-slider {
    background: #FCD34D;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(22px);
}

/* Alert Messages */
.alert {
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #D1FAE5;
    color: #065F46;
    border: 1px solid #A7F3D0;
}

.alert-error {
    background: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FECACA;
}

.alert-warning {
    background: #FEF3C7;
    color: #92400E;
    border: 1px solid #FDE68A;
}

/* Delete Account Section */
.danger-zone {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.danger-zone h3 {
    color: #DC2626;
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 0.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.danger-zone p {
    color: #7F1D1D;
    font-size: 0.875rem;
    margin: 0 0 1rem;
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 768px) {
    .settings-layout {
        grid-template-columns: 1fr;
    }
    
    .settings-sidebar {
        position: static;
    }
    
    .settings-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .settings-nav-item {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
}
</style>

<div class="profile-banner"></div>

<div class="settings-container">
    <div class="settings-header">
        <h1><i class="fas fa-cog"></i> Account Settings & Privacy</h1>
    </div>
    
    <div class="settings-layout">
        <!-- Sidebar Navigation -->
        <aside class="settings-sidebar">
            <nav class="settings-nav">
                <a href="?tab=password" class="settings-nav-item <?= $tab === 'password' ? 'active' : '' ?>">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="?tab=email" class="settings-nav-item <?= $tab === 'email' ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i> Email Settings
                </a>
                <a href="?tab=notifications" class="settings-nav-item <?= $tab === 'notifications' ? 'active' : '' ?>">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <a href="?tab=privacy" class="settings-nav-item <?= $tab === 'privacy' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i> Privacy
                </a>
                <a href="?tab=delete" class="settings-nav-item danger <?= $tab === 'delete' ? 'active' : '' ?>">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="settings-main">

            <?php if ($tab === 'password'): ?>
            <!-- Change Password Tab -->
            <h2 class="settings-section-title"><i class="fas fa-key"></i> Change Password</h2>
            <p class="settings-section-desc">
                Ensure your account is using a strong password to stay secure. 
                We recommend using a combination of letters, numbers, and symbols.
            </p>
            
            <div id="passwordAlert"></div>
            
            <form class="settings-form" id="changePasswordForm">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="current_password" name="current_password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                        <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="input-hint">Minimum 6 characters</p>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </form>

            <?php elseif ($tab === 'email'): ?>
            <!-- Email Settings Tab -->
            <h2 class="settings-section-title"><i class="fas fa-at"></i> Email Settings</h2>
            <p class="settings-section-desc">
                Manage your email address and email preferences. Your email is used for account recovery and notifications.
            </p>
            
            <div id="emailAlert"></div>
            
            <form class="settings-form" id="changeEmailForm">
                <div class="form-group">
                    <label for="current_email">Current Email</label>
                    <input type="email" id="current_email" value="<?= htmlspecialchars($fullUser['email']) ?>" disabled 
                           style="background: #F3F4F6; cursor: not-allowed;">
                </div>
                
                <div class="form-group">
                    <label for="new_email">New Email Address</label>
                    <input type="email" id="new_email" name="new_email" placeholder="Enter new email address">
                </div>
                
                <div class="form-group">
                    <label for="email_password">Confirm with Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="email_password" name="password" placeholder="Enter your password">
                        <button type="button" class="toggle-password" onclick="togglePassword('email_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-envelope"></i> Update Email
                </button>
            </form>

            <?php elseif ($tab === 'notifications'): ?>
            <!-- Notifications Tab -->
            <h2 class="settings-section-title"><i class="fas fa-bell"></i> Notification Preferences</h2>
            <p class="settings-section-desc">
                Choose what notifications you want to receive. You can change these settings at any time.
            </p>
            
            <div id="notifAlert"></div>
            
            <form id="notificationForm">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Email Notifications</h4>
                        <p>Receive email updates about your account activity</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="email_notifications" <?= ($notification_prefs['email_notifications'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Comment Notifications</h4>
                        <p>Get notified when someone comments on your recipes</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="comment_notifications" <?= ($notification_prefs['comment_notifications'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Recipe Likes</h4>
                        <p>Get notified when someone likes your recipes</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="like_notifications" <?= ($notification_prefs['like_notifications'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Newsletter</h4>
                        <p>Receive weekly recipe recommendations and tips</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="newsletter" <?= ($notification_prefs['newsletter'] ?? false) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Preferences
                    </button>
                </div>
            </form>

            <?php elseif ($tab === 'privacy'): ?>
            <!-- Privacy Tab -->
            <h2 class="settings-section-title"><i class="fas fa-shield-alt"></i> Privacy Settings</h2>
            <p class="settings-section-desc">
                Control who can see your profile and activity. Your privacy is important to us.
            </p>
            
            <div id="privacyAlert"></div>
            
            <form id="privacyForm">
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Public Profile</h4>
                        <p>Allow anyone to view your profile and recipes</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="public_profile" <?= ($privacy_prefs['public_profile'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Show Activity Status</h4>
                        <p>Let others see when you're active on the platform</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="show_activity" <?= ($privacy_prefs['show_activity'] ?? false) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Show Bookmarks</h4>
                        <p>Allow others to see your bookmarked recipes</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="show_bookmarks" <?= ($privacy_prefs['show_bookmarks'] ?? false) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="toggle-group">
                    <div class="toggle-info">
                        <h4>Search Engine Indexing</h4>
                        <p>Allow search engines to index your profile</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="search_indexing" <?= ($privacy_prefs['search_indexing'] ?? true) ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Privacy Settings
                    </button>
                </div>
            </form>

            <?php elseif ($tab === 'delete'): ?>
            <!-- Delete Account Tab -->
            <h2 class="settings-section-title" style="color: #DC2626;">
                <i class="fas fa-exclamation-triangle"></i> Delete Account
            </h2>
            <p class="settings-section-desc">
                Once you delete your account, there is no going back. Please be certain.
            </p>
            
            <div class="danger-zone">
                <h3><i class="fas fa-skull-crossbones"></i> Danger Zone</h3>
                <p>
                    Deleting your account will permanently remove all your data including:
                </p>
                <ul style="margin: 0 0 1rem 1.5rem; color: #7F1D1D; font-size: 0.875rem; line-height: 1.8;">
                    <li>Your profile information</li>
                    <li>All recipes you've created</li>
                    <li>Your comments and ratings</li>
                    <li>Your bookmarks and saved items</li>
                    <li>Your followers and following list</li>
                </ul>
                <p><strong>This action cannot be undone.</strong></p>
                
                <button type="button" class="btn-danger" onclick="showDeleteConfirmModal()">
                    <i class="fas fa-trash-alt"></i> Delete My Account
                </button>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-content-box" style="max-width: 420px;">
        <h3 style="color: #DC2626; margin-bottom: 15px;">
            <i class="fas fa-exclamation-triangle"></i> Confirm Account Deletion
        </h3>
        <p style="color: #6B7280; font-size: 0.9rem; margin-bottom: 20px;">
            Please type <strong style="color: #DC2626;">DELETE</strong> to confirm you want to permanently delete your account.
        </p>
        <form id="deleteAccountForm">
            <div class="form-group">
                <input type="text" id="delete_confirm_text" placeholder="Type DELETE here" 
                       style="text-transform: uppercase; text-align: center; font-weight: bold;">
            </div>
            <div class="form-group">
                <label>Enter your password to confirm</label>
                <input type="password" id="delete_password" name="password" required>
            </div>
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash-alt"></i> Delete Forever
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.parentElement.querySelector('.toggle-password i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Show alert message
function showAlert(containerId, type, message) {
    const container = document.getElementById(containerId);
    container.innerHTML = `
        <div class="alert alert-${type}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'exclamation-circle'}"></i>
            ${message}
        </div>
    `;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

// Change Password Form
document.getElementById('changePasswordForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validate passwords match
    if (formData.get('new_password') !== formData.get('confirm_password')) {
        showAlert('passwordAlert', 'error', 'New passwords do not match!');
        return;
    }
    
    try {
        const response = await fetch('/smart-recipes/backend/api/change_password.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert('passwordAlert', 'success', result.message);
            this.reset();
        } else {
            showAlert('passwordAlert', 'error', result.message);
        }
    } catch (error) {
        showAlert('passwordAlert', 'error', 'An error occurred. Please try again.');
    }
});

// Change Email Form
document.getElementById('changeEmailForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    if (!formData.get('new_email')) {
        showAlert('emailAlert', 'error', 'Please enter a new email address!');
        return;
    }
    
    try {
        const response = await fetch('/smart-recipes/backend/api/change_email.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert('emailAlert', 'success', result.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showAlert('emailAlert', 'error', result.message);
        }
    } catch (error) {
        showAlert('emailAlert', 'error', 'An error occurred. Please try again.');
    }
});

// Notification Form
document.getElementById('notificationForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('type', 'notifications');
    try {
        const response = await fetch('/smart-recipes/backend/api/update_user_preferences.php', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') {
            showAlert('notifAlert', 'success', result.message);
        } else {
            showAlert('notifAlert', 'error', result.message);
        }
    } catch (err) {
        showAlert('notifAlert', 'error', 'Network error.');
    }
});

// Privacy Form
document.getElementById('privacyForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('type', 'privacy');
    try {
        const response = await fetch('/smart-recipes/backend/api/update_user_preferences.php', { method: 'POST', body: formData });
        const result = await response.json();
        if (result.status === 'success') {
            showAlert('privacyAlert', 'success', result.message);
        } else {
            showAlert('privacyAlert', 'error', result.message);
        }
    } catch (err) {
        showAlert('privacyAlert', 'error', 'Network error.');
    }
});

// Delete Account Modal
function showDeleteConfirmModal() {
    document.getElementById('deleteConfirmModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.remove('show');
    document.getElementById('delete_confirm_text').value = '';
    document.getElementById('delete_password').value = '';
    document.getElementById('confirmDeleteBtn').disabled = true;
}

// Enable delete button only when "DELETE" is typed
document.getElementById('delete_confirm_text')?.addEventListener('input', function() {
    const btn = document.getElementById('confirmDeleteBtn');
    btn.disabled = this.value.toUpperCase() !== 'DELETE';
});

// Delete Account Form
document.getElementById('deleteAccountForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const confirmText = document.getElementById('delete_confirm_text').value.toUpperCase();
    if (confirmText !== 'DELETE') {
        alert('Please type DELETE to confirm.');
        return;
    }
    
    const password = document.getElementById('delete_password').value;
    if (!password) {
        alert('Please enter your password.');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('password', password);
        
        const response = await fetch('/smart-recipes/backend/api/delete_own_account.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('Your account has been deleted. Goodbye!');
            window.location.href = '/smart-recipes/frontend/pages/home.php';
        } else {
            alert(result.message || 'Failed to delete account.');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});

// Close modal when clicking outside
document.getElementById('deleteConfirmModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
