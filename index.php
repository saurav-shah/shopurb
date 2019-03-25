<?php include('includes/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>SHOPURB</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="main.js"></script>
    <link rel="stylesheet" type="text/css" href="nivo-slider/jquerysctipttop.css"/>
    <link href="jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="nivo-slider/themes/default/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/themes/bar/bar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="nivo-slider/demo/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <script src="gumby/js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body>
<div id="heading">
        <h1>SHOPURB</h1>
    </div>

    <?php include('navbar.php');
    include('searchetc.php');?> 
    
  <div id="wrapper">

    <div class="row" id="slider">
      <div class="slider-wrapper theme-default" style="margin: 0; margin-left: 63px;">
        <div id="slider" class="nivoSlider" style="z-index: -1;">
          <img src="images/jamaican.jpg" data-thumb="images/jamaican.jpg" alt="" title="The Jammy's,Thamel Kathmandu"/>
          <img src="images/tuna.jpg" data-thumb="images/tuna.jpg" alt="" title="The SubURB,jhamsikhel Kathmandu" />
          <img src="images/fishnchips.jpg" data-thumb="images/fishnchips.jpg" alt="" title="Tamarind,jhamsikhel Lalitpur" /> 
          <img src="images/cake.jpg" data-thumb="images/cake.jpg" alt="" title="Hessed,Pulchowk Lalitpur" /> 
          <img src="images/vegan.jpg" data-thumb="images/vegan.jpg" alt="" title="Veggie Delight,jhamsikhel Lalitpur" />  
          <img src="images/cookies.jpg" data-thumb="images/cookies.jpg" alt="" title="Herman Bakery,Pulchowk Lalitpur" /> 
        </div>
      </div>
    </div>
    

</div>
<br>

<!-- PRODUCT LISTING-->
<div id="wrapper">
    
    <h2>Featured Products</h2>
    
    <div class="row" id="products">
        
        
<?php
                 $sql = "select * from (select * from product order by dbms_random.value) where rownum <= 6";

                $get_prod = oci_parse($con, $sql);

                oci_execute($get_prod);

                while($row = oci_fetch_assoc($get_prod)){

                    $prod_id = $row['PROD_ID'];
                    $prod_title = $row['PROD_TITLE'];
                    $prod_cat = $row['FK_CAT_ID'];
                    $prod_img = $row['PROD_IMG'];
                    $prod_price = $row['PROD_PRICE'];


                    echo "


                 <div class='product_box'>

                    <center><div class='primary badge'>$prod_title</div></center>

                    <img src='trader_area/product_images/$prod_img' alt='$prod_img'>

                    <center>
                        <p><div class='default badge'>Price: $$prod_price</div></p>
                        <div class='pretty small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class='pretty small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                }  ?>  
            
    </div>
  
</div>
<br>
<!-- PRODUCT LISTING END-->



    <footer>
    <?php include('footer.php')?>
    </footer>

    <script src=" gumby/js/libs/jquery-2.0.2.min.js"></script>
    <script gumby-touch="gumby/js/libs" src="gumby/js/libs/gumby.min.js"></script>
    <script src="jquery.min.js"></script> 
    <script type="text/javascript" src="nivo-slider/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
      $(window).load(function() {
        $('#slider').nivoSlider();
      });
    </script> 
</body>
</html>