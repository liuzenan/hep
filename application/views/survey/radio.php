<?php foreach (json_decode($question->content, TRUE) as $key => $text) { ?>
	<label class="radio">
	<?php if ($key == $response): ?>
		<input type="radio" name="<?php echo $question->id; ?>" value="<?php echo $key; ?>" checked>
	<?php else: ?>
		<input type="radio" name="<?php echo $question->id; ?>" value="<?php echo $key; ?>">
	<?php endif ?>
	<?php echo $text; ?>
	</label>
<?php } ?>

