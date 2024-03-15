/**
 * Creates a row when clicking the "Add Row" button.
 */
function createRow() {
	const tbody = document.querySelector('#alertsTable tbody');
	const final = tbody.lastElementChild;

	const newRow = tbody.firstElementChild.cloneNode(true);
	// i is the number of rows from the top of the table. It's set by PHP.
	// We change it so that the checkboxes work.
	newRow.querySelectorAll('input').forEach(input => {
		input.id = input.id.replace(/\d+/, i)
		input.name = input.name.replace(/\d+/, i)
	});
	newRow.querySelectorAll('label').forEach(label =>
		label.htmlFor = label.htmlFor.replace(/\d+/, i)
	)
	i++;
	newRow.style.display = '';

	tbody.insertBefore(newRow, final);
}

/**
 * Delete the row that the delete button is in.
 * @param ev (Event) - The event that triggered this function
 */
function deleteRow(ev) {
	const row = ev.target.parentElement.parentElement;
	row.remove();
}

/**
 * Deletes this alert from the database.
 */
function deleteAlert() {
	if (confirm("Are you sure you want to delete this alert?")) {
		window.location.href = window.location.href.replace("upsert", "delete");
	}
}