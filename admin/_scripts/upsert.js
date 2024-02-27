const pounds = new Intl.NumberFormat('en-GB', {
    style: 'currency',
    currency: 'GBP',
})

window.addEventListener("load", () => {
    document.querySelectorAll(".priceBox").forEach(input => {
        input.addEventListener("input", (ev) => syncPrice(ev.target));
        syncPrice(input);
    });

    document.querySelectorAll('.priceInput').forEach(input => {
        // We're onchange here because we want to format the input after the user has finished typing.
        // Doing so oninput causes the number to be formatted as the user types, which makes it unusable.
        input.addEventListener('change', () => {
            // Make value numbers only, convert it to a float and ensure it's not over 10000 (database constraint)
            const safeVal = input.value.replace(/[^0-9.]/g, '');
            const valAsNumber = parseFloat(safeVal) >= 10000 ? 0 : parseFloat(safeVal);
			input.value = pounds.format(valAsNumber);
			if (input.value === '£NaN') input.value = '£0.00'; // If the user breaks it, clear the input.
        });
    });
});

/**
 * Syncs the price input with the price box
 * @param {HTMLInputElement} target - The target element from the event
 */
function syncPrice(target) {
    const respectiveInput = target.parentElement.querySelector(".priceInput");
    respectiveInput.disabled = !target.checked;
}

/**
 * Updates the image preview with images
 */
function showImagePreview() {
    const row = document.querySelector('#imageUpload');
    const inputCol = document.querySelector('#imageUploadInput');
    const inputElement = document.querySelector("#imageInput");

    for (let i = 0; i < inputElement.files.length; i++) {
        const col = document.createElement('div');
        col.classList.add('col', 'new');
        const img = document.createElement('img');
        img.src = URL.createObjectURL(inputElement.files[i]);
        img.alt = "Image preview";
        img.dataset.name = inputElement.files[i].name;

        const overlay = document.createElement('div');
        overlay.classList.add('overlay');
        const deleteIcon = document.createElement('i');
        deleteIcon.classList.add('fa-solid', 'fa-trash');
        deleteIcon.addEventListener('click', deleteImage);
        overlay.append(deleteIcon);

        col.append(overlay);
        col.append(img);
        row.insertBefore(col, inputCol);
    }
}

/**
 * Deletes an image from the section, new and old.
 * @param {Event} event - The event that triggered this function
 */
function deleteImage(event) {
    const parent = event.target.parentElement.parentElement;
    const deletedInput = document.querySelector("#deletedImages");

    if (confirm("Are you sure you want to delete this image?")) {
        const name = parent.querySelector("img").dataset.name;
        deletedInput.value = deletedInput.value + name + ";";

        parent.remove();
    }
}