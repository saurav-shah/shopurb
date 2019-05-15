<?php 
session_start();

if(!isset($_SESSION['cust_id'])) {
    header('location: index.php');
}

include 'includes/db.php';
include 'functions/functions.php';
require_once('PHPMailer/PHPMailerAutoload.php');
require_once('credential.php');
$error = null;
$format='/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
$c_id = $_SESSION['cust_id'];



   $sql = 'select * from users where user_id = '.$c_id.'';
          $prep = oci_parse($con, $sql);
          oci_execute($prep);         
          
          $row = oci_fetch_assoc($prep);
          
          $image = $row['PROFILE_PICTURE'];
          if($image == null) {
                $image = 'default.jpg';
          }
          $fname = $row['FIRSTNAME'];
          $lname = $row['LASTNAME'];
          $email = $row['EMAIL'];
          $username = $row['USERNAME'];
          $dob = strtotime($row['DOB']);
          $dob = date('Y/m/d',$dob);
          $addr = $row['ADDRESS'];
          $old_pic = $row['PROFILE_PICTURE'];
          $pass = $row['PASSWORD'];
          $e = $row['EMAIL'];
          $role = $row['ROLE'];
          



if(isset($_POST['update'])) {
   
    $f = $_POST['fname'];
    $l = $_POST['lname'];
    $a = $_POST['addr'];
    $current_pass = $_POST['current_pass'];
    $updated = false;
    
    if(md5($current_pass) == $pass) {
        
        if($fname != $f) {
            if(strpbrk($f, '1234567890')) {
                $error = 'The first name should not contain any numbers';
            }
            if($error == null) {
                //update fname
                $sql = "update users set firstname = '$f' where user_id = $c_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            } 
        }
        
        if($lname != $l) {
            
            if(strpbrk($l, '1234567890')) {
                $error = 'The last name should not contain any numbers';
            }
            if($error == null) {
                //update lname
                $sql = "update users set lastname = '$l' where user_id = $c_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            } 
        }
        
        if($a != $addr) {
            
            if($error == null) {
                //update addr
                $sql = "update users set address = '$a' where user_id = $c_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            } 
        }
        
        if(is_uploaded_file($_FILES['pic']['tmp_name'])) {
            
            $image = $_FILES['pic']['name'];    
            $image_temp = $_FILES['pic']['tmp_name'];    
            $check_image = getimagesize($_FILES["pic"]["tmp_name"]);
            
            if($check_image == 0) {
                $error = 'Invalid Image';
            }
            
            if($error == null) { 
                if($old_pic != null and $old_pic != 'default.jpg'){
                    unlink('profile_pics/'.$old_pic);
                }
                
                move_uploaded_file($image_temp,'profile_pics/'.$image);
                
                //update image
                $sql = "update users set profile_picture = '$image' where user_id = $c_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            }
            
        }
        
        if($_POST['new_pass'] != '') {
            
            if(!preg_match($format, $_POST['new_pass'])) {
                $error = "New password must contain a number, a special character, a capital letter and must be 8-20 characters long.";
            }
            if($error == null) {
                //update pass md5
                $p = md5($_POST['new_pass']);
                
                $sql = "update users set password = '$p' where user_id = $c_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            }
            
        }
        
    }
    else {
        $error = 'Incorrect password';
    }
    
    
    
    if($updated) {
        
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
            $mail->Subject = 'Profile Updated!';
            $mail->Body    = "
            
            <img style=\"display: block;\" src=\"https://2.bp.blogspot.com/-eeGplg5TLGE/XL_a8A4DoKI/AAAAAAAADVM/_0KAjIU3tcgKuUrM5ZYH_JhJyjBu08iLACLcBGAs/s320/logo.png\" width=\"200\" /><br>
            <strong>Dear $username,</strong><br>
            
            <p>Your profile details has been updated!</p><br>
            
            <p>Account Type: $role</p>
            
            <p>Status: Verified</p>
            
          
            
            ";            
            
        
            if(!$mail->send()) {
                
                echo 'Message could not be sent.';
                
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                
            } else {
                
                echo "<meta http-equiv='refresh' content='1'>";
                echo "<li class=\"success alert\">Profile Updated!</li>";
                
            }
        
    } else {
        if($error == null) {
            echo "<meta http-equiv='refresh' content='1'>";
                echo "<li class=\"warning alert\">Nothing to Update!</li>";
        }
        
    }
    
}
  

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
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
                <h2>Customer Profile</h2>
         

                <img src="profile_pics/<?=$image?>" alt="pic" width="100" height="100" style="border-radius:50%">


                <br><br>
                
                <form action="" method="post" enctype="multipart/form-data">

                    <table class="table rounded">

                       

                        <tr>
                            <td><label for="uname">Username: </label></td>
                            <td>

                                <div class="field">

                                    <input disabled id="uname" class="input wide" type="text" name="uname" value="<?=$username?>">

                                </div>
                            </td>

                        </tr>


                        <tr>

                            <td><label for="fname">First Name: </label></td>
                            <td>
                                <div class="field">

                                    <input required id="fname" class="input wide" type="text" name="fname" value="<?=$fname?>">
                                </div>
                            </td>
                        </tr>




                        <tr>

                            <td><label for="lname">Last Name: </label></td>
                            <td>
                                <div class="field">

                                    <input required id="lname" class="input wide" type="text" name="lname" value="<?=$lname?>">

                                </div>
                            </td>

                        </tr>


                        <tr>


                            <td><label for="email">Email: </label></td>
                            <td>
                                <div class="field">

                                    <input disabled id="email" class="input wide" type="email" name="email" value="<?=$email?>">
                                </div>
                            </td>
                        </tr>


                        <tr>

                            <td><label for="addr">Address: </label></td>
                            <td>
                                <div class="field">

                                    <input required id="addr" class="input wide" type="text" name="addr" value="<?=$addr?>">
                                </div>
                            </td>
                        </tr>



                        <tr>

                            <td><label for="dob">DOB: </label></td>
                            <td>
                                <div class="field">

                                    <input id="dob" disabled class="input wide" type="text" value="<?=$dob?>">
                                </div>
                            </td>
                        </tr>

                        
                        <tr>
                            <td><label for="image">Profile Pic: </label></td>
                            <td>
                                
                                <div class="field">
                                    <input type="file" name="pic" class="input wide">
                                    <em style="color: grey;">Optional</em>
                                </div>
                                
                            </td>
                            
                        </tr>
                        
                        <tr>
                            <td><label for="pass">Change Password: </label></td>
                            <td>
                                <div class="field">
                                    <input type="password" name="new_pass" class="input wide" placeholder="New Password"> <em style="color: grey;">Optional</em>
                                </div>
                            </td>
                        </tr>
                        <tr><td colspan="2">Enter Your Password Below to Update your Profile:</td></tr>
                        <tr>
                            <td colspan="2">
                               <div class="field">
                                <input type="password" name="current_pass" required placeholder="Password" class="input narrow">
                               </div>
                            </td>
                            
                        </tr>
                       
                       
                      
                       
                    </table>





                    <div class="primary btn medium"><input type="submit" name="update" value="Update"></div>




                </form>
                
                <center style="color:red;"><?php echo $error; ?></center><br><br>
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

