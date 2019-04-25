
<div class="search">
    <hr class="line">
    <br>
    <div class="item">
        <?php 
        
        @session_start();
        if(isset($_SESSION['cust_id'])) {
            
            $sql = 'select username from users where user_id = '.$_SESSION['cust_id'].'';
            $prep = oci_parse($con, $sql);
            oci_execute($prep);
            
            while($row = oci_fetch_assoc($prep)){
                $username = $row['USERNAME'];
            };
            
            
            echo ' Welcome '.$username.' | <a href = "logout.php">Logout</a>';
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
