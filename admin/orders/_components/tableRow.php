<?php
	global $hide;
	global $allProducts;
	global $thisProduct;
	global $db;
?>
<tr style="<?= $hide ? 'display: none;' : '' ?>">
	<td>
		<select name="products[id][]" class="productSelect" aria-label="Product" onchange="getSizes(event)">
			<option value="" selected disabled hidden>Product...</option>
			<?php
			foreach ($allProducts as $product) {
				?>
				<option value="<?= $product->productID ?>" <?= isset($thisProduct['productID']) && $product->productID == $thisProduct['productID'] ? 'selected' : '' ?>><?= $product->name ?></option>
				<?php
			}
			?>
		</select>
	</td>
	<td>
		<select name="products[size][]" class="sizeSelect" aria-label="Size" disabled onchange="calculatePrice(event)">
			<option value="" selected disabled hidden>Size...</option>
			<?php
				$priceSelected = 0;

				if (isset($thisProduct['sizeID'])) {
					$product = $db->getProduct($thisProduct['id']);

					foreach ($product->sizes as $size) {
						/** @var $size Size */
						if ($size->sizeID == $thisProduct['sizeID']) $priceSelected = $size->price;
						?>
						<option value="<?= $size->sizeID ?>" data-price="<?= $size->price ?>" <?= $size->name == $thisProduct['sizeID'] ? 'selected' : '' ?>><?= $size->name ?></option>
						<?php
					}
				}
			?>
		</select>
	</td>
	<td>
		<input class="quantityInput" type="number" min="1" max="99" name="products[quantity][]"
			   value="<?= $thisProduct['quantity'] ?? '1' ?>" aria-label="Quantity" placeholder="Quantity of Product..." disabled onchange="calculatePrice(event)">
	</td>
	<td class="price">£<?= isset($thisProduct['quantity']) ? $thisProduct['quantity'] * $priceSelected : '0.00' ?></td>
	<td><i class="fa-solid fa-trash" onclick="deleteRow(event)"></i></td>
</tr>