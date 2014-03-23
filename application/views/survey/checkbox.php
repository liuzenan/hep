<?php foreach (json_decode($question->content, TRUE) as $key => $text) { ?>
	<label class="checkbox">
	<?php if ($response && isset($response[$key])): ?>
		<input type="checkbox" name="<?php echo $question->id.'['.$key.']'; ?>" value="<?php echo $key; ?>" checked>
	<? else: ?>
		<input type="checkbox" name="<?php echo $question->id.'['.$key.']'; ?>" value="<?php echo $key; ?>">
	<? endif ?>
	<?php echo $text; ?>
	</label>
<?php } ?>