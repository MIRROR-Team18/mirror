window.addEventListener("load", () => {
    const filterGroups = document.querySelectorAll(".filterGroup");
    filterGroups.forEach((group) => {
        console.log(group.children);
    });

    const inputLabels = document.querySelectorAll(".inputLabelGroup");
    inputLabels.forEach((il) => {
        il.addEventListener("change", () => filter(il.parentElement.id));
    });

    const anyAllButtons = document.querySelectorAll(".filterGroup .title a");
    anyAllButtons.forEach((a) => {
        if (!a.id) return;
        a.addEventListener("click", updateMode)
    })

    const searchBar = document.querySelector("#search");
    searchBar.addEventListener("input", () => {
        search(searchBar.value.toLowerCase());
    });
});

/**
 * When the user clicks on a "any" or "all" button, change the mode of the filter group.
 * @param ev (Event) - The event that triggered this function
 */
function updateMode(ev) {
    ev.preventDefault(); // We don't want the page to change.

    const clicked = ev.target;
    const parent = clicked.parentElement;
    if (clicked.classList.contains("selected")) return; // Do nothing if already selected

    const currentMode = parent.querySelector("a.selected").id; // The filter group
    const newMode = clicked.id;

    if (currentMode === newMode) return; // Do nothing if already in this mode (the above check should handle this already but just in case)

    parent.querySelector(".title a.selected").classList.remove("selected"); // Remove the selected class from the old mode
    clicked.classList.add("selected"); // Add the selected class to the new mode

    filter(parent.parentElement.parentElement.id); // Call the filter function to update the display, providing the parent's parent's ID (filterGroup)
}

/**
 * With a provided filtering type and value(s), filter the objects by hiding them.
 * @param parameter (string) - What to filter by
 */
function filter(parameter) {
    // Before everything, unhide everything.
    document.querySelectorAll(".product").forEach(product => { product.style.display = "flex"; });

    switch (parameter) {
        case "forProductType": {
            const mode = document.querySelector(".filterGroup#forProductType .title a.selected").id.split("_")[1];
            const inputs = document.querySelectorAll(".filterGroup#forProductType .inputLabelGroup input");
            const filterRule = {};
            let allFalse = true;

            inputs.forEach(input => {
                filterRule[input.id] = input.checked;
                if (input.checked) allFalse = false;
            });

            if (allFalse) { } // Do nothing
            else if (mode === "any") { // Product must have any filterRule value true, iterate through products
                document.querySelectorAll(".product").forEach(product => {
                    if (!filterRule[product.dataset.productType]) product.style.display = "none";
                    // ! This whole any/all thing doesn't work with this type, but will keep it as a proof of concept.
                })
            } else if (mode === "only") { // Product must have all filterRule values which are true, iterate through filter rule
                for (const [value, active] of Object.entries(filterRule)) {
                    if (!active) continue;
                    document.querySelectorAll(".product").forEach(product => {
                        if (product.dataset.productType !== value) product.style.display = "none";
                    });
                }
            } else console.error(`Invalid mode when filtering ${parameter}: ${mode}!`);

            break;
        }
        case "forProductGender": {
            const mode = document.querySelector(".filterGroup#forProductGender .title a.selected").id.split("_")[1];
            const inputs = document.querySelectorAll(".filterGroup#forProductGender .inputLabelGroup input");
            const filterRule = {};
            let allFalse = true;

            inputs.forEach(input => {
                filterRule[input.id] = input.checked;
                if (input.checked) allFalse = false;
            });

            if (allFalse) { } // Do nothing
            else if (mode === "any") { // Product must have any filterRule value true, iterate through products
                document.querySelectorAll(".product").forEach(product => {
                    if (!filterRule[product.dataset.productGender]) product.style.display = "none";
                })
            } else if (mode === "only") { // Product must have all filterRule values which are true, iterate through filter rule
                for (const [value, active] of Object.entries(filterRule)) {
                    if (!active) continue;
                    document.querySelectorAll(".product").forEach(product => {
                        if (product.dataset.productGender !== value) product.style.display = "none";
                    });
                }
            } else console.error(`Invalid mode when filtering ${parameter}: ${mode}!`);

            break;
        }
    }
}

/**
 * Filter the products by their name
 * @param parameter (string) - What to filter by
 */
function search(parameter) {
    document.querySelectorAll(".product").forEach(product => {
        if (product.dataset.productName.toLowerCase().includes(parameter)) product.style.display = "flex";
        else product.style.display = "none";
    });
}