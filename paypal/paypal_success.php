<?php

require 'includes/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    var_($_POST);
    if(isset($_GET['i'])) {
        
        
        $invoice_no = $_GET['i'];
        
        $sql = "update orders set payment_status = 'Paid' where invoice_no = $invoice_no";
        
        oci_execute(oci_parse($con, $sql));
        
        $sql = "update invoice set payment_status = 'Paid' where invoice_no = $invoice_no";
        
        oci_execute(oci_parse($con, $sql));
        
        
    }
    
    
    
    
}
else {
    echo 'Something went wrong!';
}

?>