/**
 * bookmarks.js
 * Handles bookmark toggle on recipe_detail page.
 * Expects window.bookmarkState = { recipeId, isBookmarked, isLoggedIn }
 * injected by recipe_detail.php
 */
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-bookmark');
    if (!btn) return;

    const state = window.bookmarkState || {};
    let isBookmarked = state.isBookmarked === true;

    // Reflect initial state
    updateBookmarkUI(btn, isBookmarked);

    btn.addEventListener('click', function () {
        if (!state.isLoggedIn) {
            if (typeof window.showAuthModal === 'function') window.showAuthModal();
            return;
        }

        const fd = new FormData();
        fd.append('recipe_id', state.recipeId);

        fetch('/smart-recipes/backend/api/toggle_bookmark.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(d => {
                if (d.status === 'success') {
                    isBookmarked = d.bookmarked;
                    updateBookmarkUI(btn, isBookmarked);
                } else {
                    alert(d.message || 'Lỗi!');
                }
            })
            .catch(() => alert('Không thể kết nối server!'));
    });

    function updateBookmarkUI(btn, saved) {
        const path = btn.querySelector('path');
        if (path) {
            path.style.fill   = saved ? '#FCD34D' : 'none';
            path.style.stroke = saved ? '#F59E0B' : 'currentColor';
        }
        btn.setAttribute('title', saved ? 'Remove bookmark' : 'Bookmark');
        btn.setAttribute('data-saved', saved ? 'true' : 'false');
    }
});
