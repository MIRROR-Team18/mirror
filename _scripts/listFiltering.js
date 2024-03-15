window.addEventListener("load", () => {
    const inputLabels = document.querySelectorAll(".inputLabelGroup");
    inputLabels.forEach((il) => {
        il.addEventListener("change", filter);
    });

    const anyOnlyButtons = document.querySelectorAll(".filterGroup .title a");
    anyOnlyButtons.forEach((a) => {
        if (!a.id) return;
        a.addEventListener("click", updateMode)
    })

    const searchBar = document.querySelector("#search");
    searchBar.addEventListener("input", filter);
});

/**
 * When the user clicks on an "any" or "only" button, change the mode of the filter group.
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

    filter(); // Call the filter function to update the display
}

/**
 * Filter the objects based on the filter groups and search bar.
 * <strong>This function requires options to be laid out in a particular way.</strong>
 */
function filter() {
    // Before anything, un-hide everything.
    document.querySelectorAll(".listObject").forEach(obj => { obj.style.display = ""; });

    // The previous approach was naive in the sense that it would only apply the filter of what changed, but multiple filters can be active at once.
    // This means that we need to re-apply all filters every time one of them changes instead.
    const filterGroups = document.querySelectorAll(".filterGroup");
    filterGroups.forEach(filterGroup => {
        const forWhat = filterGroup.dataset.for;
        const mode = filterGroup.querySelector(".title a.selected").id.split("_")[1];
        const inputs = filterGroup.querySelectorAll(".inputLabelGroup input");
        const filterRule = {};
        let allFalse = true;

        inputs.forEach(input => {
            filterRule[input.id] = input.checked;
            if (input.checked) allFalse = false;
        });

        if (allFalse) { } // Do nothing for this rule
        else if (mode === "any") { // Object must have any filterRule value true, iterate through objects
            document.querySelectorAll(".listObject").forEach(obj => {
                if (!filterRule[obj.dataset[forWhat]]) obj.style.display = "none";
            })
        } else if (mode === "only") { // Objects must have all filterRule values which are true, iterate through filter rule
            for (const [key, value] of Object.entries(filterRule)) {
                if (!value) continue; // If unchecked, skip
                document.querySelectorAll(".listObject").forEach(obj => {
                    if (obj.dataset[forWhat] !== key) obj.style.display = "none";
                });
            }
        } else console.error(`Unhandled mode when filtering ${forWhat}, was provided ${mode}!`);
    });

    // Finally, apply the search filter. This is separate because it's not a filter group.
    const search = document.querySelector("#search").value.toLowerCase();
    if (search !== "") {
        document.querySelectorAll(".listObject").forEach(obj => {
            if (!obj.dataset.name.toLowerCase().includes(search)) obj.style.display = "none";
        });
    }
}

function reset() {
    document.querySelectorAll(".inputLabelGroup input").forEach(input => input.checked = false);
    document.querySelector("#search").value = "";
    filter();
}