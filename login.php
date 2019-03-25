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
    <script src="main.js"></script>
</head>
<body>
<script src="gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script src="gumby/js/libs/gumby.min.js"></script>
    <div class= "wrapper">
    <div id="heading">
        <img src="images/banner.png" alt="banner">
    </div>
    <?php include('navbar.php');
    include('searchetc.php');?> 
    <!--LOGIN FORM-->
    <div id="wrapper" style="text-align: left;">
            <div class="row" id="container">
                <div class="twelve columns">
                    <h2>Login</h2><br/>
            <form method="POST" action="">
                Email<br/>
                <input type="Email" name="mail"  required value="<?php if(isset($_POST['mail']))
			    {
				    echo $_POST['mail']; 
			    }?>"/><br/>
                Password<br/>
                <input type="password" name="Password" required/><br/><br/>
                <div class="medium default btn" id="btn1">
                    <input type="submit" name="Login" value="Submit" style="padding-bottom: 34px;padding-left: 0px;"/><br/><br/>
                </div>
            
            <!--login Query-->
                <?php
                $errors= array();
    include ('connection.php');
    if (isset($_POST['Login'])){
        $email=$_POST['mail'];
        $pass=md5($_POST['Password']);
        $query="SELECT * FROM users WHERE Email='$email' AND Password='$pass'";
        $result=mysqli_query($connection, $query);
        $count= mysqli_num_rows($result);
        if($count>=1)
        //checks role
        {
            while ($row = mysqli_fetch_assoc($result)) {
            $crole=$row['Role'];
           
           
            if($crole=="SA")
            {
                header('Location:admin/dashboard.php');
                $_SESSION['email']=$email;
            }
            if($crole=="U")
            {
                header('Location: Index.php');
                $_SESSION['email']=$email;
            }
        }  
    }
    else{
        echo"User not Registered";
    }
    }
?>
            </form>
            <a href="register.php">Create a new account</a><br/>
        </div>
        </div>
</div>
<br/>
        <!--Footer-->
<footer>
   <?php include('footer.php')?>
</footer>
</body>
</html>

