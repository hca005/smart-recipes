// Helper for dynamic app path prefix
function getAppPrefix() {
    return window.location.pathname.startsWith('/smart-recipes') ? '/smart-recipes' : '';
}

// Check if user is authenticated
function isAuthenticated() {
    const token = localStorage.getItem('user_token');
    const user = localStorage.getItem('current_user');
    return token && user;
}

// Get current user
function getCurrentUser() {
    const userStr = localStorage.getItem('current_user');
    return userStr ? JSON.parse(userStr) : null;
}

// Save authentication data
function saveAuth(token, user) {
    localStorage.setItem('user_token', token);
    localStorage.setItem('current_user', JSON.stringify(user));
}

// Clear authentication data
function clearAuth() {
    localStorage.removeItem('user_token');
    localStorage.removeItem('current_user');
}

// Login function
async function login(email, password) {
    try {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        const response = await fetch(getAppPrefix() + '/backend/api/login.php', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Login failed');
        }

        const user = {
            username: data.username,
            email,
            role: data.role || 'user',
        };

        saveAuth('php-session', user);

        return { success: true, user };
    } catch (error) {
        return { success: false, error: error.message };
    }
}
// Register function
async function register(userData) {
    try {
        const formData = new FormData();
        Object.entries(userData).forEach(([key, value]) => {
            formData.append(key, value);
        });

        const response = await fetch(getAppPrefix() + '/backend/api/register.php', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();

        if (data.status !== 'success') {
            throw new Error(data.message || 'Registration failed');
        }

        return {
            success: true,
            user: {
                username: userData.username,
                email: userData.email,
                role: 'user',
            },
        };
    } catch (error) {
        return { success: false, error: error.message };
    }
}
// Logout function
async function logout() {
    try {
        await fetch(getAppPrefix() + '/backend/api/logout.php', {
            method: 'POST',
        });
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        clearAuth();
        window.location.href = getAppPrefix() + '/frontend/pages/home.php';
    }
}

// Require authentication for a page
function requireAuth() {
    if (!isAuthenticated()) {
        // Save current URL to redirect back after login
        localStorage.setItem('redirect_after_login', window.location.pathname);
        window.location.href = getAppPrefix() + '/frontend/pages/auth/sign_in.php';
        return false;
    }
    return true;
}

// Redirect if authenticated (for login/register pages)
function redirectIfAuthenticated() {
    if (isAuthenticated()) {
        const redirect = localStorage.getItem('redirect_after_login') || (getAppPrefix() + '/frontend/pages/home.php');
        localStorage.removeItem('redirect_after_login');
        window.location.href = redirect;
        return true;
    }
    return false;
}

// Get user role
function getUserRole() {
    const user = getCurrentUser();
    return user?.role || 'guest';
}

// Check if user is admin
function isAdmin() {
    return getUserRole() === 'admin';
}

// Check if user owns a resource
function isOwner(resourceUserId) {
    const user = getCurrentUser();
    return user && user.id === resourceUserId;
}

// Format user display name
function getUserDisplayName() {
    const user = getCurrentUser();
    if (!user) return 'Guest';
    return user.display_name || user.username || user.email;
}

// Export auth functions
window.auth = {
    isAuthenticated,
    getCurrentUser,
    saveAuth,
    clearAuth,
    login,
    register,
    logout,
    requireAuth,
    redirectIfAuthenticated,
    getUserRole,
    isAdmin,
    isOwner,
    getUserDisplayName,
};
