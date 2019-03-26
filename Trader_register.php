<?php
$errors=array();
 include ('errors.php');
include ('connection.php');
    if (isset($_POST['Login']))
    {
        //makes a  directory if doesn't exist to store images
        $dir="admin/profile";
        if(!file_exists($dir) && !is_dir($dir))
        {
            mkdir($dir);
        }
        $tmpname=$_FILES['UploadImg']['tmp_name'];
        $name=$_FILES['UploadImg']['name'];
        $size=$_FILES['UploadImg']['size'];
        $type=$_FILES['UploadImg']['type'];
        $fullpath="$dir/$name";
        if($name!=""){
       
            move_uploaded_file($tmpname,$fullpath);
        //Insert into Table    
        $fname=$_POST['fname'];
        $fname=htmlentities($fname);
        $lname=$_POST['lname'];
        $lname=htmlentities($lname);
        $pass=$_POST['password'];
        $password=md5($pass);
        $age=$_POST['age'];
        $email=$_POST['email'];
        $email=htmlentities($email);
        $format='/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
        if(!preg_match($format,$pass)) 
        { 
            array_push($errors, "Password must contain a number, a special character, a capital letter and must be 8-20 characters long.");
 
        }
   

if(strpbrk($fname, '1234567890'))
       {
        array_push($errors, "Name must not contain numbers, only letters");
    }
   
    if(count($errors)==0){
        $query="INSERT INTO users (Fname, Lname, Age, Email,DP, Password, Role, Status) 
        VALUES ('$fname','$lname','$age','$email','$fullpath','$password','U','Active')";
        $result=mysqli_query($connection, $query);
        if($result)
        {
            header('Location: login.php');
        }
        else{
            echo"Something Wrong";
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
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
    <script src="main.js"></script>
</head>
<body>
<div id="heading">
        <img src="images/banner.png" alt="banner">
    </div>
<script src="gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script src="gumby/js/libs/gumby.min.js"></script>

   <?php include('navbar.php');
    include('searchetc.php');?>

    <div id="wrapper">
    <div class="row" id="container">
      <div class="twelve columns">
    <h2>Trader Registration</h2>
    <!--Register Form-->
    <form method="POST" enctype="multipart/form-data" action="register.php">
    <?php displayError($errors);?>
    First Name
    <input type="text" name="fname" required value="<?php 
    if(isset($_POST['fname']))
			    {
				    echo $_POST['fname']; 
			    }//to retain value?>"/><br/><br/>
    Last Name
    <input type="text" name="lname" required value="<?php 
    if(isset($_POST['lname']))
			    {
				    echo $_POST['lname']; 
			    }//to retain value?>"/><br/><br/>
    Age
    <select name="age" required>
        <option value="10-20">10-20</option>
        <option value="20-30">20-30</option>
        <option value="30-40">30-40</option>
        <option value="40-50">40-50</option>
        <option value="50-60">50-60</option>
    </select><br/><br/>
    Email
    <input type="email" name="email" required value="<?php 
    if(isset($_POST['email']))
			    {
				    echo $_POST['email']; 
			    }//to retain value?>"/><br/><br/>
    Profile Pic
    <input type="file" name="UploadImg" required>
    Password
    <input type="password" name="password" required/>
    <br/><br/>
    <input type= "checkbox" name="terms" value="tick" required>I agree to the terms and condition of this site
    <br/><br/>
    <div class="medium default btn" id="btn2">
    <input type="submit" name="Login" value="Register"/>  
</div>  
    </form>
   
</div>
</div>
<hr class="line">
<!--FOOTER-->
    <footer>
    <?php include('footer.php')?>
</footer>
</body>
</html>