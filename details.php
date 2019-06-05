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
    include('searchetc.php');?>



    <!-- PRODUCT LISTING START-->
    <div id="wrapper">
        <h2>Product Detail</h2>
        <div class="row">
            <div class="btn default pretty medium icon-left entypo icon-left-open-big">
                <a href='shop.php'>Back</a>
            </div>
        </div>

        <div class="row" id="products">

            
            <div class="twelve columns">

                <?php
                
                if(isset($_GET['pro_id'])){
                    
                $pro_id = $_GET['pro_id'];
                    
                $sql = "select * from product where prod_id = $pro_id";

                $get_prod = oci_parse($con, $sql);

                oci_execute($get_prod);

                while($row = oci_fetch_assoc($get_prod)){

                    $prod_id = $row['PROD_ID'];
                    $prod_title = $row['PROD_TITLE'];                    
                    $prod_img = $row['PROD_IMG'];
                    $prod_price = $row['PROD_PRICE'];
                    $prod_desc = $row['PROD_DESC'];
                    $allergy = $row['ALLERGY_INFO'];
                    $discount = $row['DISCOUNT'];
                    $min_order = $row['MIN_ORDER'];
                    $max_order = $row['MAX_ORDER'];
                    $stock = $row['STOCK'];
                    $in_stock =($stock > 1)?true:false;
                    
                    if($in_stock){
                        $stock_status = 'In Stock';
                        $color = '#4CAF50';
                    }
                    else {
                        $stock_status = 'Out of Stock';
                        $color = '#CA3838';
                    }
                    

                    
                    if($discount != 0) {
                       
                        $new_price = $prod_price - (($discount / 100) * $prod_price);


                    echo "

                 <div class='row'>
                <div class='six columns'>
                
                
                <center>
                     <div class='product_detail'>

                     <h3>$prod_title</h3>

                        <img src='trader_area/product_images/$prod_img' alt='$prod_img' >


                            <p>
                                <div class='default badge'>Price: <s>$$prod_price</s>&nbsp;&nbsp;$$new_price</div>
                            </p>
                        
                            
                            <div class=' medium warning btn'>
                                <a href='shop.php?add_cart=$prod_id'>Add to Cart</a>
                            </div>

                    </div>
                </center>
                
                </div>
                
              
               <div class='six columns'>
               
                   <section class='tabs'>

                        <ul class='tab-nav'>
                            <li class='active'><a href='#'>Description</a></li>
                            <li><a href='#'>Allergy</a></li>
                        </ul>

                        <div class='tab-content active'>
                            <p style=' color: $color; font-weight:bold; font-size: 23px;'>$stock_status</p>
                            <p>$prod_desc</p>
                            <p>Minimun Order: $min_order</p>
                            <p>Maximum Order: $max_order</p>
                        </div>

                        <div class='tab-content'>
                            <p>$allergy</p>
                        </div>

                   </section>
                </div>
                
               </div>
                
               

                ";
                }
                else{
                         echo "
                <div class='row'>
                <div class='six columns'>
                
                
                <center>
                     <div class='product_detail'>

                     <h3>$prod_title</h3>

                        <img src='trader_area/product_images/$prod_img' alt='$prod_img' >


                            <p>
                                <div class='default badge'>Price: $$prod_price</div>
                            </p>
                            <div class=' medium warning btn'>
                                <a href='shop.php?add_cart=$prod_id'>Add to Cart</a>
                            </div>

                    </div>
                </center>
                
                </div>
                
              
               <div class='six columns'>
               
                   <section class='tabs'>

                        <ul class='tab-nav'>
                            <li class='active'><a href='#'>Description</a></li>
                            <li><a href='#'>Allergy</a></li>
                        </ul>

                        <div class='tab-content active'>
                            <p style='color: $color; font-weight: bold; font-size: 23px;'>$stock_status</p>
                            <p>$prod_desc</p>
                            <p>Minimun Order: $min_order</p>
                            <p>Maximum Order: $max_order</p>
                            
                           
                        </div>

                        <div class='tab-content'>
                            <p>$allergy</p>
                        </div>

                   </section>
                </div>
                
               </div> ";
                        
                }
                    
            }
        }
                
?>

            </div>
            <!-- right side end-->
        </div>

    </div>

    <!-- PRODUCT LISTING END-->

    <hr class="line">

    <footer>
        <?php include('footer.php')?>
    </footer>

    <script src=" gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script gumby-touch="gumby/js/libs" src="gumby/js/libs/gumby.min.js"></script>

</body>

</html>
