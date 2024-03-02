function createRow() {
	const tbody = document.querySelector('#productsTable tbody');
	const final = tbody.lastElementChild;

	const newRow = tbody.firstElementChild.cloneNode(true);
	newRow.style.display = '';

	tbody.insertBefore(newRow, final);
}