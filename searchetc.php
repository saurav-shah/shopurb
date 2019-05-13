
<div class="search">
    <hr class="line">
    <br>
<div class="row">
    <div class="twelve columns">
    <ul class="second-nav">

        <li>
        <div>
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

                echo '<img width="35" src = "profile_pics/'.$pic.'" alt="avatar" style="border-radius: 50%; margin-top:-5px; float:left; margin-right: 5px;"><span style = "color:white; "> <em>'.$username.'</em> | <a href="profile.php">Profile</a> | <a href = "logout.php">Logout</a></span>';
            }
            else {
                echo '<span style = "color:white;"> <a href="login.php">Login</a> |
                    <a href="customer_register.php">Customer Register</a> |
                    <a href="Trader_register.php">Trader Register</a></span>';
            } ?>

        </div>
        </li>

        <li>
            <div>

                <form action="results.php" method="get" enctype="multipart/form-data">
                    <li class="field" style="display:inline;list-style:none;">
                        <input  class="input wide" type="text" name="user_query" placeholder="Search Product...">
                    </li>
                    <div class="medium pretty secondary btn">

                        <input type="submit" name="search" value="Search">

                    </div>
                </form>
            </div>
        </li>

        <li>
            <div>
                <a href="cart.php">Cart <span class="info badge"><?php echo cart_items(); ?></span></a>
            </div>
        </li>

    </ul>

    </div>
</div>

    <br><br>
</div>

