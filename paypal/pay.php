<?php
session_start();
require_once('includes/db.php');


    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
       $slot = $_POST['slot'];
       $time = $_POST['time'];
       $amt = $_POST['amount'];
       $total_prod = $_POST['count_prod']; 
       $c_id = $_SESSION['cust_id'];
       $collection_slot = $slot.' ('.$time.')';
       $payment_status = 'Unpaid';
       $invoice_no = mt_rand(1000,9999); 
        
        //Inserting into orders table with payment_status = Unpaid
         $sql = "insert into orders (order_id, customer_id, amount, collection_slot, total_products, order_date, payment_status, invoice_no) values (order_id.nextval, $c_id, $amt, '$collection_slot', $total_prod, sysdate, '$payment_status', $invoice_no)";
        
        
        $prep = oci_parse($con, $sql);
        oci_execute($prep);
        
        if($prep) {
            
            
            $sql = 'select * from cart where user_id = '.$c_id.'';
            $select = oci_parse($con, $sql);
            oci_execute($select);
    
                            
            while($row = oci_fetch_assoc($select)){

                $qty = $row['QUANTITY'];
                $p_id = $row['PROD_ID'];
                $qry = 'select * from product where prod_id = '.$p_id.'';
                $get = oci_parse($con, $qry);
                oci_execute($get);

                while($data = oci_fetch_assoc($get)){

                    $shop_id = $data['FK_SHOP_ID'];





                }
            
             $insert_invoice = "insert into invoice (invoice_id, shop_id, invoice_no, prod_id, quantity, collection_slot, payment_status) values (invoice_id.nextval, $shop_id, $invoice_no, $p_id, $qty, '$collection_slot', '$payment_status')";
            
                
            oci_execute(oci_parse($con, $insert_invoice));
                
                
                
                
            
            }

            
            
            
        }
   
        
        
    }
    else {
        echo die('Something went wrong');
    }





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>

<body>

    <form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr" id="paypalForm">

        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="business" value="seller1@shopurb.com">
        <input type="hidden" name="currency_code" value="USD">


        <?php
                            $subtotal = 0;
                            $count = 1; 
                            $sql = 'select * from cart where user_id = '.$c_id.'';
                            $prep = oci_parse($con, $sql);
                            oci_execute($prep);
    
                            
                            while($row = oci_fetch_assoc($prep)){
                                
                                $qty = $row['QUANTITY'];
                                $p_id = $row['PROD_ID'];
                                $qry = 'select * from product where prod_id = '.$p_id.'';
                                $get = oci_parse($con, $qry);
                                oci_execute($get);
                                
                                while($data = oci_fetch_assoc($get)){
                                    
                                    
                                    
                                    $p_name = $data['PROD_TITLE'];
                                   
                                    $unit_price = $data['PROD_PRICE'];
                                    $discount = $data['DISCOUNT'];                                    
                                    $qty_price = $qty * $unit_price;
                                    $line_total = $qty_price - (($discount/100) * $qty_price);
                                    $subtotal += $line_total;
                                    $tax = 0.13 * $subtotal;
                                    
                                    
                                
                           ?>

        <input type="hidden" name="item_name_<?= $count ?>" value="<?= $p_name ?>">
        <input type="hidden" id="item_number_<?= $count ?>" name="item_number" value="<?= $p_id ?>">
        <input type="hidden" name="amount_<?= $count ?>" value="<?= $unit_price ?>">
        <input type="hidden" name="discount_rate_<?= $count ?>" value="<?= $discount ?>">
        <input type="hidden" name="quantity_<?= $count ?>" value="<?= $qty ?>">




        <?php $count++; }} ?>
       
        <input type="hidden" name="item_name_<?= $count ?>" value="Sales Tax">
        <input type="hidden" name="amount_<?= $count ?>" value="<?= $tax ?>">

        <input type="hidden" name="rm" value="2">
        <input type="hidden" name="return" value="http://localhost/projects/PM/paypal/paypal_success.php?i=<?= $invoice_no ?>" />
        <input type="hidden" name="cancel_return" value="http://localhost/projects/PM/paypal/paypal_cancel.php" />


    </form>
    <script>
        document.getElementById('paypalForm').submit();

    </script>
</body>

</html>