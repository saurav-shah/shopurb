<?php
session_start();
require_once('PHPMailer/PHPMailerAutoload.php');
require_once('credential.php');
include 'includes/db.php';
include 'functions/functions.php';
$error = null;

if(isset($_SESSION['cust_id'])) {
    header('location: index.php');
    
}
else {
    
    
if(isset($_POST['register'])) {
    
    $image = $_FILES['pic']['name'];    
    $image_temp = $_FILES['pic']['tmp_name'];    
    $check_image = getimagesize($_FILES["pic"]["tmp_name"]);
    
    $u = $_POST['username'];
    $f = $_POST['fname'];
    $e = $_POST['email'];
    $l = $_POST['lname'];
    $dob = $_POST['dob'];
    $addr = $_POST['address'];
    $p1 = $_POST['pass1'];
    $p2 = $_POST['pass2'];
    $format='/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
    
    
    $sql = "select * from users where username = '$u'";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    $num_rows = oci_fetch_all($prep, $out);
    
    $sql = "select * from users where email = '$e'";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    $email_count = oci_fetch_all($prep, $out);
        
    if($num_rows != 0) {
        $error = '<p>Username is taken. Choose another one.</p>';
            
    }  
    elseif(strlen($u) < 5){
        
        $error = "<p>Your username must be at least 5 characters</p>";
        
    } 
    elseif(strpbrk($f, '1234567890')) {
        $error = 'The first name should not contain any numbers';
    }
    elseif(strpbrk($l, '1234567890')) {
        $error = 'The last name should not contain any numbers';
    }
    elseif($email_count != 0) {
        $error = 'An account already exists with the email address you provided';
    }
    
    elseif ($check_image == 0){
        $error = 'Please Upload a Valid Image';
    }
    elseif ($p1 != $p2) {
        $error = 'The two passwords do not match';
    }
    elseif(!preg_match($format, $p1)) { 
            $error = "Password must contain a number, a special character, a capital letter and must be 8-20 characters long.";
 
    }
    else {
        
        // form is valid
        $role = 'Customer';
        $vKey = md5(time().$u); // Generating a Verification Code
        $p = md5($p1); // Encrypting Password
        
        // inserting into database
        
        move_uploaded_file($image_temp,'profile_pics/'.$image);
        $sql = "insert into users (user_id, vKey, username, firstname, lastname, password, email, dob, address, role, profile_picture) values (user_id.nextval, '$vKey', '$u', '$f', '$l', '$p', '$e', to_date('$dob','yyyy-mm-dd'), '$addr', '$role', '$image')";
           
        
        $insert = oci_parse($con, $sql);
        
        oci_execute($insert);
        
        if($insert){
            //Sending Email
            $mail = new PHPMailer;
            //$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP();                                      
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;                               
            $mail->Username = EMAIL;                
            $mail->Password = PASS;                           
            $mail->SMTPSecure = 'tls';                          
            $mail->Port = 587;  
            $mail->setFrom(EMAIL, 'Shopurb Registration');
            $mail->addAddress("$e");                           
            $mail->addReplyTo(EMAIL); 
            $mail->isHTML(true); 
            $mail->Subject = 'Shopurb Registration';
            $mail->Body    = "
            
            <img style=\"display: block;\" src=\"https://2.bp.blogspot.com/-eeGplg5TLGE/XL_a8A4DoKI/AAAAAAAADVM/_0KAjIU3tcgKuUrM5ZYH_JhJyjBu08iLACLcBGAs/s320/logo.png\" width=\"200\" /><br>
            <strong>Dear $u,</strong><br>
            <p>Thank You for Registering with Shopurb.</p>
            
            <p>Account Type: $role</p>
            
            <p>Status: Pending Verification </p>
            
            <p>Click the link to below to verify your account.</p> <br>
            
            <div><button style=\"background-color:green; color:white; padding: 5px 50px; font-size: 1.5em\"><a href=\"http://localhost/projects/PM/verify.php?vkey=$vKey\" style=\"color:white; text-decoration:none; \">Verify</a></button></div>
            
            ";            
            
        
            if(!$mail->send()) {
                
                echo 'Message could not be sent.';
                
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                
            } else { 
                
                header ('location: thankyou.php');
                
            }            
           
        }
        
        
        
        else{
            echo 'something wrong';
            
            $m = oci_error();
            
            echo $m['message'], "\n";
            
            exit;
            
            
        }
        
    }
    
    
}


    
}


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
                <h2>Customer Registration</h2>
                <!--Register Form-->
                <form method="POST" enctype="multipart/form-data" action="">

                    <ul style="text-align:center;">

                        <li class="field">
                            <input type="text" class="wide input" placeholder="Username" name="username" required value="<?php if(isset($_POST['username'])) echo $_POST['username'];?>">
                        </li>

                        <li class="field">
                            <input type="text" class="wide input" placeholder="First Name" name="fname" required value="<?php if(isset($_POST['fname'])) echo $_POST['fname'];?>">
                        </li>

                        <li class="field">
                            <input type="text" class="wide input" placeholder="Last Name" name="lname" required value="<?php if(isset($_POST['lname'])) echo $_POST['lname'];?>">
                        </li>

                        <label for="dob" style="text-align:left; margin-left:165px;">Date of Birth</label>
                        <li class="field" id="dob">
                            <input type="date" class="wide input" name="dob" required value="<?php if(isset($_POST['dob'])) echo $_POST['dob'];?>">
                        </li>

                        <li class="field">
                            <input type="text" class="wide input" placeholder="Address" name="address" required value="<?php if(isset($_POST['address'])) echo $_POST['address'];?>">
                        </li>

                        <li class="field">
                            <input type="email" class="wide input" placeholder="Email" name="email" required value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
                        </li>
                        
                        <label for="pic" style="text-align:left; margin-left:165px;">Profile Image</label>
                        <li class="field">
                            <input id="pic" type="file" class="input wide" name="pic" required value="<?php if(isset($_POST['pic'])) echo $_POST['pic'];?>">
                        </li>

                        <li class="field">
                            <input type="password" class="wide input" name="pass1" placeholder="Password" required value="<?php if(isset($_POST['pass1'])) echo $_POST['pass1'];?>">
                        </li>

                        <li class="field">
                            <input type="password" class="wide input" name="pass2" placeholder="Confirm Password" required value="<?php if(isset($_POST['pass2'])) echo $_POST['pass2'];?>">
                        </li>

                        <div class="medium primary btn">
                            <input type="submit" value="Register" name="register">
                        </div>
                    </ul>






                </form>
                <center style="color:red;"><?php echo $error; ?></center>
            </div>
        </div>
    </div>
    <hr class="line">
    <!--FOOTER-->
    <footer>
        <?php include('footer.php')?>
    </footer>
</body>

</html>
