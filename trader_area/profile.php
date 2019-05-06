<?php 
include 'includes/db.php';
include 'functions/functions.php'; 
require_once('../PHPMailer/PHPMailerAutoload.php');
require_once('../credential.php');

$format='/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
$error = null;
$updated = false;


if(isset($_POST['update'])) {
   
    $f = $_POST['fname'];
    $l = $_POST['lname'];
    $a = $_POST['addr'];
    $p = $_POST['phone'];
    $current_pass = $_POST['current_pass'];
    $updated = false;
    
    if(md5($current_pass) == $pass) {
        
        if($fname != $f) {
            if(strpbrk($f, '1234567890')) {
                $error = 'The first name should not contain any numbers';
            }
            if($error == null) {
                //update fname
                $sql = "update users set firstname = '$f' where user_id = $trader_id";
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
                $sql = "update users set lastname = '$l' where user_id = $trader_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            } 
        }
        
        if($a != $addr) {
            
            if($error == null) {
                //update addr
                $sql = "update users set address = '$a' where user_id = $trader_id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
            } 
        }
        if($p != $contact) {
            if($error == null) {
                $sql = "update users set phone = '$p' where user_id = $trader_id";
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
            
            if($error == null and $old_pic != 'default.jpg') { 
                unlink('../profile_pics/'.$old_pic);
                move_uploaded_file($image_temp,'../profile_pics/'.$image);
                
                //update image
                $sql = "update users set profile_picture = '$image' where user_id = $trader_id";
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
                $sql = "update users set password = '$p' where user_id = $trader_id";
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
            $mail->setFrom(EMAIL, 'Shopurb Registration');
            $mail->addAddress("$e");                           
            $mail->addReplyTo(EMAIL); 
            $mail->isHTML(true); 
            $mail->Subject = 'Shopurb Registration';
            $mail->Body    = "
            
            <img style=\"display: block;\" src=\"https://2.bp.blogspot.com/-eeGplg5TLGE/XL_a8A4DoKI/AAAAAAAADVM/_0KAjIU3tcgKuUrM5ZYH_JhJyjBu08iLACLcBGAs/s320/logo.png\" width=\"200\" /><br>
            <strong>Dear $username,</strong><br>
            
            <p>Your profile details has been updated!</p><br>
            
            <p>Account Type: $role</p>
            
            <p>Status: Verified </p>
            
          
            
            ";            
            
        
            if(!$mail->send()) {
                
                echo 'Message could not be sent.';
                
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                
            } else {
                echo "<meta http-equiv='refresh' content='1'>";
                echo "<div class=\"alert alert-success alert-dismissible \">
                <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                <strong>Success!</strong> Your account has been updated
              </div>";
                
            }
    
    
} 
else {
    
    if($error == null) {
        echo "<meta http-equiv='refresh' content='1'>";
        echo "<div class=\"alert alert-warning alert-dismissible \">
    <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
    <strong>Warning!</strong> Nothing to Update!
  </div>";
    }
}
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trader Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
</head>

<body>
    <div class="container-fluid display-table">
        <div class="row display-table-row">
            <!-- Side Menu -->
            <div class="col-md-2 col-sm-1 hidden-xs display-table-cell valign-top" id="side-menu">
                <h1 class="hidden-xs hidden-sm"><?=$p_line?></h1>
                <ul>
                    <li class="link">
                        <a href="index.php">
                            <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Dashboard</span>
                        </a>
                    </li>
                    <li class="link active">
                        <a href="profile.php">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Profile</span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="shop_crud.php">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Shop</span>
                            <span class="pull-right label label-success hidden-xs hidden-sm"><?=get_shop_count();?></span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="product_crud.php">
                            <span class="glyphicon glyphicon-gift" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Products</span>
                            <span class="pull-right label label-success hidden-xs hidden-sm"><?=get_prod_count();?></span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- Main Content-->
            <div class="col-md-10 col-sm-11 display-table-cell valign-top">
                <div class="row">

                    <header id="nav-header" class="clearfix">
                        <div class="col-md-5">

                            <nav class="pull-left navbar-default">

                                <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#slide-menu">
                                    <span class="sr-only">Toggle navigtion</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>

                            </nav>

                        </div>
                        <div class="col-md-7">
                            <ul class="pull-right">
                                <li id="welcome">Trader Area</li>

                                <li>
                                    <a href="logout.php" class="logout">
                                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                                        <span>logout</span>
                                    </a>
                                </li>
                                <li>

                                </li>
                            </ul>
                        </div>
                    </header>
                </div>
                <!--Content Area-->
                <div id="content">
                    <header>
                        <h2 class="page_title">Trader Profile</h2>
                    </header>

                    <div class="content-inner">



                        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="form-wrapper">
                                <div class="preview form-group row">
                                    <img class="preview-img" src="../profile_pics/<?=$image?>" alt="Preview Image" width="200" height="200" />
                                    <div class="browse-button">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                        <input class="browse-input" type="file" name="pic" id="UploadedFile" />
                                    </div>

                                </div>
                                <div class="form-group row">

                                    <label for="uname" class="col-md-2 col-form-label">Username:</label>
                                    <div class="col-md-4">

                                        <input name="uname" disabled type="text" class="form-control" id="uname" value="<?=$username?>">

                                    </div>
                                </div>
                                <div class="form-group row">

                                    <label for="fname" class="col-md-2 col-form-label">First Name:</label>
                                    <div class="col-md-4">

                                        <input required name="fname" type="text" class="form-control" id="fname" value="<?=$fname?>">

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="lname" class="col-md-2 col-form-label">Last Name:</label>
                                    <div class="col-md-4">

                                        <input required name="lname" type="text" class="form-control" id="lname" value="<?=$lname?>">

                                    </div>
                                </div>

                                <div class="form-group row">

                                    <label for="email" class="col-md-2 col-form-label">Email:</label>
                                    <div class="col-md-4">

                                        <input name="email" disabled type="email" class="form-control" id="email" value="<?=$email?>">

                                    </div>
                                </div>
                                <div class="form-group row">

                                    <label for="addr" class="col-md-2 col-form-label">Address:</label>
                                    <div class="col-md-4">

                                        <input required name="addr" type="text" class="form-control" id="addr" value="<?=$addr?>">

                                    </div>
                                </div>
                                <div class="form-group row">

                                    <label for="dob" class="col-md-2 col-form-label">DOB:</label>
                                    <div class="col-md-4">

                                        <input name="dob" disabled type="text" class="form-control" id="dob" value="<?=$dob?>">

                                    </div>
                                </div>

                                <div class="form-group row">

                                    <label for="pline" class="col-md-2 col-form-label">Product Line:</label>
                                    <div class="col-md-4">

                                        <input name="p_line" disabled type="text" class="form-control" id="pline" value="<?=$p_line?>">

                                    </div>
                                </div>
                                <div class="form-group row">

                                    <label for="phone" class="col-md-2 col-form-label">Phone:</label>
                                    <div class="col-md-4">

                                        <input required name="phone" type="tel" class="form-control" id="phone" value="<?=$contact?>">

                                    </div>
                                </div>
                                <div class="form-group row">

                                    <label for="p" class="col-md-2 col-form-label">Change Password:</label>
                                    <div class="col-md-4">

                                        <input type="password" class="form-control" id="p" name="new_pass">

                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="p2" class="col-md-2 col-form-label">Password:</label>

                                    <div class="col-md-4">

                                        <input required type="password" class="form-control" id="p2" name="current_pass">

                                    </div>
                                </div>
                                <div class="clearfix">
                                    <button type="submit" class="btn btn-success pull-left" name="update">Update</button>
                                </div>


                                <div class="row">
                                    <div class="error">
                                        <p style="color:red;"><?=$error?></p>
                                    </div>

                                </div>

                            </div>


                        </form>

                    </div>
                </div>

                <div class="row">
                    <footer id="footer" class="clearfix">
                        <div class="pull-left"><b>Copyright</b> &copy; 2019</div>
                        <div class="pull-right">SHOPURB</div>
                    </footer>
                </div>
            </div>
        </div>
    </div>


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
