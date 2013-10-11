<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ol class="surveyresult">
			<li><strong>Gender</strong><br>
			<?php if ($q0=='0'): ?>
			Male
				<?php else: ?>
			Female
			<?php endif ?>
			
			</li>
			<li><strong>How would you categorise your physical activity level before the HEP?</strong><br>
			<?php switch ($q1) {
				case '0':
					# code...
				echo "None";
					break;
				case '1':
				echo "Low (once a week)";
					break;
				case '2':
				echo "Moderate (3 times a week)";
					break;
				case '3':
				echo "Very high (athletes and intensive sports training for competition, i.e. high intensity-high frequency)";
					break;
				default:
					# code...
					break;
			} ?>
						
			</li>
			<li><strong>The ability to track my daily activity using a wireless device (FitBit) changed my perception about my level of daily physical activity?</strong><br>
			<?php switch ($q2) {
					case '0':
						# code...
					echo "Strong disagree";
						break;
					case '1':
					echo "Disagree";
						break;
					case '2':
					echo "Neutral";
						break;
					case '3':
					echo "Agree";
						break;
					case '4':
					echo "Strongly agree";
					break;
					default:
						# code...
						break;
				} ?>
				
			</li>
			<li><strong>The ability to track my sleep using a wireless device (FitBit) changed the perception of my sleep (patterns and quantity)</strong><br>
				<?php switch ($q3) {
					case '0':
						# code...
					echo "Strong disagree";
						break;
					case '1':
					echo "Disagree";
						break;
					case '2':
					echo "Neutral";
						break;
					case '3':
					echo "Agree";
						break;
					case '4':
					echo "Strongly agree";
					break;
					default:
						# code...
						break;
				} ?>
				
			</li>
			<li><strong>Please tell us about what you liked about your experience of tracking sleep with the FitBit One.</strong><br>
				<p><?php echo htmlspecialchars($q4) ?></p>
				
			</li>
			<li><strong>Please tell us about what you disliked about your experience of tracking sleep with the FitBit One.</strong><br>
				<p><?php echo htmlspecialchars($q5) ?></p>
				
			</li>
			<li><strong>Please tell us about what you liked about your experience of tracking your daily activity with the FitBit One.</strong><br>
				<p><?php echo htmlspecialchars($q6) ?></p>
				
			</li>
			<li><strong>Please tell us about what you disliked about your experience of tracking your daily activity with the FitBit One.</strong><br>
				<p><?php echo htmlspecialchars($q7) ?></p>
				
			</li>
			<li><strong>The introduction of the challenges motivated me to track my activity and sleep.</strong><br>
				<?php switch ($q8) {
					case '0':
						# code...
					echo "Strong disagree";
						break;
					case '1':
					echo "Disagree";
						break;
					case '2':
					echo "Neutral";
						break;
					case '3':
					echo "Agree";
						break;
					case '4':
					echo "Strongly agree";
					break;
					default:
						# code...
						break;
				} ?>
				
			</li>
			<li><strong>What are your views about the length of the HEP Challenge?</strong><br>
				<p><?php echo htmlspecialchars($q9) ?></p>
			</li>
			<li><strong>Did you give up on the HEP Challenge after a while?</strong><br>
			<?php if ($q10=='0'): ?>
			Yes
				<?php else: ?>
			No
			<?php endif ?>	
			<br>
			<br>
			
			<strong>If yes, please share with us the reason why you did not finish the Challenge</strong><br>
				<?php echo htmlspecialchars($q10yes) ?>	
				

			</li>
			<li><strong>Please tell us about what you liked about the HEP Challenge</strong><br>
				<?php echo htmlspecialchars($q11) ?>
				
			</li>
			<li><strong>Please tell us about what you disliked about the HEP Challenge</strong><br>
				<?php echo htmlspecialchars($q12); ?>
				
			</li>
			<li><strong>Please suggest how we can improve the HEP Challenge in a future offering of the challenge</strong><br>
				<?php echo htmlspecialchars($q13) ?>
				
			</li>

		</ol>
	</div>
</div>