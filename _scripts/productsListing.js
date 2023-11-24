window.addEventListener("load", () => {
    const filterGroups = document.querySelectorAll(".filterGroup");
    filterGroups.forEach((group) => {
        console.log(group.children);
    });

    const inputLabels = document.querySelectorAll(".inputLabelGroup");
    inputLabels.forEach((il) => {
        il.addEventListener("change", () => filter(il.parentElement.id));
    });
});

/**
 * With a provided filtering type and value(s), filter the objects by hiding them.
 * @param parameter (string) - What to filter by
 */
function filter(parameter) {
    // Before everything, unhide everything.
    document.querySelectorAll(".product").forEach(product => { product.style.display = "flex"; });

    switch (parameter) {
        case "forProductType": {
            const mode = document.querySelector(".filterGroup#forProductType").dataset.currentMode;
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
                    if (!filterRule[product.dataset.productType]) product.style.display = "none"; // ! This whole any/all thing doesn't work with this type
                })
            } else if (mode === "all") { // Product must have all filterRule values which are true, iterate through filter rule
                filterRule.forEach((value, active) => {
                    if (!active) return;
                    document.querySelectorAll(".product").forEach(product => {
                        if (!product.dataset.productType === value) product.style.display = "none";
                    })
                })
            } else console.error(`Invalid mode when filtering ${parameter}!`);

            break;
        }
    }
}