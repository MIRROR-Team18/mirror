function createRow() {
	const tbody = document.querySelector('#productsTable tbody');
	const final = tbody.lastElementChild;

	const newRow = document.createElement('tr');
	newRow.innerHTML = `
		<td><input type="text" name="products[id][]" value=""></td>
		<td><input type="text" name="products[quantity][]" value=""></td>
		<td><input type="text" name="products[size][]" value=""></td>
		<td>£0.00</td>
		<td><i class="fa-solid fa-trash"></i></td>
	`;

	tbody.insertBefore(newRow, final);
}