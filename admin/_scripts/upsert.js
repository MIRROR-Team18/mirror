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
        input.addEventListener('change', () => {
			// We're onchange here because we want to format the input after the user has finished typing.
			// Doing so oninput causes the number to be formatted as the user types, which makes it unusable.
			input.value = pounds.format(input.value.replace(/[^0-9.]/g, ''));
			if (input.value === '£NaN') input.value = ''; // If the user breaks it, clear the input.
        });
    });
});

/*
 * Syncs the price input with the price box
 * @param {HTMLElement} target - The target element from the event
 */
function syncPrice(target) {
    const respectiveInput = target.parentElement.querySelector(".priceInput");
    respectiveInput.disabled = !target.checked;
}