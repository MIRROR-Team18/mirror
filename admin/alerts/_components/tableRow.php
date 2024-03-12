<?php
	global $hide;
	global $threshold;
	global $i;
	// What is &#8203; ? It's a zero-width space that only exists because without it the labels would sink into the ocean for no real reason.
?>
<tr style="<?= $hide ? 'display: none;' : '' ?>">
	<td>
		<input type="number" name="thresholds[value][<?= $i ?>]" value="<?= $threshold['threshold'] ?? '' ?>" aria-label="Threshold" placeholder="Threshold Value" min="0" <?= $hide ? '' : 'required' ?>>
	</td>
	<td>
		<input type="checkbox" id="thresholds[email][<?= $i ?>]" name="thresholds[email][<?= $i ?>]" <?= isset($threshold['byEmail']) && $threshold['byEmail'] == 1 ? "checked" : "" ?>>
		<label for="thresholds[email][<?= $i ?>]">&#8203;</label>
	</td>
	<td>
		<input type="checkbox" id="thresholds[sms][<?= $i ?>]" name="thresholds[sms][<?= $i ?>]" <?= isset($threshold['bySMS']) && $threshold['bySMS'] == 1 ? "checked" : "" ?>>
		<label for="thresholds[sms][<?= $i ?>]">&#8203;</label>
	</td>
	<td>
		<input type="checkbox" id="thresholds[site][<?= $i ?>]" name="thresholds[site][<?= $i ?>]" <?= isset($threshold['bySite']) && $threshold['bySite'] == 1 ? "checked" : "" ?>>
		<label for="thresholds[site][<?= $i ?>]">&#8203;</label>
	</td>
    <td><i class="fa-solid fa-trash" onclick="deleteRow(event)"></i></td>
</tr>