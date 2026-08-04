(() => {
    const ROUTES = {
        verifyEmail: '/smart-recipes/frontend/pages/auth/verify_email.php',
        home: '/smart-recipes/frontend/pages/home.php'
    };

    function $(id) {
        return document.getElementById(id);
    }

    function getValue(id) {
        return $(id)?.value.trim() || '';
    }

    function showMessage(message) {
        alert(message);
    }

    function redirectTo(path, delay = 500) {
        setTimeout(() => {
            window.location.href = path;
        }, delay);
    }

    function saveSession(key, value) {
        sessionStorage.setItem(key, typeof value === 'string' ? value : JSON.stringify(value));
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // SIGN UP
    const signupForm = $('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                username: getValue('username'),
                email: getValue('email'),
                password: getValue('password'),
                confirm_password: getValue('confirm_password')
            };

            if (!formData.username || !formData.email || !formData.password || !formData.confirm_password) {
                showMessage('Please fill in all required fields.');
                return;
            }

            if (!validateEmail(formData.email)) {
                showMessage('Please enter a valid email address.');
                return;
            }

            if (formData.password.length < 6) {
                showMessage('Password must be at least 6 characters.');
                return;
            }

            if (formData.password !== formData.confirm_password) {
                showMessage('Passwords do not match!');
                return;
            }

            try {
                console.log('Signing up...', formData);

                // Placeholder for real API call
                // const response = await fetch(...)

                saveSession('verify_email', formData.email);
                redirectTo(ROUTES.verifyEmail);

            } catch (error) {
                console.error('Sign up error:', error);
                showMessage('An error occurred during sign up.');
            }
        });
    }

    // SIGN IN
    const signinForm = $('signin-form');
    if (signinForm) {
        signinForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = {
                email: getValue('email'),
                password: getValue('password'),
                remember: $('remember')?.checked || false
            };

            if (!formData.email || !formData.password) {
                showMessage('Please enter your email and password.');
                return;
            }

            if (!validateEmail(formData.email)) {
                showMessage('Please enter a valid email address.');
                return;
            }

            try {
                console.log('Signing in...', formData);

                // Placeholder for real API call
                // const response = await fetch(...)

                const user = {
                    id: 1,
                    name: formData.email.split('@')[0],
                    email: formData.email
                };

                saveSession('user', user);

                if (formData.remember) {
                    localStorage.setItem('remembered_email', formData.email);
                } else {
                    localStorage.removeItem('remembered_email');
                }

                redirectTo(ROUTES.home);

            } catch (error) {
                console.error('Sign in error:', error);
                showMessage('Invalid email or password.');
            }
        });
    }

    // PASSWORD TOGGLE
    window.togglePassword = function (fieldId) {
        const field = $(fieldId);
        if (!field) return;

        field.type = field.type === 'password' ? 'text' : 'password';
    };

    // GOOGLE SIGN IN PLACEHOLDER
    window.signInWithGoogle = function () {
        showMessage('Google Sign In - To be implemented');
    };

    // AUTO-FILL REMEMBERED EMAIL
    document.addEventListener('DOMContentLoaded', () => {
        const rememberedEmail = localStorage.getItem('remembered_email');
        const emailField = $('email');

        if (rememberedEmail && emailField && $('signin-form')) {
            emailField.value = rememberedEmail;

            if ($('remember')) {
                $('remember').checked = true;
            }
        }
    });
})();