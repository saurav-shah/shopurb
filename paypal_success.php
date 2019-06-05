<?php
session_start();
require 'includes/db.php';
require 'functions/functions.php';



if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //print_r($_POST);
    
    
    //getting all the variables posted by paypal
    $count = $_POST['num_cart_items'] - 1; // because sales tax is also send as an item
    $c_id = $_SESSION['cust_id'];
    
    parse_str($_POST['custom'],$_MYVAR);

    $discount = $_MYVAR['d'];
    $collection_slot = $_MYVAR['c'];
    
    
    $invoice_no = $_POST['invoice'];
        
    // updating invoice and orders table 
    $sql = "update orders set payment_status = 'Paid' where invoice_no = $invoice_no";        
    oci_execute(oci_parse($con, $sql));
        
//    $sql = "update invoice set payment_status = 'Paid' where invoice_no = $invoice_no";        
//    oci_execute(oci_parse($con, $sql));
    
    
    
    // decreasing quantity from stock
    $sql = 'select * from cart where user_id = '.$c_id.'';
    $select = oci_parse($con, $sql);
    oci_execute($select);
    
    while($row = oci_fetch_assoc($select)) {
        $p_id = $row['PROD_ID'];
        $qty = $row['QUANTITY'];
        
        $qry = 'select stock from product where prod_id = '.$p_id.'';
        $get = oci_parse($con, $qry);
        oci_execute($get);
        $data = oci_fetch_assoc($get);
        
        $stock = $data['STOCK'];
        $new_stock = $stock - $qty;
        
        $update = 'update product set stock = '.$new_stock.' where prod_id = '.$p_id.'';
        oci_execute(oci_parse($con, $update));
        
        
    }
    
    
    
    // deleting products from cart
    $sql = 'delete from cart where user_id = '.$c_id.'';
    
    oci_execute(oci_parse($con, $sql));
        
    
    
}
else {
    die('Something went wrong!');
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Paypal Success</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
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
    <h2>Thank You For Your Purchase!</h2>
    
          <center><strong>Your order has been placed successfully.</strong></center>
          <form target="_blank" action="generate_pdf.php" method="post" enctype="multipart/form-data">
              
             <?php
                $subtotal = 0;
                $i=1;
                while($i <= $count) {
                    
                    // adding all gross of items to calculate subtotal
                   $subtotal += $_POST['mc_gross_'.$i.''];
                    
                    // getting unit price by dividing the line total with quantity
                   $unit_price = $_POST['mc_gross_'.$i.''] / $_POST['quantity'.$i.''];
                 
              ?>
              
              <input type="hidden" name="item_name<?= $i ?>" value="<?= $_POST['item_name'.$i.''] ?>">
              <input type="hidden" name="quantity<?= $i ?>" value="<?= $_POST['quantity'.$i.''] ?>">     <input type="hidden" name="unit_price<?=$i?>" value="<?=$unit_price?>">
              <input type="hidden" name="mc_gross_<?=$i?>" value="<?= $_POST['mc_gross_'.$i.''] ?>">
              
              <?php $i++; } ?>
             
             <input type="hidden" name="address_street" value="<?=$_POST['address_street']?>">          
             <input type="hidden" name="address_state" value="<?=$_POST['address_state']?>">
             <input type="hidden" name="address_city" value="<?=$_POST['address_city']?>">
             <input type="hidden" name="address_zip" value="<?=$_POST['address_zip']?>">
             <input type="hidden" name="invoice" value="<?=$_POST['invoice']?>">
             
             <input type="hidden" name="subtotal" value="<?=$subtotal?>">
             <input type="hidden" name="discount" value="<?=$discount?>">
             <input type="hidden" name="grand_total" value="<?= $_POST['mc_gross'] ?>">
             <input type="hidden" name="num_cart_items" value="<?=$_POST['num_cart_items']?>">
             <input type="hidden" name="collection_slot" value="<?=$collection_slot?>">
             <br>
              <center><div class="primary btn medium" type="submit"><input type="submit" name="generate" value="Generate Invoice"></div></center>
          </form>
    
    
    
</div>
</div></div>
<hr class="line">

<!--FOOTER-->
    <footer>
    <?php include('footer.php')?>
</footer>
    </body></html>