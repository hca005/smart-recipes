<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand Section -->
            <div class="footer-section">
                <h3 class="font-logo" style="color: white;">Food<span style="color: var(--yellow-400);">.</span></h3>
                <p style="margin-top: 0.5rem;">Discover What to Cook Today?</p>
            </div>

            <!-- All Categories -->
            <div class="footer-section">
                <h4>All categories</h4>
                <ul class="footer-links">
                    <li><a href="/smart-recipes/frontend/pages/recipes/all_recipes.php">Recipes</a></li>
                    <li><a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?popular=true">Popular</a></li>
                    <li><a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?category=quick-easy">Quick & Easy</a></li>
                </ul>
            </div>

            <!-- Help -->
            <div class="footer-section">
                <h4>Help</h4>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Guidelines</a></li>
                </ul>
            </div>

            <!-- About Us -->
            <div class="footer-section">
                <h4>About Us</h4>
                <ul class="footer-links">
                    <li><a href="#">Our Story</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; 2026 Warner Bros. Discovery, Inc. or its subsidiaries and affiliates. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">terms and customers</a>
                <a href="#">privacy policy</a>
            </div>
        </div>
    </div>
</footer>

<!-- Login/Register Modal -->
<div id="auth-modal" class="modal-overlay" style="display: none;">
    <div class="modal" style="max-width: 400px; background: white; border-radius: 1rem; padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; text-align: center;">Welcome to Food.</h2>
        <p style="text-align: center; color: #6B7280; margin-bottom: 2rem;">Please sign in to use this feature</p>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="/smart-recipes/frontend/pages/auth/sign_in.php" class="btn btn-primary" style="text-align: center; text-decoration: none;">Sign In</a>
            <a href="/smart-recipes/frontend/pages/auth/sign_up.php" class="btn btn-outline" style="text-align: center; text-decoration: none;">Create Account</a>
            <button onclick="closeAuthModal()" style="background: none; border: none; color: #6B7280; cursor: pointer; padding: 0.5rem;">Maybe later</button>
        </div>
    </div>
</div>

<script>
function showAuthModal() {
    const modal = document.getElementById('auth-modal');
    if (!modal) return;

    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.position = 'fixed';
    modal.style.inset = '0';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    modal.style.backdropFilter = 'blur(4px)';
    modal.style.zIndex = '9999';

    document.body.classList.add('modal-open');
}

function closeAuthModal() {
    const modal = document.getElementById('auth-modal');
    if (!modal) return;

    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
}

document.getElementById('auth-modal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeAuthModal();
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeAuthModal();
    }
});

window.showAuthModal = showAuthModal;
window.closeAuthModal = closeAuthModal;
</script>

<!-- Scripts -->
<script src="/smart-recipes/frontend/assets/js/utils/helpers.js"></script>
<?php if (isset($additionalScripts)): ?>
    <?php foreach ($additionalScripts as $script): ?>
        <script src="<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
