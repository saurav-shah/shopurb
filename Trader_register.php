<?php
session_start();
require_once('PHPMailer/PHPMailerAutoload.php');
require_once('credential.php');
include 'includes/db.php';
include 'functions/functions.php';
$error = null;

if(isset($_SESSION['trader_id'])) {
    header('location: index.php');
    
}
else {
    
    
if(isset($_POST['register'])) {
    $check_image = 1; // to skip the validation at line 86 if the image was not uploaded
    $uploaded = false;
    if(is_uploaded_file($_FILES['pic']['tmp_name'])){
        $image = $_FILES['pic']['name'];    
        $image_temp = $_FILES['pic']['tmp_name'];    
        $check_image = getimagesize($_FILES["pic"]["tmp_name"]);
        $uploaded = true;
    }
    
    
    $phone =$_POST['phone'];
    $p_line = $_POST['p_line'];
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
    
    $sql = "select * from users where email = '$e' and role = 'Trader'";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    $email_count = oci_fetch_all($prep, $out);
    
    $sql = "select * from users where product_line = '$p_line'";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    $p_line_count = oci_fetch_all($prep, $out);
    
    $sql = "select count(*) from users where role = 'Trader'";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    oci_fetch($prep);	
	$trader_count = oci_result($prep,'COUNT(*)');
    
    if($trader_count >= 10) {
        $error = '<p>You cannot create any more trader accounts at the moment. Please try again in future!</p>';
            
    }      
    else if($num_rows != 0) {
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
    elseif($p_line_count != 0) {
        $error = 'Product Line is taken. Please choose another one!';
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
        $role = 'Trader';
        $vKey = md5(time().$u); // Generating a Verification Code
        $p = md5($p1); // Encrypting Password
        
        // inserting into database
        
         if($uploaded){
            move_uploaded_file($image_temp,'profile_pics/'.$image);
        }
        else{
            $image = null;
        }
        
        $sql = "insert into users (user_id, vKey, username, firstname, lastname, password, email, dob, address, role, profile_picture, phone, product_line) values (user_id.nextval, '$vKey', '$u', '$f', '$l', '$p', '$e', to_date('$dob','yyyy-mm-dd'), '$addr', '$role', '$image','$phone','$p_line')";
           
        
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
            $mail->setFrom(EMAIL, 'Shopurb');
            $mail->addAddress("$e");                           
            $mail->addReplyTo(EMAIL); 
            $mail->isHTML(true); 
            $mail->Subject = 'Shopurb Registration';
            $mail->Body    = "
            
            <img style=\"display: block;\" src=\"https://2.bp.blogspot.com/-eeGplg5TLGE/XL_a8A4DoKI/AAAAAAAADVM/_0KAjIU3tcgKuUrM5ZYH_JhJyjBu08iLACLcBGAs/s320/logo.png\" width=\"200\" /><br>
            <strong>Dear $f $l,</strong><br>
            <p>Thank You for Registering with Shopurb.</p>
            
            <p><em>Username:</em> $u</p>
            <p><em>Password:</em> $p1</p>
            <p>Account Type: $role</p>
            
            <p>Status: Pending Verification </p>
            <p>You can log in to your dashboard and access database with above credentials once your account is verified.</p>
            
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
    <title>Trader Registration</title>
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
                <h2>Trader Registration</h2>
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

                        <label for="pic" style="text-align:left; margin-left:165px;">Profile Image</label>
                        <li class="field">
                            <input id="pic" type="file" class="input wide" name="pic" value="<?php if(isset($_POST['pic'])) echo $_POST['pic'];?>">
                        </li>

                        <li class="field">
                            <input type="email" class="wide input" placeholder="Email" name="email" required value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
                        </li>

                        <li class="field">
                            <input type="tel" class="wide input" placeholder="Phone" name="phone" required value="<?php if(isset($_POST['phone'])) echo $_POST['phone'];?>">
                        </li>

                        <li class="field">
                            <input type="text" class="wide input" placeholder="Product Line" name="p_line" required value="<?php if(isset($_POST['p_line'])) echo $_POST['p_line'];?>">
                        </li>

                        <li class="field">
                            <input type="password" class="wide input" name="pass1" placeholder="Password" required>
                        </li>

                        <li class="field">
                            <input type="password" class="wide input" name="pass2" placeholder="Confirm Password" required >
                        </li>


                        <br><br>
                        <input type="checkbox" required> <span>I agree with the terms and conditions.</span>
                        <br><br>
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
