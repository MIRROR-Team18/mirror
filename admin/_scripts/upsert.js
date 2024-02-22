window.addEventListener("load", () => {
   document.querySelectorAll(".priceBox").forEach((input) => {
       input.addEventListener("input", (ev) => syncPrice(ev.target));
       syncPrice(input);
   })
});

/*
 * Syncs the price input with the price box
 * @param {HTMLElement} target - The target element from the event
 */
function syncPrice(target) {
    const respectiveInput = target.parentElement.querySelector(".priceInput");
    respectiveInput.disabled = !target.checked;
}