/**
 * search_ingredients.js
 * Powers trang by_ingredients.php
 * Data được PHP inject qua: window.searchPageData = { recipes, query, isLoggedIn }
 */
document.addEventListener('DOMContentLoaded', function () {

    const allRecipes = window.searchPageData?.recipes    || [];
    const rawQuery   = window.searchPageData?.query      || '';

    const searchInput = document.getElementById('ingredient-search-input');
    const searchForm  = document.getElementById('ingredient-search-form');
    const resultsGrid = document.getElementById('search-results-grid');
    const resultCount = document.getElementById('result-count');
    const sortSelect  = document.getElementById('sort-select');
    const noResults   = document.getElementById('no-results');

    // ── Scoring ───────────────────────────────────────────────────────────────
    function parseIngredients(query) {
        return query.toLowerCase().split(/[\s,]+/).map(s => s.trim()).filter(Boolean);
    }

    function scoreRecipe(recipe, tokens) {
        const haystack = [
            recipe.title       || '',
            recipe.description || '',
            recipe.category    || '',
            ...(recipe.ingredients || []),
            ...(recipe.tags        || []),
        ].join(' ').toLowerCase();

        let matchedCount = 0;
        for (const token of tokens) if (haystack.includes(token)) matchedCount++;

        const coverage = matchedCount / tokens.length;
        const rating   = (parseFloat(recipe.rating) || 0) / 5;
        return { score: coverage * 0.75 + rating * 0.25, matchedCount };
    }

    // ── Render sao ────────────────────────────────────────────────────────────
    function renderStars(rating) {
        const val   = parseFloat(rating) || 0;
        const full  = Math.floor(val);
        const half  = val - full >= 0.5;
        const empty = 5 - full - (half ? 1 : 0);
        const star  = (fill, stroke) =>
            `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                  fill="${fill}" stroke="${stroke}" stroke-width="1.5"
                  style="display:inline-block;vertical-align:middle;">
               <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
             </svg>`;

        let html = '';
        for (let i = 0; i < full;  i++) html += star('#F59E0B', '#F59E0B');
        if (half)                         html += star('url(#hg)', '#F59E0B');
        for (let i = 0; i < empty; i++) html += star('none',    '#F59E0B');
        return html;
    }

    // ── Card HTML ─────────────────────────────────────────────────────────────
    function createResultCard(recipe) {
        const rating  = parseFloat(recipe.rating)  || 4;
        const time    = recipe.ready_in || recipe.time || '30 min';
        const author  = recipe.author  || recipe.user || 'Roddema';
        const image   = recipe.image   || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop';

        return `
        <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=${encodeURIComponent(recipe.id)}"
           class="si-card">
          <div class="si-card__img">
            <img src="${escapeHTML(image)}" alt="${escapeHTML(recipe.title)}" loading="lazy">
          </div>
          <div class="si-card__body">
            <h3 class="si-card__title">${escapeHTML(recipe.title)}</h3>
            <p class="si-card__author">By <span>${escapeHTML(author)}</span></p>
            <div class="si-card__meta">
              <div class="si-card__stars">${renderStars(rating)}</div>
              <div class="si-card__time">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
                <span>${escapeHTML(String(time))}</span>
              </div>
            </div>
          </div>
          <div class="si-card__divider"></div>
        </a>`;
    }

    function escapeHTML(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ── Render kết quả ────────────────────────────────────────────────────────
    function renderResults(query) {
        const tokens  = parseIngredients(query);
        let   recipes = allRecipes;

        if (tokens.length) {
            recipes = allRecipes
                .map(r => ({ recipe: r, ...scoreRecipe(r, tokens) }))
                .filter(r => r.matchedCount > 0)
                .sort((a, b) => b.score - a.score || b.matchedCount - a.matchedCount)
                .map(r => r.recipe);
        }

        // Sort override
        const sortVal = sortSelect?.value || 'relevance';
        if (sortVal === 'rating') {
            recipes = [...recipes].sort((a, b) =>
                (parseFloat(b.rating) || 0) - (parseFloat(a.rating) || 0));
        } else if (sortVal === 'time') {
            const toMin = v => parseInt(String(v || '999').replace(/\D/g, '')) || 999;
            recipes = [...recipes].sort((a, b) =>
                toMin(a.ready_in || a.time) - toMin(b.ready_in || b.time));
        } else if (sortVal === 'newest') {
            recipes = [...recipes].reverse();
        }

        if (resultCount) resultCount.textContent = recipes.length.toLocaleString();

        if (noResults)   noResults.style.display   = recipes.length ? 'none' : 'flex';
        if (resultsGrid) resultsGrid.style.display  = recipes.length ? 'grid' : 'none';
        if (resultsGrid) resultsGrid.innerHTML      = recipes.map(createResultCard).join('');
    }

    // ── Init ──────────────────────────────────────────────────────────────────
    if (searchInput) searchInput.value = rawQuery;
    renderResults(rawQuery);

    searchForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        const q = searchInput?.value.trim() || '';
        if (!q) return;
        const url = new URL(window.location.href);
        url.searchParams.set('ingredients', q);
        window.history.pushState({}, '', url.toString());
        renderResults(q);
    });

    sortSelect?.addEventListener('change', function () {
        renderResults(searchInput?.value.trim() || rawQuery);
    });
});