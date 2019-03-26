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
               
               <?php get_products(); ?> 
               <?php get_cat_products(); ?>
               <?php get_shop_products(); ?>  
                
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
