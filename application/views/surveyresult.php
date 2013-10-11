<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
		<?php for($i=1; $i<=10; $i++): ?>
			<li class="<?php if($house_id==$i) echo "active" ?>">
				<a href="<?php echo base_url() . "surveyresult/house/".$i; ?>"><?php echo $i ?></a>
			</li>
		<?php endfor ?>
		<li class="<?php if($house_id==-1) echo "active" ?>">
			<a href="<?php echo base_url() . "surveyresult/house/-1"; ?>">Tutor</a>
		</li>
		<li class="<?php if($house_id=="all") echo "active" ?>">
			<a href="<?php echo base_url() . "surveyresult/all"; ?>">Overall</a>
		</li>
		<li class="<?php if($house_id=="count") echo "active" ?>">
			<a href="<?php echo base_url() . "surveyresult/count"; ?>">Name List</a>
		</li>
		</ul>
		<?php if ($house_id=="count"): ?>
			<?php if ($not_complete): ?>
				<h4>Students who have not completed the survey yet.</h4>
				<table class="table">
					<tbody>
						<?php foreach ($not_complete as $person): ?>
							<tr>
								<td><strong><?php echo $person->first_name . " " . $person->last_name ?></strong></td>
								<td><a href="mailto:<?php echo $person->email ?>"><?php echo $person->email ?></a></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
			<?php if ($complete): ?>
				<h4>Students who have completed the survey.</h4>
				<table class="table">
					<tbody>
						<?php foreach ($complete as $person): ?>
							<tr>
								<td><strong><?php echo $person->first_name . " " . $person->last_name ?></strong></td>
								<td><a href="mailto:<?php echo $person->email ?>"><?php echo $person->email ?></a></td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		<?php endif ?>
		<?php if (!empty($surveyResult)): ?>
		<ul class="survey-result">
				<li>
					<ol>
						<li>
							<h5>Gender</h5>
							<?php $q0_0 = 0; $q0_1 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q0=="0"): ?>
									<?php $q0_0 += 1; ?>
								<?php else: ?>
									<?php $q0_1 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Male</td>
										<td><?php echo $q0_0; ?><br>(<?php echo round($q0_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Female</td>
										<td><?php echo $q0_1; ?><br>(<?php echo round($q0_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li>
							<h5>How would you categorise your physical activity level before the HEP?</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q1=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q1 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php elseif($result->q1 =="2"): ?>
									<?php $q1_2 += 1; ?>
								<?php elseif($result->q1 =="3"): ?>
									<?php $q1_3 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>None</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Low (once a week)</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Moderate (3 times a week)</td>
										<td><?php echo $q1_2; ?><br>(<?php echo round($q1_2 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Very high (athletes and intensive sports training for competition, i.e. high intensity-high frequency)</td>
										<td><?php echo $q1_3; ?><br>(<?php echo round($q1_3 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li>
							<h5>The ability to track my daily activity using a wireless device (FitBit) changed my perception about my level of daily physical activity?</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = $q1_4 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q2=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q2 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php elseif($result->q2 =="2"): ?>
									<?php $q1_2 += 1; ?>
								<?php elseif($result->q2 =="3"): ?>
									<?php $q1_3 += 1; ?>
								<?php elseif($result->q2 =="4"): ?>
									<?php $q1_4 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Strong disagree</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Disagree</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Neutral</td>
										<td><?php echo $q1_2; ?><br>(<?php echo round($q1_2 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Agree</td>
										<td><?php echo $q1_3; ?><br>(<?php echo round($q1_3 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Strongly agree</td>
										<td><?php echo $q1_4; ?><br>(<?php echo round($q1_4 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li>
							<h5>The ability to track my sleep using a wireless device (FitBit) changed the perception of my sleep (patterns and quantity)</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = $q1_4 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q3=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q3 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php elseif($result->q3 =="2"): ?>
									<?php $q1_2 += 1; ?>
								<?php elseif($result->q3 =="3"): ?>
									<?php $q1_3 += 1; ?>
								<?php elseif($result->q3 =="4"): ?>
									<?php $q1_4 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Strong disagree</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Disagree</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Neutral</td>
										<td><?php echo $q1_2; ?><br>(<?php echo round($q1_2 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Agree</td>
										<td><?php echo $q1_3; ?><br>(<?php echo round($q1_3 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Strongly agree</td>
										<td><?php echo $q1_4; ?><br>(<?php echo round($q1_4 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li>
							<h5>Please tell us about what you liked about your experience of tracking sleep with the FitBit One.</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q4 ?></li>
								<?php endforeach ?>
							</ul>
							
						</li>
						<li>
							<h5>Please tell us about what you disliked about your experience of tracking sleep with the FitBit One.</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q5 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Please tell us about what you liked about your experience of tracking your daily activity with the FitBit One.</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q6 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Please tell us about what you disliked about your experience of tracking your daily activity with the FitBit One.</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q7 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>The introduction of the challenges motivated me to track my activity and sleep.</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = $q1_4 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q8=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q8 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php elseif($result->q8 =="2"): ?>
									<?php $q1_2 += 1; ?>
								<?php elseif($result->q8 =="3"): ?>
									<?php $q1_3 += 1; ?>
								<?php elseif($result->q8 =="4"): ?>
									<?php $q1_4 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Strong disagree</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Disagree</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Neutral</td>
										<td><?php echo $q1_2; ?><br>(<?php echo round($q1_2 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Agree</td>
										<td><?php echo $q1_3; ?><br>(<?php echo round($q1_3 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Strongly agree</td>
										<td><?php echo $q1_4; ?><br>(<?php echo round($q1_4 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li>
							<h5>What are your views about the length of the HEP Challenge?</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q9 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Did you give up on the HEP Challenge after a while?</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = $q1_4 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q10=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q10 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Yes</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>No</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
							<h5>If yes, please share with us the reason why you did not finish the Challenge</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q10yes ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Please tell us about what you liked about the HEP Challenge</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q11?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Please tell us about what you disliked about the HEP Challenge</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q12 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Please suggest how we can improve the HEP Challenge in a future offering of the challenge</h5>
							<ul>
								<?php foreach ($surveyResult as $result): ?>
									<li><?php echo $result->q13 ?></li>
								<?php endforeach ?>
							</ul>
						</li>
						<li>
							<h5>Monitoring my daily activity and sleep pattern is now part of my everyday routine.</h5>
							<?php $q1_0 = $q1_1 = $q1_2 = $q1_3 = $q1_4 = 0; ?>
							<?php foreach ($surveyResult as $result): ?>
								<?php if ($result->q14=="0"): ?>
									<?php $q1_0 += 1; ?>
								<?php elseif($result->q14 =="1"): ?>
									<?php $q1_1 += 1; ?>
								<?php elseif($result->q14 =="2"): ?>
									<?php $q1_2 += 1; ?>
								<?php elseif($result->q14 =="3"): ?>
									<?php $q1_3 += 1; ?>
								<?php elseif($result->q14 =="4"): ?>
									<?php $q1_4 += 1; ?>
								<?php endif ?>
							<?php endforeach ?>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="span8">Response</td>
										<td>Percentage</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Strong disagree</td>
										<td><?php echo $q1_0; ?><br>(<?php echo round($q1_0 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Disagree</td>
										<td><?php echo $q1_1; ?><br>(<?php echo round($q1_1 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Neutral</td>
										<td><?php echo $q1_2; ?><br>(<?php echo round($q1_2 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Agree</td>
										<td><?php echo $q1_3; ?><br>(<?php echo round($q1_3 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
									<tr>
										<td>Strongly agree</td>
										<td><?php echo $q1_4; ?><br>(<?php echo round($q1_4 / count($surveyResult) * 100.0, 1); ?>%)</td>
									</tr>
								</tbody>
							</table>
						</li>
					</ol>
				</li>
		</ul>			
		<?php endif ?>
	</div>
</div>