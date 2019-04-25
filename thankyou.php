<?php 
include 'includes/db.php';
include 'functions/functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
    <script src="main.js"></script>
</head>
<body>
<div id="heading">
        <img src="images/banner.png" alt="banner">
    </div>
    <?php include('navbar.php');
    include('searchetc.php');?>
<script src="gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script src="gumby/js/libs/gumby.min.js"></script>

    <div id="wrapper">
    <div class="row" id="container">
      <div class="twelve columns">
    <h2>Thank You</h2>
    
          <center><strong>Your account has been created. In order to verify your account a confirmation link has been sent to your email.</strong></center>
    
    
    
</div>
</div></div>
<hr class="line">

<!--FOOTER-->
    <footer>
    <?php include('footer.php')?>
</footer>
    </body></html>