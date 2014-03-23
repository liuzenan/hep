<ul>
	<li>Completed: <?php echo $completed; ?></li>
	<li>Incomplete: <?php echo $incomplete; ?></li>
</ul>

<?php if ($completed == 0) $completed = -1; // prevent division by zero ?>
	<?php
		$index = 0; 
		foreach ($questions as $question) {
			$index ++;
			echo '<div class="survey-item">' . $index.'. '.nl2br(htmlspecialchars($question['description'])).'</div class="survey-item">';
		?>
		<table class='table table-bordered'>
		<thead><tr><th>Response</th><th style="width: 100px;">Frequency</th></tr></thead>
		<tbody>
			<?php 
				$answered = 0; 
				if ($question['type'] == 'radio' || $question['type'] == 'checkbox'): ?>
				<?php foreach (json_decode($question['content']) as $val => $text): ?>
					<tr>
						<td>
							<?php echo $text; ?> 
						</td>
						<td>
							<?php 
								$freq = isset($tallied[$question['id']][$val]) ? $tallied[$question['id']][$val] : 0;
								$answered += $freq;
								echo $freq; 
							?>
							&nbsp;(<?php echo round($freq / $completed * 100, 2); ?>%)
						</td>
					</tr>
				<?php endforeach ?>
			<?php elseif ($completed > 0) : ?>
				<?php foreach ($tallied[$question['id']] as $option => $freq): ?>
					<tr>
						<td>
							<?php $answered += $freq; echo nl2br(htmlspecialchars($option)); ?> 
						</td>
						<td>
							<?php echo $freq; ?>&nbsp;(<?php echo round($freq / $completed * 100, 2); ?>%)
						</td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
			<!-- skipped count -->
			<?php if ($question['type'] != 'checkbox'): ?>
			<tr>
				<td>
					Skipped
				</td>
				<td>
					<?php 
							$skipped = $completed < 0 ? 0 : $completed - $answered;
							echo $skipped; 
						?>
						&nbsp;(<?php echo round($skipped / $completed * 100, 2); ?>%)
				</td>
			</tr>
			<?php endif ?>
		</tbody>
		</table>
	<?php } ?>
