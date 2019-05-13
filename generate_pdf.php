<?php 
session_start();
require 'fpdf/fpdf.php';
require 'includes/db.php';


if(isset($_POST['generate'])) {
    
    //if(true){
    $street = $_POST['address_street'];
    $state = $_POST['address_state'];
    $city = $_POST['address_city'];
    $zip = $_POST['address_zip'];
    $invoice_no = $_POST['invoice'];
    $count = $_POST['num_cart_items'] - 1;
    
    $c_id = $_SESSION['cust_id'];
    
    $sql = 'select firstname, lastname, address from users where user_id = '.$c_id.'';
    $prep = oci_parse($con, $sql);
    oci_execute($prep);    
    $row = oci_fetch_assoc($prep);
    
    $fname = $row['FIRSTNAME'];
    $lname = $row['LASTNAME'];
    $addr = $row['ADDRESS'];
    $date = date('Y/m/d');
    $collection = $_POST['collection_slot'];
    
    
    
} else {
    die('Error');
}


//writable horizontal width = 189

$pdf = new FPDF('p','mm','A4');

$pdf -> AddPage();


$pdf -> setFont('Arial','B',14);
//cell->(width, height, text, border, new line,[align])

//logo
$pdf->Image("images/logo.png",0,0,50,50);

$pdf->Cell(130,5,'',0,0);
$pdf->Cell(59,5,'INVOICE',0,1,);
$pdf->Cell(189,5,'',0,1);

$pdf -> setFont('Arial','',12);
$pdf->Cell(130,5,'',0,0);
$pdf->Cell(25,5,'Date',0,0);
$pdf->Cell(34,5,"$date",0,1);
$pdf->Cell(130,5,'',0,0);
$pdf->Cell(25,5,'Invoice #',0,0);
$pdf->Cell(34,5,"$invoice_no",0,1);

// vertical gap
$pdf->Cell(189,15,'',0,1);


$pdf->Cell(189,5,"$street",0,1);
$pdf->Cell(189,5,"$city, $state $zip",0,1);
$pdf->Cell(189,5,"United States",0,1);


// vertical gap
$pdf->Cell(189,20,'',0,1);

//TO
$pdf->Cell(100,5,'To,',0,1);



$pdf->Cell(10,5,'',0,0);
$pdf->Cell(90,5,"$fname $lname",0,1);

$pdf->Cell(10,5,'',0,0);
$pdf->Cell(90,5,"$addr",0,1);


// vertical gap
$pdf->Cell(189,20,'',0,1);

//invoice contents
$pdf -> setFont('Arial','B',12);
$pdf->Cell(80,5,'Item',1,0);
$pdf->Cell(30,5,'Quantity',1,0);
$pdf->Cell(30,5,'Unit Price',1,0);
$pdf->Cell(49,5,'Amount',1,1);

$pdf -> setFont('Arial','',12);



$i = 1;
while($i <= $count) {

    $pdf->Cell(80,5,''.$_POST['item_name'.$i.''].'',1,0);
    $pdf->Cell(30,5,''.$_POST['quantity'.$i.''].'',1,0,'R');
    $pdf->Cell(30,5,''.$_POST['unit_price'.$i.''].'',1,0,'R');
    $pdf->Cell(49,5,''.$_POST['mc_gross_'.$i.''].'',1,1,'R'); 
    $i++;
    
}

//summary
$pdf->Cell(80,5,'',0,0);
$pdf->Cell(30,5,'',0,0);
$pdf->Cell(30,5,'Subtotal',1,0);
$pdf->Cell(4,5,'$',1,0);
$pdf->Cell(45,5,''.$_POST['subtotal'].'',1,1,'R');

$pdf->Cell(80,5,'',0,0);
$pdf->Cell(30,5,'',0,0);
$pdf->Cell(30,5,'Discount',1,0);
$pdf->Cell(4,5,'$',1,0);
$pdf->Cell(45,5,''.$_POST['discount'].'',1,1,'R');

$pdf->Cell(80,5,'',0,0);
$pdf->Cell(30,5,'',0,0);
$pdf->Cell(30,5,'Vat',1,0);
$pdf->Cell(49,5,'13%',1,1,'R');

$pdf->Cell(80,5,'',0,0);
$pdf->Cell(30,5,'',0,0);
$pdf->Cell(30,5,'Total',1,0);
$pdf->Cell(4,5,'$',1,0);
$pdf->Cell(45,5,''.$_POST['grand_total'].'',1,1,'R');

$pdf->Cell(189,5,'',0,1);
$pdf->Cell(30,5,'Collection Day: ',0,0);
$pdf->Cell(159,5,"$collection",0,1);
$pdf -> Output();
?>