// API Utility Functions

const API_BASE_URL = '/smart-recipes/backend/api'; // XAMPP PHP API directory

// Generic API call function
async function apiCall(endpoint, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
        },
    };

    // Add auth token if available
    const token = localStorage.getItem('user_token');
    if (token) {
        defaultOptions.headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers,
        },
    };

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'API request failed');
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Recipe API calls
const recipeAPI = {
    // Get all recipes
    getAll: (params = {}) => {
        const queryString = new URLSearchParams(params).toString();
        return apiCall(`/recipes${queryString ? '?' + queryString : ''}`);
    },

    // Get recipe by ID
    getById: (id) => {
        return apiCall(`/recipes/${id}`);
    },

    // Create new recipe
    create: (recipeData) => {
        return apiCall('/recipes', {
            method: 'POST',
            body: JSON.stringify(recipeData),
        });
    },

    // Update recipe
    update: (id, recipeData) => {
        return apiCall(`/recipes/${id}`, {
            method: 'PUT',
            body: JSON.stringify(recipeData),
        });
    },

    // Delete recipe
    delete: (id) => {
        return apiCall(`/recipes/${id}`, {
            method: 'DELETE',
        });
    },

    // Search recipes by ingredients
    searchByIngredients: (ingredients) => {
        return apiCall('/recipes/search/ingredients', {
            method: 'POST',
            body: JSON.stringify({ ingredients }),
        });
    },

    // Get trending recipes
    getTrending: () => {
        return apiCall('/recipes/trending');
    },

    // Get popular recipes
    getPopular: () => {
        return apiCall('/recipes/popular');
    },
};

// User API calls
const userAPI = {
    // Register new user
    register: (userData) => {
        return apiCall('/auth/register', {
            method: 'POST',
            body: JSON.stringify(userData),
        });
    },

    // Login user
    login: (credentials) => {
        return apiCall('/auth/login', {
            method: 'POST',
            body: JSON.stringify(credentials),
        });
    },

    // Logout user
    logout: () => {
        return apiCall('/auth/logout', {
            method: 'POST',
        });
    },

    // Get user profile
    getProfile: () => {
        return apiCall('/user/profile');
    },

    // Update user profile
    updateProfile: (profileData) => {
        return apiCall('/user/profile', {
            method: 'PUT',
            body: JSON.stringify(profileData),
        });
    },

    // Get user bookmarks
    getBookmarks: () => {
        return apiCall('/user/bookmarks');
    },

    // Add bookmark
    addBookmark: (recipeId) => {
        return apiCall('/user/bookmarks', {
            method: 'POST',
            body: JSON.stringify({ recipe_id: recipeId }),
        });
    },

    // Remove bookmark
    removeBookmark: (recipeId) => {
        return apiCall(`/user/bookmarks/${recipeId}`, {
            method: 'DELETE',
        });
    },
};

// Comment API calls
const commentAPI = {
    // Get comments for a recipe
    getByRecipe: (recipeId) => {
        return apiCall(`/recipes/${recipeId}/comments`);
    },

    // Add comment
    create: (recipeId, commentData) => {
        return apiCall(`/recipes/${recipeId}/comments`, {
            method: 'POST',
            body: JSON.stringify(commentData),
        });
    },

    // Update comment
    update: (commentId, commentData) => {
        return apiCall(`/comments/${commentId}`, {
            method: 'PUT',
            body: JSON.stringify(commentData),
        });
    },

    // Delete comment
    delete: (commentId) => {
        return apiCall(`/comments/${commentId}`, {
            method: 'DELETE',
        });
    },
};

// Rating API calls
const ratingAPI = {
    // Add or update rating
    rate: (recipeId, rating) => {
        return apiCall(`/recipes/${recipeId}/rate`, {
            method: 'POST',
            body: JSON.stringify({ rating }),
        });
    },

    // Get average rating
    getAverage: (recipeId) => {
        return apiCall(`/recipes/${recipeId}/rating`);
    },
};

// Category and Tag API calls
const categoryAPI = {
    // Get all categories
    getAll: () => {
        return apiCall('/categories');
    },

    // Get recipes by category
    getRecipes: (categoryId) => {
        return apiCall(`/categories/${categoryId}/recipes`);
    },
};

const tagAPI = {
    // Get all tags
    getAll: () => {
        return apiCall('/tags');
    },

    // Get recipes by tag
    getRecipes: (tagId) => {
        return apiCall(`/tags/${tagId}/recipes`);
    },
};

// Newsletter API
const newsletterAPI = {
    // Subscribe to newsletter
    subscribe: (email) => {
        return apiCall('/newsletter/subscribe', {
            method: 'POST',
            body: JSON.stringify({ email }),
        });
    },
};

// Export API functions
window.api = {
    recipe: recipeAPI,
    user: userAPI,
    comment: commentAPI,
    rating: ratingAPI,
    category: categoryAPI,
    tag: tagAPI,
    newsletter: newsletterAPI,
};
