<?php session_start();
if(!isset($_SESSION['cust_id'])){
    header('location: index.php');
    die();
}
include 'includes/db.php';
include 'functions/functions.php';

if(isset($_POST['submit'])){
    
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $id = $_SESSION['cust_id'];
    
    $sql = 'select firstname, lastname from users where user_id = '.$id;
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    $row = oci_fetch_assoc($prep);
    $fname = $row['FIRSTNAME'];
    $lname = $row['LASTNAME'];
    $name = $fname.' '.$lname;
    
    $sql = "insert into feedback values ($id,'$name','$subject','$message') ";
    $insert = oci_execute(oci_parse($con, $sql));
    
    if($insert){
        echo '<center class = "success alert">We have recieved your feedback. Thank you for taking the time to provide us your experience with our website!</center>';
        
    }
    else{
        echo '<center class = "danger alert">Something went wrong!</center>';
    }
}



?>
<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="main.js"></script>
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body>
<div id="heading">
        <img src="images/banner.png" alt="banner">
    </div>
<?php include('navbar.php');
    include('searchetc.php');?> 
<div id="wrapper">
    <div class="row">
    

        <form action="" method="post">
            <ul style="margin-top: 20px;">
                <li class="field">
                   <label  for="subj">Subject</label>
                    <input  id="subj" class="input wide" type="text" name="subject" required placeholder="Subject">
                </li>
                <li class="field" >
                   <label  for="m">Message</label>
                    <textarea rows="10" id="m" class="input textarea wide"  name="message" required placeholder="Message..."></textarea>
                </li>
                <li class="primary  pretty medium btn">
                    <input type="submit" value="Submit" name="submit">
                </li>
            </ul>
        </form>
    
    
    </div>
</div>

<hr class="line">
<footer>
<?php include('footer.php')?>
</footer>
</body>
</html>