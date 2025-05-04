function changeImage(thumbnail) {
    document.getElementById('mainImage').src = thumbnail.src;
    
    // Remove selected-thumbnail class from all thumbnails
    document.querySelectorAll('.thumbnail-img').forEach(img => img.classList.remove('selected-thumbnail'));
    
    // Add selected-thumbnail class to clicked thumbnail
    thumbnail.classList.add('selected-thumbnail');
}

document.addEventListener("DOMContentLoaded", function () {
    const colorOptions = document.querySelectorAll("input[name='color']");
    const storageOptions = document.querySelectorAll("input[name='storage']");

    colorOptions.forEach(option => {
        option.addEventListener("change", function () {
            updateSelectedOption("color", this.id);
        });
    });

    storageOptions.forEach(option => {
        option.addEventListener("change", function () {
            updateSelectedOption("storage", this.id);
        });
    });

    function updateSelectedOption(type, selectedId) {
        document.querySelectorAll(`input[name='${type}'] + label`).forEach(label => {
            label.style.border = "1px solid black"; // Reset border for all labels
        });

        const selectedLabel = document.querySelector(`label[for='${selectedId}']`);
        if (selectedLabel) {
            selectedLabel.style.border = "2px solid red"; // Highlight selected option
        }
    }
});