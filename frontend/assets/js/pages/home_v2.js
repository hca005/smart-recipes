/**
 * home.js  –  Homepage logic
 * Includes: trending / fan-favourites / craving / dont-miss renderers
 *           + hero ingredient-search with live dropdown
 */
document.addEventListener('DOMContentLoaded', function () {

    // ── Section data ─────────────────────────────────────────────────────────
    const trendingRecipes     = (window.homepageData?.trendingRecipes || []).slice(0, 4).map(mapRecipeForHomeCard);
    const fanFavorites        = (window.homepageData?.fanFavorites || []).slice(0, 4).map(mapRecipeForHomeCard);
    const cravingCollections  = (window.homepageData?.cravingCollections || []).slice(0, 3);
    const dontMissCollections = (window.homepageData?.dontMissCollections || []).slice(0, 5);
    const allRecipes          = window.homepageData?.allRecipes || [];

    loadRecipes('trending-recipes', trendingRecipes);
    loadCollections('craving-collections', cravingCollections);
    loadDontMissCollections('dont-miss-collections', dontMissCollections);
    loadRecipes('fan-favorites', fanFavorites);

    // ── Hero search ──────────────────────────────────────────────────────────
    const heroSearchForm  = document.getElementById('hero-search-form');
    const heroSearchInput = document.getElementById('hero-search-input');

    if (!heroSearchForm || !heroSearchInput) return;

    const isLoggedIn = heroSearchInput.dataset.loggedIn === 'true';

    // ---- Auth guard cho guest ----
    if (!isLoggedIn) {
        ['focus', 'click'].forEach(evt => {
            heroSearchInput.addEventListener(evt, function () {
                if (typeof window.showAuthModal === 'function') {
                    window.showAuthModal();
                }
            });
        });

        heroSearchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (typeof window.showAuthModal === 'function') {
                window.showAuthModal();
            }
        });

        return; // Không hiển thị dropdown cho guest
    }

    // ---- Dropdown setup (chỉ cho user đã đăng nhập) ----
   const dropdown = document.createElement('div');
dropdown.id = 'hero-search-dropdown';
dropdown.className = 'hero-dropdown';
heroSearchForm.appendChild(dropdown);

    let debounceTimer = null;

    heroSearchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => renderDropdown(heroSearchInput.value.trim()), 180);
    });

    heroSearchInput.addEventListener('focus', function () {
    const q = heroSearchInput.value.trim();
    if (q) renderDropdown(q);
});

    heroSearchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDropdown();
    });

   document.addEventListener('click', function (e) {
    if (!heroSearchForm.contains(e.target)) closeDropdown();
});

    heroSearchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const q = heroSearchInput.value.trim();
        if (!q) { heroSearchInput.focus(); return; }
        navigateToResults(q);
    });

    // ── Thuật toán scoring nguyên liệu ───────────────────────────────────────
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

        let matched = 0;
        for (const t of tokens) if (haystack.includes(t)) matched++;

        const coverage = matched / tokens.length;
        const rating   = (parseFloat(recipe.rating) || 0) / 5;
        return { score: coverage * 0.75 + rating * 0.25, matched };
    }

    function getSuggestedRecipes(query, limit = 5) {
        const tokens = parseIngredients(query);
        if (!tokens.length) return [];

        return allRecipes
            .map(r => ({ ...r, _s: scoreRecipe(r, tokens) }))
            .filter(r => r._s.matched > 0)
            .sort((a, b) => b._s.score - a._s.score)
            .slice(0, limit);
    }

    // ── Render dropdown ───────────────────────────────────────────────────────
    function renderDropdown(query) {
        if (!query) { closeDropdown(); return; }

        const results    = getSuggestedRecipes(query, 5);
        const encQ       = encodeURIComponent(query);
        const seeMoreUrl = `/smart-recipes/frontend/pages/search/by_ingredients.php?ingredients=${encQ}`;

        let html = '';

        if (results.length === 0) {
            html = `<div class="hero-dropdown-empty">No recipes found for "<strong>${escapeHTML(query)}</strong>"</div>`;
        } else {
            html += '<ul class="hero-dropdown-list">';
            for (const recipe of results) {
                const time = recipe.ready_in || recipe.time || '';
                const img  = recipe.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=120&h=90&fit=crop';
                const url  = `/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=${encodeURIComponent(recipe.id)}`;
                html += `
                  <li>
                    <a href="${url}" class="hero-dropdown-item">
                      <img class="hero-dropdown-item__img" src="${img}" alt="${escapeHTML(recipe.title)}" loading="lazy">
                      <div class="hero-dropdown-item__info">
                        <p class="hero-dropdown-item__title">${escapeHTML(recipe.title)}</p>
                        <div class="hero-dropdown-item__meta">
                          ${time
                            ? `<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                 <circle cx="12" cy="12" r="10"/>
                                 <polyline points="12 6 12 12 16 14"/>
                               </svg>
                               <span>${escapeHTML(String(time))}</span>`
                            : ''}
                        </div>
                      </div>
                    </a>
                  </li>`;
            }
            html += '</ul>';
        }

        html += `
          <div class="hero-dropdown-footer">
            <a href="${seeMoreUrl}" class="hero-dropdown-see-more">
              See more
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                   fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </a>
          </div>`;

        dropdown.innerHTML = html;
        dropdown.classList.add('is-open');
    }

    function closeDropdown() {
        dropdown.classList.remove('is-open');
    }

    function navigateToResults(query) {
        window.location.href =
            `/smart-recipes/frontend/pages/search/by_ingredients.php?ingredients=${encodeURIComponent(query)}`;
    }

    function escapeHTML(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
});

// ── Section helpers ───────────────────────────────────────────────────────────
function mapRecipeForHomeCard(recipe) {
    return { id: recipe.id, name: recipe.title, image: recipe.image, time: recipe.ready_in };
}

function createRecipeCard(recipe) {
    return `<a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=${recipe.id}" class="card" style="text-decoration:none;color:inherit;">
              <div class="card-image"><img src="${recipe.image}" alt="${recipe.name}" loading="lazy"></div>
              <div class="card-body">
                <h3 class="card-title">${recipe.name}</h3>
                <div class="card-meta">
                  <div class="card-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                      <circle cx="12" cy="12" r="10"/>
                      <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>${recipe.time}</span>
                  </div>
                </div>
              </div>
            </a>`;
}

function createCollectionCard(collection) {
    return `<a href="/smart-recipes/frontend/pages/recipes/collection.php?id=${collection.id}"
               class="collection-card" style="text-decoration:none;color:inherit;">
              <img src="${collection.image}" alt="${collection.title}" loading="lazy">
              <div class="collection-card-content">
                <p class="collection-card-category">${collection.category}</p>
                <h3 class="collection-card-title">${collection.title}</h3>
              </div>
            </a>`;
}

function createDontMissCard(collection) {
    return `<a href="/smart-recipes/frontend/pages/recipes/collection.php?id=${collection.id}"
               class="dont-miss-card"
               style="text-decoration:none;color:inherit;display:flex;flex-direction:column;
                      align-items:center;justify-content:flex-start;width:100%;max-width:190px;
                      text-align:center;transition:transform 0.25s ease;">
              <div class="dont-miss-card-image"
                   style="width:170px;height:170px;min-width:170px;min-height:170px;max-width:170px;
                          max-height:170px;margin:0 auto 14px auto;border-radius:9999px;overflow:hidden;
                          background:#f3f4f6;display:block;transition:transform 0.25s ease,box-shadow 0.25s ease;">
                <img src="${collection.image}" alt="${collection.title}" loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;border-radius:9999px;">
              </div>
              <h3 class="dont-miss-card-title"
                  style="font-size:13px;font-weight:600;line-height:1.35;text-transform:uppercase;
                         color:#1f2937;margin:0;max-width:140px;">
                ${collection.title}
              </h3>
            </a>`;
}

function loadRecipes(containerId, recipes) {
    const c = document.getElementById(containerId);
    if (c) c.innerHTML = recipes.map(createRecipeCard).join('');
}

function loadCollections(containerId, collections) {
    const c = document.getElementById(containerId);
    if (c) c.innerHTML = collections.map(createCollectionCard).join('');
}

function loadDontMissCollections(containerId, collections) {
    const c = document.getElementById(containerId);
    if (!c) return;
    c.style.display              = 'flex';
    c.style.justifyContent       = 'center';
    c.style.gap                  = '2.5rem';
    c.style.flexWrap             = 'wrap';
    c.innerHTML = collections.map(createDontMissCard).join('');

    c.querySelectorAll('.dont-miss-card').forEach(card => {
        const img = card.querySelector('.dont-miss-card-image');
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-6px)';
            if (img) { img.style.transform = 'scale(1.04)'; img.style.boxShadow = '0 10px 24px rgba(0,0,0,0.16)'; }
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            if (img) { img.style.transform = 'scale(1)'; img.style.boxShadow = 'none'; }
        });
    });
}