<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<?php if ($submitted==1): ?>
			<h3>Thanks for completing the survey!</h3>
		<?php endif ?>
		<?php try { ?>
			
		<?php } catch (Exception $e) { } ?>
		<form id="survey_form" action="<?php echo base_url() . "survey/submitSurvey"; ?>" accept-charset="utf-8" method="POST">
			<label for="q0">1. Gender</label>
			<input type="radio" name="q0" value="0" <?php if(!empty($completed_survey) && $completed_survey->q0=='0') echo 'checked="checked"' ?>>Male <br>
			<input type="radio" name="q0" value="1" <?php if(!empty($completed_survey) && $completed_survey->q0=='1') echo 'checked="checked"' ?>>Female <br>
			<br>
			<label for="q1">2. How would you categorise your physical activity level before the HEP?</label>
			<input type="radio" name="q1" value="0" <?php if(!empty($completed_survey) && $completed_survey->q1=='0') echo 'checked="checked"' ?>>None <br>
			<input type="radio" name="q1" value="1" <?php if(!empty($completed_survey) && $completed_survey->q1=='1') echo 'checked="checked"' ?>>Low (once a week)  <br>
			<input type="radio" name="q1" value="2" <?php if(!empty($completed_survey) && $completed_survey->q1=='2') echo 'checked="checked"' ?>>Moderate (3 times a week) <br>
			<input type="radio" name="q1" value="3" <?php if(!empty($completed_survey) && $completed_survey->q1=='3') echo 'checked="checked"' ?>>Very high (athletes and intensive sports training for competition, i.e. high intensity-high frequency) <br>
			<br>
			<label for="q2">3. The ability to track my daily activity using a wireless device (FitBit) changed my perception about my level of daily physical activity?</label>
			<input type="radio" name="q2" value="0" <?php if(!empty($completed_survey) && $completed_survey->q2=='0') echo 'checked="checked"' ?>>Strong disagree 
			<input type="radio" name="q2" value="1" <?php if(!empty($completed_survey) && $completed_survey->q2=='1') echo 'checked="checked"' ?>>Disagree 
			<input type="radio" name="q2" value="2" <?php if(!empty($completed_survey) && $completed_survey->q2=='2') echo 'checked="checked"' ?>>Neutral 
			<input type="radio" name="q2" value="3" <?php if(!empty($completed_survey) && $completed_survey->q2=='3') echo 'checked="checked"' ?>>Agree 
			<input type="radio" name="q2" value="4" <?php if(!empty($completed_survey) && $completed_survey->q2=='4') echo 'checked="checked"' ?>>Strongly agree <br>
			<br>
			<label for="q3">4. The ability to track my sleep using a wireless device (FitBit) changed the perception of my sleep (patterns and quantity) </label>
			<input type="radio" name="q3" value="0" <?php if(!empty($completed_survey) && $completed_survey->q3=='0') echo 'checked="checked"' ?>>Strong disagree
			<input type="radio" name="q3" value="1" <?php if(!empty($completed_survey) && $completed_survey->q3=='1') echo 'checked="checked"' ?>>Disagree 
			<input type="radio" name="q3" value="2" <?php if(!empty($completed_survey) && $completed_survey->q3=='2') echo 'checked="checked"' ?>>Neutral 
			<input type="radio" name="q3" value="3" <?php if(!empty($completed_survey) && $completed_survey->q3=='3') echo 'checked="checked"' ?>>Agree 
			<input type="radio" name="q3" value="4" <?php if(!empty($completed_survey) && $completed_survey->q3=='4') echo 'checked="checked"' ?>>Strongly agree <br>
			<br>
			<label for="q4">5. Please tell us about what you liked about your experience of tracking sleep with the FitBit One.</label>
			<textarea name="q4" id="q4" class="span12"rows="10"><?php if(!empty($completed_survey) && $completed_survey->q4) echo $completed_survey->q4 ?></textarea>
			<br>
			<br>
			<label for="q5">6. Please tell us about what you disliked about your experience of tracking sleep with the FitBit One.</label>
			<textarea name="q5" id="q5" class="span12"rows="10"><?php if(!empty($completed_survey) && $completed_survey->q5) echo $completed_survey->q5 ?></textarea>
			<br>
			<br>
			<label for="q6">7. Please tell us about what you liked about your experience of tracking your daily activity with the FitBit One.</label>
			<textarea name="q6" id="q6" class="span12"rows="10"><?php if(!empty($completed_survey) && $completed_survey->q6) echo $completed_survey->q6 ?></textarea>
			<br>
			<br>
			<label for="q7">8. Please tell us about what you disliked about your experience of tracking your daily activity with the FitBit One.</label>
			<textarea name="q7" id="q7" class="span12"rows="10"><?php if(!empty($completed_survey) && $completed_survey->q7) echo $completed_survey->q7 ?></textarea>
			<br>
			<br>
			<label for="q8">9. The introduction of the challenges motivated me to track my activity and sleep. </label>
			<input type="radio" name="q8" value="0" <?php if(!empty($completed_survey) && $completed_survey->q8=="0") echo 'checked="checked"' ?>>Strong disagree 
			<input type="radio" name="q8" value="1" <?php if(!empty($completed_survey) && $completed_survey->q8=="1") echo 'checked="checked"' ?>>Disagree 
			<input type="radio" name="q8" value="2" <?php if(!empty($completed_survey) && $completed_survey->q8=="2") echo 'checked="checked"' ?>>Neutral 
			<input type="radio" name="q8" value="3" <?php if(!empty($completed_survey) && $completed_survey->q8=="3") echo 'checked="checked"' ?>>Agree 
			<input type="radio" name="q8" value="4" <?php if(!empty($completed_survey) && $completed_survey->q8=="4") echo 'checked="checked"' ?>>Strongly agree <br>
			<br>
			<label for="q9">10. What are your views about the length of the HEP Challenge?</label>
			<textarea name="q9" id="q9" class="span12"rows="10"><?php if(!empty($completed_survey) && $completed_survey->q9) echo $completed_survey->q9 ?></textarea>
			<br>
			<br>
			<label for="q10">11. Did you give up on the HEP Challenge after a while?</label>
			<input type="radio" name="q10" value="0" <?php if(!empty($completed_survey) && $completed_survey->q10=="0") echo 'checked="checked"' ?>>Yes <br>
			<input type="radio" name="q10" value="1" <?php if(!empty($completed_survey) && $completed_survey->q10=="1") echo 'checked="checked"' ?>>No <br>
			<br>
			<label for="q10yes">If yes, please share with us the reason why you did not finish the Challenge</label>
			<textarea name="q10yes" id="q10yes" class="span12" rows="10"><?php if(!empty($completed_survey) && $completed_survey->q10yes) echo $completed_survey->q10yes ?></textarea>
			<br>
			<br>
			<label for="q11">12. Please tell us about what you liked about the HEP Challenge</label>
			<textarea name="q11" id="q11" class="span12" rows="10"><?php if(!empty($completed_survey) && $completed_survey->q11) echo $completed_survey->q11 ?></textarea>
			<br>
			<br>
			<label for="q12">13. Please tell us about what you disliked about the HEP Challenge</label>
			<textarea name="q12" id="q12" class="span12" rows="10"><?php if(!empty($completed_survey) && $completed_survey->q12) echo $completed_survey->q12 ?></textarea>
			<br>
			<br>
			<label for="q13">14. Please suggest how we can improve the HEP Challenge in a future offering of the challenge</label>
			<textarea name="q13" id="q13" class="span12" rows="10"><?php if(!empty($completed_survey) && $completed_survey->q13) echo $completed_survey->q13 ?></textarea>
			<br>
			<br>
			<label for="q14">15. Monitoring my daily activity and sleep pattern is now part of my everyday routine.</label>
			<input type="radio" name="q14" value="0" <?php if(!empty($completed_survey) && $completed_survey->q14=="0") echo 'checked="checked"' ?>>Strong disagree 
			<input type="radio" name="q14" value="1" <?php if(!empty($completed_survey) && $completed_survey->q14=="1") echo 'checked="checked"' ?>>Disagree 
			<input type="radio" name="q14" value="2" <?php if(!empty($completed_survey) && $completed_survey->q14=="2") echo 'checked="checked"' ?>>Neutral 
			<input type="radio" name="q14" value="3" <?php if(!empty($completed_survey) && $completed_survey->q14=="3") echo 'checked="checked"' ?>>Agree 
			<input type="radio" name="q14" value="4" <?php if(!empty($completed_survey) && $completed_survey->q14=="4") echo 'checked="checked"' ?>>Strongly agree <br>
			<br>
			<br>
			<input type="submit" class="btn" value="Submit">
		</form>			
	</div>
</div>