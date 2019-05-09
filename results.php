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

        <h2>Products</h2>

        <div class="row" id="products">

            <!-- left side start-->
            <div class="two columns">

                <center>
                    <h5 class="title"><strong>Category</strong></h5>
                </center>
                
                <ul>
                    <?php get_category(); ?>

                </ul>

                <center>
                    <h5 class="title"><strong>Shop</strong></h5>
                </center>

                <ul>
                    <?php get_shop(); ?>
                </ul>
            </div>
            <!-- left side end-->
            
            
            
            <!-- right side start-->
            <div class="nine columns">
               
               <?php
                
                if(isset($_GET['search'])){
                    
                    $query = $_GET['user_query'];
                    
                 $sql = "select * from product where keywords like '%$query%' and prod_status = 'active'";

                $get_prod = oci_parse($con, $sql);

                oci_execute($get_prod);
                $num_rows = oci_fetch_all($get_prod, $out);
                    if($num_rows != 0){
                        oci_execute($get_prod);
                       while($row = oci_fetch_assoc($get_prod)){

                    $prod_id = $row['PROD_ID'];
                    $prod_title = $row['PROD_TITLE'];
                    $prod_img = $row['PROD_IMG'];
                    $prod_price = $row['PROD_PRICE'];


                    echo "


                 <div class='product_box'>

                    <center><div class='primary badge'>$prod_title</div></center>

                    <img src='trader_area/product_images/$prod_img' alt='$prod_img'>

                    <center>
                        <p><div class='default badge'>Price: $$prod_price</div></p>
                        <div class='small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class='small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";
                    
                    
                } 
                    }
                    else{
                        echo '<center><h3>No Products Found!</h3><center>';
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
