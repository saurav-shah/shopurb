<?php

include ('includes/db.php');
include ('functions/functions.php');

?>
<!DOCTYPE html>
<html>

<head>
    <title>SHOPURB</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="main.js"></script>
    <link rel="stylesheet" type="text/css" href="nivo-slider/jquerysctipttop.css" />
    <link href="jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="nivo-slider/themes/default/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/themes/bar/bar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/demo/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" href="css/main.css" media="all">
    <script src="gumby/js/libs/modernizr-2.6.2.min.js"></script>
</head>

<body>
    <div id="heading">
        <img src="images/banner.png" alt="banner">
    </div>
    <?php include('navbar.php');
    include('searchetc.php'); cart();?>



    <!-- PRODUCT LISTING START-->
    <div id="wrapper">



        <div class="row">

            <div class="twelve columns">

                <?php if(cart_items() != 0 ) { ?>
               
                 <form action="cart.php" enctype="multipart/form-data" method="post" name="form">
                    <table>
                        <thead>
                            <tr>
                                <th>Remove</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total</th>

                            </tr>
                        </thead>
                         
                        <tbody>
                          
                            <?php
                            $subtotal = 0;
                            $sql = 'select * from cart where user_id = '.$_SESSION['cust_id'].'';
                            $prep = oci_parse($con, $sql);
                            oci_execute($prep);
                            $count = oci_fetch_all($prep, $out);
                            oci_execute($prep);
                            
                            while($row = oci_fetch_assoc($prep)){
                                
                                $qty = $row['QUANTITY'];
                                $p_id = $row['PROD_ID'];
                                $qry = 'select * from product where prod_id = '.$p_id.'';
                                $get = oci_parse($con, $qry);
                                oci_execute($get);
                                
                                while($data = oci_fetch_assoc($get)){
                                    
                                    
                                    $max = $data['STOCK'] - 1;
                                    $p_image = $data['PROD_IMG'];
                                    $p_name = $data['PROD_TITLE'];
                                    $p_desc = $data['PROD_DESC'];
                                    $unit_price = $data['PROD_PRICE'];
                                    $discount = $data['DISCOUNT'];
                                    $qty_price = $qty * $unit_price;
                                    $line_total = $qty_price - (($discount/100) * $qty_price);
                                    $subtotal += $line_total;
                                    $grand_total = 0.13 * $subtotal + $subtotal;
                                
                                    
                                    
                                
                           ?>

                            <tr>

                                <td>
                                    <input type="checkbox" name="remove[]" value="<?= $p_id ?>">
                                </td>
                                <td>
                                    <?= $p_name ?><br>
                                    <img src="trader_area/product_images/<?= $p_image ?>" alt="img" width="100" height="100">
                                </td>

                                <td>
                                    <input name="qty<?= $p_id ?>" type="number" min="1" max="<?= $max ?>" value="<?= $qty ?>">
                                    <?php
                                   
                                        if(isset($_POST['update'])) {
                                            $qty = $_POST['qty'.$p_id];
                                            
                                            $update = 'update cart set quantity = '.$qty.' where user_id = '.$_SESSION['cust_id'].' and prod_id = '.$p_id.'';
                                            $run_update = oci_parse($con, $update);
                                            oci_execute($run_update);
                                            
                                            if($run_update) {
                                                echo '<script>window.open(\'cart.php\',\'_self\')</script>';
                                            }
                                        }
                                   
                                   ?>
                                </td>

                                <td>
                                    <?= '$'.$unit_price ?>
                                </td>

                                <td>
                                    <?= $discount.'%' ?>
                                </td>

                                <td>
                                    <?= '$'.$line_total ?>
                                </td>


                            </tr>


                            <?php }}  ?>

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Subtotal:</strong></td>
                                <td><?= '$'.$subtotal ?></td>
                                <td></td>
                            </tr>
                            
                             <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Vat:</strong></td>
                                <td>13%</td>
                                <td></td>
                            </tr>
                             <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><input type="hidden" name="count_prod" value="<?= $count ?>"></td>
                                <td><strong>Total:</strong></td>
                                <td><?= '$'.$grand_total ?></td>
                                <td><input type="hidden" name="amount" value="<?= $grand_total ?>"></td>
                            </tr>
                         
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>


                                <td><strong>Collection: </strong></td>
                                <?php $slots = get_slots(); 
                                
                                    $s1 = $slots[0];
                                    $s2 = $slots[1];
                                    $s3 = $slots[2];
                                ?>
                                <td><select name="slot" required>
                                        

                                        <option value="" disabled  >-- Slot --</option>
                                        
                                        <option value="<?= $s1 ?>"><?= $s1 ?></option>
                                        <option value="<?= $s2 ?>"><?= $s2 ?></option>
                                        <option value="<?= $s3 ?>"><?= $s3 ?></option>

                                    </select>
                                </td>
                                
                                <td>
                                    <select name="time" required>

                                        <option value="" disabled >-- Time --</option>
                                        <option value="10-13">10-13</option>
                                        <option value="13-16">13-16</option>
                                        <option value="16-19">16-19</option>

                                    </select>

                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="primary btn medium"><input type="submit" name="continue" value="Continue Shopping"></div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                
                                <td>
                                    <div class="primary btn medium"><input type="submit" name="update" value="Update"></div>
                                </td>

                                <!-- checkout start -->
                                


                               
                                
                              <td>
                              

                              <input onclick="pay()" type="image" border="0" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-medium.png" alt="Check out with PayPal">
                              
                              </td>
                              




                                <!-- checkout end -->

                            </tr>

                                  
                        </tbody>


                    </table>
</form>
                
                
               
                
                                    
                                  

                <?php } else {echo '<h2>cart is empty<h2>';} ?>

                <?php
                    function update_cart() {
                        global $con;
                        if(isset($_POST['update'])) {
                            foreach($_POST['remove'] as $remove_id) {

                                $sql = 'delete from cart where prod_id = '.$remove_id.' and user_id = '.$_SESSION['cust_id'].'';
                                $prep = oci_parse($con, $sql);
                                oci_execute($prep);

                                if($prep) {
                                    echo "<meta http-equiv='refresh' content='0'>";
                                }
                            }
                        }

                        if(isset($_POST['continue'])) {
                            echo '<script>window.open(\'shop.php\',\'_self\')</script>';
                        }
                        
                        
                    }
                    echo @$update = update_cart();
                
               
                

                ?>

            </div>







        </div>

    </div>

    <!-- PRODUCT LISTING END-->

    <hr class="line">

    <footer>
        <?php include('footer.php')?>
    </footer>

    <script src=" gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script gumby-touch="gumby/js/libs" src="gumby/js/libs/gumby.min.js"></script>
    
    <script>
        
        function pay() {
            document.form.action = "paypal/pay.php";
            document.form.submit();
        }
    </script>
    
</body>

</html>
