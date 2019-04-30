
<div class="search">
    <hr class="line">
    <br>
    <div class="item">
        <?php 
        
        @session_start();
        if(isset($_SESSION['cust_id'])) {
            
            $sql = 'select username,profile_picture from users where user_id = '.$_SESSION['cust_id'].'';
            $prep = oci_parse($con, $sql);
            oci_execute($prep);
            
            while($row = oci_fetch_assoc($prep)){
                $username = $row['USERNAME'];
                $pic = $row['PROFILE_PICTURE'];
                
            };
            
            if($pic == null) {
                $pic = 'default.jpg';
            }
            
            echo '<img width="35" src = "profile_pics/'.$pic.'" alt="avatar" style="border-radius: 50%; margin-top:-5px; float:left; margin-right: 10px;"><span style = "color:white;"> <em>'.$username.'</em> | <a href="profile.php">Profile</a> | </span><a href = "logout.php">Logout</a>';
        }
        else {
            echo '<a href="login.php">Login</a> |
                <a href="customer_register.php">Customer Register</a> |
                <a href="Trader_register.php">Trader Register</a>';
        } ?>

    </div>


    <div class="item">

        <form action="results.php" method="get" enctype="multipart/form-data">

            <input type="text" name="user_query" placeholder="Search Product..." size="50">
            <input type="submit" name="search" value="Search">
        </form>
    </div>


    <div class="item">

        <a href="cart.php">Cart <span class="info badge"><?php echo cart_items(); ?></span></a>

    </div>





    <br><br>
</div>
