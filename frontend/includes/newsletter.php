<section class="newsletter">
    <div class="container">
        <h2 class="newsletter-title">Get access to exclusive updates</h2>
        <form class="newsletter-form" id="newsletter-form">
            <input 
                type="email" 
                class="newsletter-input" 
                placeholder="you@gmail.com"
                required
                name="email"
                id="newsletter-email"
            >
            <button type="submit" class="newsletter-button">
                subscribe to the newsletter
            </button>
        </form>
        <div id="newsletter-message" style="margin-top: 1rem; display: none;"></div>
    </div>
</section>

<script>
document.getElementById('newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email     = document.getElementById('newsletter-email').value.trim();
    const messageEl = document.getElementById('newsletter-message');

    if (!email) return;

    const fd = new FormData();
    fd.append('email', email);

    fetch('/smart-recipes/backend/api/newsletter_subscribe.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            messageEl.textContent  = d.message || (d.status === 'success' ? 'Thank you for subscribing!' : 'Something went wrong.');
            messageEl.style.color  = d.status === 'success' ? '#10B981' : '#EF4444';
            messageEl.style.display = 'block';
            if (d.status === 'success') this.reset();
            setTimeout(() => { messageEl.style.display = 'none'; }, 4000);
        })
        .catch(() => {
            messageEl.textContent  = 'Could not connect to server.';
            messageEl.style.color  = '#EF4444';
            messageEl.style.display = 'block';
        });
});
</script>
