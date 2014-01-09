<div class="row-fluid">
  <?php $this->load->view('templates/sidebar'); ?>
  <div class="span10">
    <div class="row-fluid">
      <h3 class="maintitle">HEP Challenge 2014 Rules</h3>
      <ol>
        <li><p>Official Period of contest: ? March 2014 00:00 -- ? May 2014 23:59</p></li>
        <li><p>Every week on Sunday 23:59, the top ranking houses in terms of steps and sleep 
        for that week will receive <strong><?php echo WEEKLY_POINT_MAX ?></strong> points each. All other
        houses will receive a fraction<sup><a href="#footnote1" data-toggle="modal">?</a></sup>
        of that amount based on their progress in steps and sleep clocked 
        respectively. Maximum points attainable is
        <?php echo WEEKLY_POINT_MAX ?>&nbsp;+&nbsp;<?php echo WEEKLY_POINT_MAX ?>&nbsp;=&nbsp;<strong><?php echo 2*WEEKLY_POINT_MAX ?></strong>
        per week.</p>
        
        <li><p> Weekly progress is calculated from total steps per person and number of hours of sleep per person to accomodate differences in 
        house sizes.</p></li>
        <li><p>To help the houses that are falling behind to catch up, a certain 
        formula<sup><a href="#footnote2" data-toggle="modal">?</a></sup> will
        be used to calculate a multiplier for each house to boost their progress.</p>
        <p>For example,
        a house with a step multiplier of 120% that would normally receive 40 points from steps, will earn 
        40&nbsp;&times;&nbsp;120%&nbsp;=&nbsp;<strong>48</strong> points instead, subject to a cap of
        <strong><?php echo WEEKLY_POINT_MAX ?></strong> points.</p></li>
        <li><p>House members who opt out of the contest temporarily for legitimate reasons will have their
contributions weighted according to the period of participation. </p></li>
        <li><p>Contestants can opt out of the contest permanently or temporarily. For example,
because of illness or because of a misplaced FitBit. They are to inform their House
reps, who will in turn inform the contest organizers. </p></li>
<h5>House Contest</h5>
        <li><p>The winning House will be the one which has the highest points accrued at the end
        of the contest.
        </p></li>
      </ol>
    </div>  
    <div id="footnote1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="footnote1Header" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="footnote1Header">Scoring</h3>
    </div>
    <div class="modal-body">
      <p>For example, the record for steps and hours of sleep for a given week are 200000 steps and 50 hours respectively.
        A house that has 100000 steps and 40 hours of sleep will earn 
        100000&nbsp;/&nbsp;200000&nbsp;&times;&nbsp;<?php echo WEEKLY_POINT_MAX ?>&nbsp;+&nbsp;40&nbsp;/&nbsp;50&nbsp;&times;&nbsp;<?php echo WEEKLY_POINT_MAX ?>&nbsp;=&nbsp;65 points.</p></li>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>

    <div id="footnote2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="footnote2Header" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      <h3 id="footnote2Header">Multiplier</h3>
    </div>
    <div class="modal-body">
      <p>Multipliers are calculated separately for steps and sleep.</p>
      <p>The value is determined by the formula: 200%&nbsp;-&nbsp;<code style="color: black">last_week_my_house_performance</code>&nbsp;/&nbsp;<code style="color: black">last_week_best_record</code></p>
      <p>For example, last week's steps record was held by House 1 with 200000 steps per person. A house that clocked 100000 steps
      will receive a multiplier of 150%.</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>

  </div>
  </div>
</div>