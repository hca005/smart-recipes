document.addEventListener('DOMContentLoaded', function () {
    // ══════════════════════════════════════════════════════════════
    // USER DROPDOWN MENU
    // ══════════════════════════════════════════════════════════════
    const userMenu = document.getElementById('userMenu');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenu && userMenuBtn && userDropdown) {
        function openDropdown() {
            const notifDropdown = document.getElementById('notifDropdown');
            if (notifDropdown) notifDropdown.style.display = 'none';

            userDropdown.classList.add('is-open');
            userMenuBtn.setAttribute('aria-expanded', 'true');
        }

        function closeDropdown() {
            userDropdown.classList.remove('is-open');
            userMenuBtn.setAttribute('aria-expanded', 'false');
        }

        function toggleDropdown() {
            const isOpen = userDropdown.classList.contains('is-open');
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        userMenuBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleDropdown();
        });

        userDropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        document.addEventListener('click', function (e) {
            if (!userMenu.contains(e.target)) {
                closeDropdown();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDropdown();
            }
        });
    }

    // ══════════════════════════════════════════════════════════════
    // NOTIFICATION MENU
    // ══════════════════════════════════════════════════════════════
    const notifMenu = document.getElementById('notifMenu');
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifBadge = document.getElementById('notifBadge');
    const notifList = document.getElementById('notifList');

    if (notifMenu && notifBtn && notifDropdown) {
        let unreadCount = 0;

        function fetchNotifications() {
            fetch('/smart-recipes/backend/api/get_notifications.php')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        unreadCount = data.unread_count || 0;
                        updateBadge();
                        renderNotifications(data.notifications || []);
                    }
                })
                .catch(err => console.error('Failed to fetch notifications:', err));
        }

        function updateBadge() {
            if (unreadCount > 0) {
                notifBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                notifBadge.style.display = 'block';
            } else {
                notifBadge.style.display = 'none';
            }
        }

        function renderNotifications(notifications) {
            if (!notifList) return;
            if (notifications.length === 0) {
                notifList.innerHTML = '<div style="padding:1.5rem; text-align:center; color:#6B7280; font-size:0.875rem;">No new notifications</div>';
                return;
            }
            
            let html = '';
            notifications.forEach(notif => {
                const isUnread = notif.is_read == 0;
                const bg = isUnread ? '#EFF6FF' : '#fff';
                const timeStr = new Date(notif.created_at).toLocaleString();
                
                const contentHtml = `
                    <div style="font-size:0.875rem; color:#111; margin-bottom:4px; font-weight:${isUnread ? '600' : '400'};">${notif.message}</div>
                    <div style="font-size:0.75rem; color:#6B7280;">${timeStr}</div>
                `;
                
                if (notif.link) {
                    html += `
                        <a href="${notif.link}" style="display:block; padding:1rem; border-bottom:1px solid #E5E7EB; background:${bg}; text-decoration:none;">
                            ${contentHtml}
                        </a>
                    `;
                } else {
                    html += `
                        <div style="padding:1rem; border-bottom:1px solid #E5E7EB; background:${bg};">
                            ${contentHtml}
                        </div>
                    `;
                }
            });
            notifList.innerHTML = html;
        }

        function markAsRead() {
            if (unreadCount === 0) return;
            fetch('/smart-recipes/backend/api/mark_notifications_read.php', { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        unreadCount = 0;
                        updateBadge();
                    }
                })
                .catch(err => console.error('Error marking read:', err));
        }

        notifBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isClosed = notifDropdown.style.display === 'none';
            if (isClosed) {
                // close user menu if open
                const userDropdown = document.getElementById('userDropdown');
                if (userDropdown) userDropdown.classList.remove('is-open');

                notifDropdown.style.display = 'block';
                markAsRead();
            } else {
                notifDropdown.style.display = 'none';
            }
        });

        notifDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        document.addEventListener('click', function(e) {
            if (!notifMenu.contains(e.target)) {
                notifDropdown.style.display = 'none';
            }
        });

        // initial fetch
        fetchNotifications();
    }

    // ══════════════════════════════════════════════════════════════
    // NAVBAR SEARCH WITH SUGGESTIONS (Recipe + Ingredients)
    // ══════════════════════════════════════════════════════════════
    const searchInput = document.getElementById('navbar-search-input');
    const searchContainer = document.querySelector('.navbar-search');
    
    if (!searchInput || !searchContainer) return;

    // Search mode: 'recipe' or 'ingredients'
    let searchMode = 'recipe';

    // Create dropdown element
    const searchDropdown = document.createElement('div');
    searchDropdown.className = 'navbar-search-dropdown';
    searchDropdown.innerHTML = '';
    searchContainer.style.position = 'relative';
    searchContainer.appendChild(searchDropdown);

    // Add styles for dropdown
    const style = document.createElement('style');
    style.textContent = `
        .navbar-search {
            position: relative;
        }
        .navbar-search-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-height: 450px;
            overflow-y: auto;
            z-index: 9999;
            display: none;
        }
        .navbar-search-dropdown.is-open {
            display: block;
        }
        /* Search Mode Tabs */
        .search-mode-tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            padding: 0;
            margin: 0;
        }
        .search-mode-tab {
            flex: 1;
            padding: 10px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #6b7280;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .search-mode-tab:hover {
            background: #f9fafb;
            color: #374151;
        }
        .search-mode-tab.active {
            color: #f59e0b;
            border-bottom: 2px solid #f59e0b;
            margin-bottom: -1px;
        }
        .search-mode-tab svg {
            width: 14px;
            height: 14px;
        }
        /* Search Results */
        .search-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            text-decoration: none;
            color: #333;
            transition: background 0.15s;
            border-bottom: 1px solid #f3f4f6;
        }
        .search-dropdown-item:last-child {
            border-bottom: none;
        }
        .search-dropdown-item:hover {
            background: #f9fafb;
        }
        .search-dropdown-item img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .search-dropdown-item-info {
            flex: 1;
            min-width: 0;
        }
        .search-dropdown-item-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111;
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .search-dropdown-item-meta {
            font-size: 0.75rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-dropdown-item-match {
            font-size: 0.7rem;
            color: #10b981;
            background: #d1fae5;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: auto;
            flex-shrink: 0;
        }
        .search-dropdown-empty {
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        .search-dropdown-footer {
            padding: 10px 14px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            border-radius: 0 0 12px 12px;
        }
        .search-dropdown-footer a {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #f59e0b;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
        }
        .search-dropdown-footer a:hover {
            color: #d97706;
        }
        .search-highlight {
            background: #fef3c7;
            padding: 0 2px;
            border-radius: 2px;
        }
        /* Ingredient tags hint */
        .search-ingredients-hint {
            padding: 10px 14px;
            background: #fffbeb;
            border-bottom: 1px solid #fde68a;
            font-size: 0.8rem;
            color: #92400e;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .search-ingredients-hint svg {
            flex-shrink: 0;
        }
    `;
    document.head.appendChild(style);

    let debounceTimer = null;
    let allRecipes = window.navbarSearchRecipes || [];

    // Fetch recipes for search
    async function fetchRecipes() {
        try {
            const response = await fetch('/smart-recipes/backend/api/search_recipes.php');
            const data = await response.json();
            if (data.status === 'success') {
                allRecipes = data.recipes || [];
            }
        } catch (error) {
            console.error('Failed to fetch recipes for search:', error);
        }
    }

    // Search by recipe name/title
    function searchByRecipe(query) {
        if (!query || query.length < 2) return [];
        
        const q = query.toLowerCase().trim();
        const words = q.split(/\s+/);
        
        return allRecipes
            .map(recipe => {
                const title = (recipe.title || '').toLowerCase();
                const description = (recipe.description || '').toLowerCase();
                const category = (recipe.category || '').toLowerCase();
                const tags = (recipe.tags || []).join(' ').toLowerCase();
                
                let score = 0;
                let matched = false;
                
                if (title.includes(q)) {
                    score += 100;
                    matched = true;
                }
                
                words.forEach(word => {
                    if (title.includes(word)) {
                        score += 50;
                        matched = true;
                    }
                    if (category.includes(word)) {
                        score += 30;
                        matched = true;
                    }
                    if (tags.includes(word)) {
                        score += 20;
                        matched = true;
                    }
                    if (description.includes(word)) {
                        score += 10;
                        matched = true;
                    }
                });
                
                return { ...recipe, score, matched };
            })
            .filter(r => r.matched)
            .sort((a, b) => b.score - a.score)
            .slice(0, 6);
    }

    // Search by ingredients - only show recipes that have ALL ingredients
    function searchByIngredients(query) {
        if (!query || query.length < 2) return [];
        
        // Parse ingredients - split by comma
        let ingredients = [];
        if (query.includes(',')) {
            ingredients = query.split(',').map(s => s.trim().toLowerCase()).filter(s => s.length >= 2);
        } else {
            // Single ingredient or space-separated
            ingredients = query.toLowerCase().split(/\s+/).map(s => s.trim()).filter(s => s.length >= 2);
        }
        
        if (ingredients.length === 0) return [];
        
        console.log('Searching for ingredients:', ingredients);
        console.log('Total recipes to search:', allRecipes.length);
        
        // Common ingredient aliases for better matching
        const ingredientAliases = {
            'chicken': ['chicken', 'gà'],
            'beef': ['beef', 'bò', 'steak', 'sirloin'],
            'pork': ['pork', 'heo', 'bacon', 'guanciale'],
            'salmon': ['salmon', 'cá hồi'],
            'shrimp': ['shrimp', 'tôm', 'prawn'],
            'egg': ['egg', 'eggs', 'trứng'],
            'tomato': ['tomato', 'tomatoes', 'cà chua'],
            'onion': ['onion', 'onions', 'hành'],
            'garlic': ['garlic', 'tỏi'],
            'mushroom': ['mushroom', 'mushrooms', 'nấm'],
            'cheese': ['cheese', 'parmesan', 'mozzarella', 'cheddar', 'pecorino'],
            'lemon': ['lemon', 'chanh'],
            'cream': ['cream', 'heavy cream', 'kem'],
            'butter': ['butter', 'bơ'],
            'basil': ['basil', 'húng quế'],
            'spinach': ['spinach', 'rau bina'],
            'avocado': ['avocado', 'bơ'],
            'honey': ['honey', 'mật ong'],
            'rice': ['rice', 'gạo', 'cơm'],
            'pasta': ['pasta', 'spaghetti', 'penne', 'fettuccine'],
            'noodles': ['noodles', 'mì', 'bún', 'phở'],
        };
        
        // Check if ingredient matches in text
        function ingredientMatches(ing, searchText) {
            // Direct match
            if (searchText.includes(ing)) return true;
            
            // Check aliases
            for (const [key, aliases] of Object.entries(ingredientAliases)) {
                if (aliases.includes(ing) || ing === key) {
                    // Check if any alias is in the text
                    if (aliases.some(alias => searchText.includes(alias))) return true;
                }
            }
            return false;
        }
        
        const results = allRecipes
            .map(recipe => {
                const title = (recipe.title || '').toLowerCase();
                const description = (recipe.description || '').toLowerCase();
                const recipeIngredients = (recipe.ingredients || []).join(' ').toLowerCase();
                const tags = (recipe.tags || []).join(' ').toLowerCase();
                const searchText = `${title} ${description} ${recipeIngredients} ${tags}`;
                
                let matchedCount = 0;
                let matchedList = [];
                ingredients.forEach(ing => {
                    if (ingredientMatches(ing, searchText)) {
                        matchedCount++;
                        matchedList.push(ing);
                    }
                });
                
                const rating = parseFloat(recipe.rating) || 0;
                const score = rating * 10;
                
                return { 
                    ...recipe, 
                    score, 
                    matchedIngredients: matchedCount, 
                    totalIngredients: ingredients.length,
                    matchedList,
                    _searchText: searchText // for debugging
                };
            });
        
        // Log some debug info
        console.log('Recipes with partial matches:', results.filter(r => r.matchedIngredients > 0).map(r => ({
            title: r.title,
            matched: r.matchedList,
            total: r.totalIngredients
        })));
        
        // ONLY show recipes that match ALL ingredients
        const filtered = results.filter(r => r.matchedIngredients === r.totalIngredients);
        console.log('Recipes matching ALL ingredients:', filtered.length);
        
        return filtered
            .sort((a, b) => b.score - a.score)
            .slice(0, 8);
    }

    // Highlight matching text
    function highlightText(text, query) {
        if (!query) return text;
        const words = query.toLowerCase().split(/[\s,]+/).filter(w => w.length > 1);
        let result = text;
        words.forEach(word => {
            const regex = new RegExp(`(${word})`, 'gi');
            result = result.replace(regex, '<span class="search-highlight">$1</span>');
        });
        return result;
    }

    // Render dropdown
    function renderDropdown(query) {
        if (!query || query.length < 2) {
            searchDropdown.classList.remove('is-open');
            return;
        }

        const results = searchMode === 'ingredients' ? searchByIngredients(query) : searchByRecipe(query);
        const encodedQuery = encodeURIComponent(query);

        let html = '';

        // Mode tabs
        html += `
            <div class="search-mode-tabs">
                <button type="button" class="search-mode-tab ${searchMode === 'recipe' ? 'active' : ''}" data-mode="recipe">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    By Recipe Name
                </button>
                <button type="button" class="search-mode-tab ${searchMode === 'ingredients' ? 'active' : ''}" data-mode="ingredients">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/>
                        <line x1="6" y1="17" x2="18" y2="17"/>
                    </svg>
                    By Ingredients
                </button>
            </div>
        `;

        // Hint for ingredients mode
        if (searchMode === 'ingredients') {
            html += `
                <div class="search-ingredients-hint">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    Separate ingredients with commas: <strong>chicken, garlic, tomato</strong>
                </div>
            `;
        }

        if (results.length === 0) {
            html += `<div class="search-dropdown-empty">
                No recipes found with <strong>all</strong> ingredients: "${escapeHTML(query)}"
                ${searchMode === 'ingredients' ? '<br><small>Try fewer ingredients or different ones</small>' : ''}
            </div>`;
        } else {
            results.forEach(recipe => {
                const img = recipe.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&h=100&fit=crop';
                const time = recipe.ready_in || '';
                const category = recipe.category || '';
                const url = `/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=${recipe.id}`;
                
                html += `
                    <a href="${url}" class="search-dropdown-item">
                        <img src="${img}" alt="${escapeHTML(recipe.title)}" loading="lazy">
                        <div class="search-dropdown-item-info">
                            <p class="search-dropdown-item-title">${highlightText(escapeHTML(recipe.title), query)}</p>
                            <div class="search-dropdown-item-meta">
                                ${category ? `<span>${escapeHTML(category)}</span>` : ''}
                                ${time ? `<span>• ${escapeHTML(time)}</span>` : ''}
                            </div>
                        </div>
                        ${searchMode === 'ingredients' ? 
                            `<span class="search-dropdown-item-match">✓ All ${recipe.totalIngredients} ingredients</span>` 
                            : ''}
                    </a>
                `;
            });
        }

        // Footer with "See all" link
        const seeAllUrl = searchMode === 'ingredients' 
            ? `/smart-recipes/frontend/pages/search/by_ingredients.php?ingredients=${encodedQuery}`
            : `/smart-recipes/frontend/pages/recipes/all_recipes.php?search=${encodedQuery}`;

        html += `
            <div class="search-dropdown-footer">
                <a href="${seeAllUrl}">
                    See all results
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </div>
        `;

        searchDropdown.innerHTML = html;
        searchDropdown.classList.add('is-open');

        // Add tab click handlers
        searchDropdown.querySelectorAll('.search-mode-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                searchMode = this.dataset.mode;
                renderDropdown(searchInput.value.trim());
            });
        });
    }

    function escapeHTML(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            renderDropdown(this.value.trim());
        }, 200);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            renderDropdown(this.value.trim());
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchDropdown.classList.remove('is-open');
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();
            if (query) {
                if (searchMode === 'ingredients') {
                    window.location.href = `/smart-recipes/frontend/pages/search/by_ingredients.php?ingredients=${encodeURIComponent(query)}`;
                } else {
                    window.location.href = `/smart-recipes/frontend/pages/recipes/all_recipes.php?search=${encodeURIComponent(query)}`;
                }
            }
        }
    });

    document.addEventListener('click', function(e) {
        if (!searchContainer.contains(e.target)) {
            searchDropdown.classList.remove('is-open');
        }
    });

    // Fetch recipes on load
    fetchRecipes();
});