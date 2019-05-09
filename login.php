<?php
session_start();
include 'includes/db.php';
include 'functions/functions.php';
$error = null;

if(isset($_SESSION['cust_id'])) {
    header('location: index.php');
}


if(isset($_POST['login'])){
    
    $u = $_POST['uname'];
    $p = $_POST['pass'];
    $p = md5($p);
    
        
    $sql = "select * from users where username = '$u' and password = '$p'";    
    $select = oci_parse($con, $sql);    
    oci_execute($select);    
    $num_rows = oci_fetch_all($select, $out);
    
    if($num_rows == 1){
        
        oci_execute($select);
        
        //process login
        while($row = oci_fetch_assoc($select)){
            $id = $row['USER_ID'];
            $verified = $row['VERIFIED'];
            $email = $row['EMAIL'];
            $status = $row['STATUS'];
            $created_at = $row['CREATED_AT'];
			$date = strtotime($created_at);
			$date = date('M d Y', $date);
            $role = $row['ROLE'];
            $p_line = $row['PRODUCT_LINE'];
        }
        
     
        if($verified == 1 and $status == 'active'){
            
            
            switch($role) {
                    
                case "Customer":                    
                    $_SESSION['cust_id'] = $id;
                    header ('location: index.php');
                    break;
                    
                case "Trader":                      
                    $_SESSION['trader_id'] = $id;
                    header ('location: trader_area/index.php');
                    break;
                    
            }
            
        }
        elseif($verified == 0 and $status == 'active') {
            $error = 'This account is not verified. An email was sent to '. $email .' on '.$date;
        }
        else {
            $error = 'Your account has been disabled by admin';
        }
        
    }else {
        
        //Invalid Credential
        $error = 'Username or password incorrect';
    }
        
    
   
    
    
    
}




?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
    <script src="gumby/js/libs/modernizr-2.6.2.min.js"></script>    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> 

</head>

<body>
    <script src="gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script src="gumby/js/libs/gumby.min.js"></script>
    <div class="wrapper">
        <div id="heading">
            <img src="images/banner.png" alt="banner">
        </div>
        <?php include('navbar.php');
    include('searchetc.php');?>
        <!--LOGIN FORM-->
        <div id="wrapper" style="text-align: left;">
            <div class="row" id="container">
                <div class="twelve columns">

                    <h2>Login</h2><br />


                    <form method="POST" action="" enctype="multipart/form-data">


                        <ul style="text-align:center;">

                            <li class="field">
                                <input class="narrow input" type="text" placeholder="Username" required name="uname" value="<?php if(isset($_POST['uname'])) echo $_POST['uname']; ?>">
                            </li>
                            <li class="field">

                                <input type="password" name="pass" required placeholder="Password" class="narrow input" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>">
                            </li>

                            <div class="medium primary btn" id="btn1">
                                <input type="submit" name="login" value="Login" />
                            </div>

                        </ul>



                        <!--login Query-->








                    </form>
                    <center style="color:red;"><?php echo $error; ?></center>
                </div>
            </div>
        </div>
    </div>
    <hr class="line">
    <!--Footer-->
    <footer>
        <?php include('footer.php')?>
    </footer>
</body>

</html>
