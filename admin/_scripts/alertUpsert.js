/**
 * Creates a row when clicking the "Add Row" button.
 */
function createRow() {
	const tbody = document.querySelector('#alertsTable tbody');
	const final = tbody.lastElementChild;

	const newRow = tbody.firstElementChild.cloneNode(true);
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