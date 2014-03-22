<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  </head>
  <body>
<div class="wrapper welcome">
    <div class="container">
      <h3 class="maintitle">Health Enhancement Programme</h3>
      
      <div class="logincontrol well">
        <p class="text-info text-center">Thank you for registering with us.</p>
        <p class="text-info text-center">As part of the research arrangement, only 100 students can access the platform at this moment.</p>
        <p class="text-info text-center">You will be notified when you can access the system in March.</p>
        <br/>
        <p class="text-center"><i class="icon-off"></i> <a href="<?php echo base_url() . 'logout' ?>">Logout</a></p>
      </div>
    </div>  
     <div>
      <p class='text-center'>
        Total <?php echo $summary->steps ?>K steps, <?php echo $summary->distance ?> kilometers,
        <?php echo $summary->sleep ?>K hours of sleep recorded in the system.
      </p>
      </div>
</div>
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.9.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>