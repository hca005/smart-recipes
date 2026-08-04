document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.recipe-detail-container');
    if (!container) return;

    const recipeId = container.dataset.recipeId || '';
    const recipeTitle = container.dataset.recipeTitle || document.title;
    const isLoggedIn = container.dataset.isLoggedIn === '1';
    const signInUrl = container.dataset.signInUrl || '';

    const bookmarkBtn = document.getElementById('btn-bookmark');
    const shareBtn = document.getElementById('btn-share');
    const copyBtn = document.getElementById('btn-copy-link');
    const madeThisBtn = document.getElementById('i-made-this-btn');

    const modal = document.getElementById('made-this-modal');
    const closeModalBtn = document.getElementById('made-this-close');
    const uploadArea = document.getElementById('upload-area');
    const imageInput = document.getElementById('image-upload');
    const previewImage = document.getElementById('preview-image');
    const postCaption = document.getElementById('post-caption');
    const submitPostBtn = document.getElementById('submit-post-btn');

    const heroImage = document.getElementById('made-this-hero-image');
    const heroCredit = document.getElementById('made-this-hero-credit');
    const thumbGrid = document.getElementById('made-this-thumb-grid');
    const expandedGrid = document.getElementById('made-this-expanded-grid');
    const communityPhotoCount = document.getElementById('community-photo-count');

    function escapeText(value) {
        return String(value ?? '').trim();
    }

    function setHeroImage(src, userName) {
        if (!heroImage || !src) return;

        heroImage.src = src;
        heroImage.alt = `Community upload by ${userName || 'Community'}`;

        if (heroCredit) {
            heroCredit.textContent = `PHOTO BY ${(userName || 'Community').toUpperCase()}`;
        }
    }

    function clearUploadForm() {
        if (imageInput) imageInput.value = '';
        if (postCaption) postCaption.value = '';

        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.remove('is-visible');
        }
    }

    function openMadeThisModal() {
        if (!modal) return;
        modal.classList.remove('is-hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
    }

    function closeMadeThisModal() {
        if (!modal) return;
        modal.classList.add('is-hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        clearUploadForm();
    }

    function handleSelectedFile(file) {
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please upload an image file.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            if (!previewImage) return;
            previewImage.src = event.target?.result || '';
            previewImage.classList.add('is-visible');
        };
        reader.readAsDataURL(file);
    }

    function bindGalleryThumb(button) {
        button.addEventListener('click', () => {
            const isViewAll = button.dataset.viewAll === 'true';

            if (isViewAll) {
                if (expandedGrid) {
                    expandedGrid.classList.toggle('is-open');
                }
                return;
            }

            const imageSrc = button.dataset.galleryImage;
            const userName = button.dataset.galleryUser || 'Community';

            setHeroImage(imageSrc, userName);

            if (thumbGrid) {
                thumbGrid.querySelectorAll('.made-this-thumb').forEach((thumb) => {
                    thumb.classList.remove('is-active');
                });
            }

            button.classList.add('is-active');
        });
    }

    function prependNewThumbnail(imageSrc, userName) {
        if (!thumbGrid || !imageSrc) return;

        const viewAllButton = thumbGrid.querySelector('[data-view-all="true"]');

        thumbGrid.querySelectorAll('.made-this-thumb:not([data-view-all="true"])').forEach((thumb) => {
            thumb.classList.remove('is-active');
        });

        const newThumb = document.createElement('button');
        newThumb.type = 'button';
        newThumb.className = 'made-this-thumb is-active';
        newThumb.dataset.galleryImage = imageSrc;
        newThumb.dataset.galleryUser = userName;
        newThumb.setAttribute('aria-label', `Community photo by ${userName}`);

        const img = document.createElement('img');
        img.src = imageSrc;
        img.alt = `Community upload by ${userName}`;
        newThumb.appendChild(img);

        if (viewAllButton) {
            thumbGrid.insertBefore(newThumb, viewAllButton);
        } else {
            thumbGrid.appendChild(newThumb);
        }

        bindGalleryThumb(newThumb);

        const normalThumbs = thumbGrid.querySelectorAll('.made-this-thumb:not([data-view-all="true"])');
        normalThumbs.forEach((thumb, index) => {
            if (index > 2) {
                thumb.remove();
            }
        });
    }

    function prependExpandedCard(imageSrc, userName, captionText) {
        if (!expandedGrid || !imageSrc) return;

        const figure = document.createElement('figure');
        figure.className = 'made-this-expanded-card';

        const img = document.createElement('img');
        img.src = imageSrc;
        img.alt = `Community upload by ${userName}`;

        const figcaption = document.createElement('figcaption');

        const strong = document.createElement('strong');
        strong.textContent = userName;

        const span = document.createElement('span');
        span.textContent = captionText || 'Shared a new photo.';

        figcaption.appendChild(strong);
        figcaption.appendChild(span);

        figure.appendChild(img);
        figure.appendChild(figcaption);

        expandedGrid.prepend(figure);
    }

    function increasePhotoCount() {
        if (!communityPhotoCount) return;

        const current = parseInt(communityPhotoCount.dataset.count || '0', 10) || 0;
        const next = current + 1;

        communityPhotoCount.dataset.count = String(next);
        communityPhotoCount.textContent = `${next} photos`;
    }

    async function copyCurrentLink() {
        const url = window.location.href;

        try {
            await navigator.clipboard.writeText(url);
            alert('Link copied to clipboard!');
        } catch (error) {
            const input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link copied!');
        }
    }

    function shareRecipe() {
        const url = window.location.href;

        if (navigator.share) {
            navigator.share({
                title: recipeTitle,
                url
            }).catch(() => {});
            return;
        }

        copyCurrentLink();
    }

    function handleBookmark() {
        if (!isLoggedIn) {
            if (typeof window.showAuthModal === 'function') {
                window.showAuthModal();
                return;
            }

            if (signInUrl) {
                window.location.href = signInUrl;
            }
            return;
        }

        bookmarkBtn?.classList.toggle('is-active');
    }

    function handleSubmitPost() {
        if (!isLoggedIn) {
            if (signInUrl) {
                window.location.href = signInUrl;
            }
            return;
        }

        const imageSrc = previewImage?.src || '';
        const captionText = escapeText(postCaption?.value) || 'Shared a new photo.';
        const userName = 'You';

        if (!imageSrc) {
            alert('Please upload a photo of your dish first.');
            return;
        }

        setHeroImage(imageSrc, userName);
        prependNewThumbnail(imageSrc, userName);
        prependExpandedCard(imageSrc, userName, captionText);
        increasePhotoCount();
        closeMadeThisModal();

        alert('Your photo has been added to the community section.');
    }

    // Initial bind for existing thumbs
    thumbGrid?.querySelectorAll('.made-this-thumb').forEach((button) => {
        bindGalleryThumb(button);
    });

    // Action buttons
    bookmarkBtn?.addEventListener('click', handleBookmark);
    shareBtn?.addEventListener('click', shareRecipe);
    copyBtn?.addEventListener('click', copyCurrentLink);
    madeThisBtn?.addEventListener('click', openMadeThisModal);

    // Modal
    closeModalBtn?.addEventListener('click', closeMadeThisModal);

    modal?.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeMadeThisModal();
        }
    });

    // Upload area
    uploadArea?.addEventListener('click', () => {
        imageInput?.click();
    });

    uploadArea?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            imageInput?.click();
        }
    });

    ['dragenter', 'dragover'].forEach((eventName) => {
        uploadArea?.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadArea.classList.add('is-dragover');
        });
    });

    ['dragleave', 'dragend', 'drop'].forEach((eventName) => {
        uploadArea?.addEventListener(eventName, (event) => {
            event.preventDefault();
            uploadArea.classList.remove('is-dragover');
        });
    });

    uploadArea?.addEventListener('drop', (event) => {
        const file = event.dataTransfer?.files?.[0];
        if (file) handleSelectedFile(file);
    });

    imageInput?.addEventListener('change', (event) => {
        const file = event.target.files?.[0];
        if (file) handleSelectedFile(file);
    });

    submitPostBtn?.addEventListener('click', handleSubmitPost);

    // Like buttons
    document.querySelectorAll('[data-like-button="true"]').forEach((button) => {
        button.addEventListener('click', () => {
            button.classList.toggle('is-liked');
        });
    });

    // Expose close function in case something else needs it
    window.closeMadeThisModal = closeMadeThisModal;
    window.openMadeThisModal = openMadeThisModal;

    // Keep recipe id available for future AJAX
    window.recipeDetailState = {
        recipeId,
        isLoggedIn
    };
});