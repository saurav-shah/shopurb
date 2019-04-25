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
    <h2>Verification</h2>
    
          <center><strong>
              
              
              <?php
              
              
if(isset($_GET['vkey'])){
    $vkey = $_GET['vkey'];
    $sql = "select verified,vkey from users where verified = 0 and vkey = '$vkey'";
    $select = oci_parse($con, $sql);
    oci_execute($select);
    
    $num_rows = oci_fetch_all($select, $out);
    
    if($num_rows == 1){
        
      // validate the email  
        $sql2 = "update users set verified = 1 where vkey = '$vkey'";
        $prep = oci_parse($con, $sql2);
        $update = oci_execute($prep);
        
        if($update){
            echo "Your account is now verified. You may now <a href='login.php'>login.</a>";
        } else {
            echo "Error";
        }
        
    } else {
        echo 'This account is invalid or already verified';
    }
    
} else {
    
    die('Something went wrong');
    
    
}
              ?>
              
              
              
          </strong></center>
    
    
    
</div>
</div></div>
<hr class="line">

<!--FOOTER-->
    <footer>
    <?php include('footer.php')?>
</footer>
    </body></html>