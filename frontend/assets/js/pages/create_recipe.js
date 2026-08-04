/* ── 1. Hàm kích hoạt chọn file (Dùng window. để HTML gọi được) ── */
window.clickFileInput = function(el) {
    // Tìm thẻ input file nằm bên trong cùng một khối với cái div vừa nhấn
    const parent = el.parentElement;
    const input = parent.querySelector('input[type="file"]');
    if (input) input.click();
};

/* ── 2. Hàm xem trước ảnh bìa ── */
window.previewImage = function(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('coverPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            
            preview.src = e.target.result;
            preview.style.display = 'block'; // Hiện ảnh lên
            
            if (placeholder) {
                placeholder.style.opacity = '0'; // Ẩn cái chữ và icon hướng dẫn đi
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
};

/* ── Hàm xem trước nhiều ảnh (Gallery) ── */
window.previewGalleryImages = function(input) {
    const container = document.getElementById('galleryPreview');
    container.innerHTML = ''; // Xóa các ảnh cũ
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '60px';
                img.style.height = '60px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '6px';
                img.style.border = '1px solid #ddd';
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
};

/* ── 3. Hàm xem trước ảnh cho từng Bước ── */
window.previewStepImage = function(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Tìm thẻ img nằm ngay sau cái div upload của bước đó
            const stepItem = input.closest('.cr-step-item');
            const preview = stepItem.querySelector('.cr-step-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
};

/* ── 4. Các logic thêm hàng (Giữ nguyên như cũ) ── */
document.addEventListener('DOMContentLoaded', function () {
    const recipeForm = document.getElementById('recipeForm');

    window.addIngredientRow = function() {
        const tbody = document.getElementById('ingredientsBody');
        const i = Date.now();
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" class="cr-input" name="ingredients[${i}][name]" placeholder="Ingredient"></td>
            <td><input type="number" class="cr-input" name="ingredients[${i}][quantity]" step="0.01"></td>
            <td><select class="cr-input cr-select" name="ingredients[${i}][unit]"><option>unit</option><option>cup</option><option>tbsp</option><option>tsp</option><option>g</option><option>kg</option><option>ml</option><option>l</option></select></td>
            <td><button type="button" class="cr-btn-del" onclick="removeRow(this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button></td>`;
        tbody.appendChild(tr);
    };

    window.addStep = function() {
        const list = document.getElementById('steps-wrapper');
        const n = list.querySelectorAll('.cr-step-item').length + 1;
        const div = document.createElement('div');
        div.className = 'cr-step-item';
        div.innerHTML = `
            <div class="cr-step-header">
                <span class="cr-step-label">Step ${n} Description</span>
                <button type="button" class="cr-btn-del" onclick="removeStep(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <textarea class="cr-textarea" name="steps[]" rows="2" placeholder="Describe this step…"></textarea>
            <p class="cr-step-img-label">Step ${n} Image (Optional)</p>
            <div class="cr-step-img-upload" onclick="window.clickFileInput(this)" style="cursor: pointer; border: 1.5px dashed #ddd; padding: 15px; text-align: center; border-radius: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 5px;">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
                <br>Click to upload image
            </div>
            <input type="file" name="step_images[]" accept="image/*" style="display:none;" onchange="window.previewStepImage(this)">
            <img class="cr-step-preview" src="" alt="Step Preview" style="display:none; width:100%; margin-top:10px; border-radius:8px; object-fit: cover; max-height: 200px;">`;
        list.appendChild(div);
    };

    window.removeRow = function(btn) { btn.closest('tr').remove(); };
    window.removeStep = function(btn) { btn.closest('.cr-step-item').remove(); };

    window.addTag = function() {
        const select = document.getElementById('tagSelect');
        const tagValue = select.value;
        if (!tagValue) return;
        
        // Check if tag already exists
        const hiddenContainer = document.getElementById('hiddenTagsContainer');
        if (hiddenContainer.querySelector(`input[value="${tagValue}"]`)) return;
        
        // Add to UI
        const tagsSelected = document.getElementById('tagsSelected');
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'cr-tag-pill';
        btn.onclick = function() { window.removeTag(this, tagValue); };
        btn.innerHTML = `${tagValue} <span class="cr-tag-x">×</span>`;
        tagsSelected.appendChild(btn);
        
        // Add hidden input
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tagValue;
        hiddenContainer.appendChild(input);
        
        // Reset select
        select.value = '';
    };

    window.removeTag = function(btn, tagValue) {
        btn.remove();
        const hiddenContainer = document.getElementById('hiddenTagsContainer');
        const input = hiddenContainer.querySelector(`input[value="${tagValue}"]`);
        if (input) input.remove();
    };

    // Gửi Form (Fetch API)
    if (recipeForm) {
        recipeForm.onsubmit = function(e) {
            e.preventDefault();
            fetch('/smart-recipes/backend/api/create_recipe.php', { method: 'POST', body: new FormData(this) })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Công thức đã được đăng thành công!');
                    window.location.href = '/smart-recipes/frontend/pages/user/profile.php?tab=my_recipes';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            });
        };
    }
});