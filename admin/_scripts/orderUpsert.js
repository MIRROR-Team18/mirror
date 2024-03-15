const pounds = new Intl.NumberFormat('en-GB', {
	style: 'currency',
	currency: 'GBP',
})

/**
 * Creates a row when clicking the "Add Row" button.
 */
function createRow() {
	const tbody = document.querySelector('#productsTable tbody');
	const final = tbody.lastElementChild;

	const newRow = tbody.firstElementChild.cloneNode(true);
	newRow.style.display = '';

	tbody.insertBefore(newRow, final);
}

/**
 * Gets the sizes for the product selected and populates the size select with them.
 * @param ev (Event) - The event that triggered this function
 * @returns {void}
 */
async function getSizes(ev) {
	const element = ev.target;
	const productChosen = element.value;
	const sizeSelect = element.parentElement.parentElement.querySelector('.sizeSelect');
	const quantityInput = element.parentElement.parentElement.querySelector('.quantityInput');

	sizeSelect.innerHTML = '';

	if (productChosen === '') {
		return;
	}

	const request = await fetch(`./sizesForProduct.php?productID=${productChosen}`, {
		credentials: 'same-origin'
	});
	const json = await request.json();
	if (json['error']) {
		console.error(json['error']);
		return;
	}

	const sizes = Object.values(json);
	if (sizes.length === 0) {
		const option = document.createElement('option');
		option.value = '';
		option.textContent = 'No sizes available';
		option.hidden = true;
		sizeSelect.appendChild(option);
		sizeSelect.disabled = false;
		return;
	}

	for (const size of Object.values(sizes)) {
		const option = document.createElement('option');
		option.value = size['sizeID'];
		option.textContent = size['name'];
		option.dataset.price = size['price'];
		sizeSelect.appendChild(option);
	}

	sizeSelect.disabled = false;
	quantityInput.disabled = false;

	calculatePrice({ target: quantityInput });
}

/**
 * Calculate the price of the product based on the quantity and size selected.
 * @param ev (Event) - The event that triggered this function. Finds parent's parent to make it work across all inputs.
 */
function calculatePrice(ev) {
	const parent = ev.target.parentElement.parentElement;

	const quantityInput = parent.querySelector('.quantityInput');
	const quantity = quantityInput.value || 0;

	const sizeSelect = parent.querySelector('.sizeSelect');
	const price = sizeSelect.selectedOptions[0].dataset.price || 0;

	const priceElement = parent.querySelector('.price');
	priceElement.textContent = pounds.format(quantity * price);
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
 * Deletes this order from the database.
 */
function deleteOrder() {
	if (confirm("Are you sure you want to delete this order?\nThis could have undesirable side effects, especially outbound!")) {
		window.location.href = window.location.href.replace("upsert", "delete");
	}
}