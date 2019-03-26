<?php

function get_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(!isset($_GET['cat'])){
            
            
            if(!isset($_GET['shop'])){
                
                 $sql = "select * from (select * from product order by dbms_random.value) where rownum <= 9";

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
                        <div class=' small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class=' small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                }       
            }
        }
}
// end of function

function get_cat_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(isset($_GET['cat'])){
            
                $cat_id = $_GET['cat'];            
                
                 $sql = "select * from product where fk_cat_id = $cat_id ";

                $get_cat_prod = oci_parse($con, $sql);

                oci_execute($get_cat_prod);

                while($row = oci_fetch_assoc($get_cat_prod)){

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
                        <div class=' small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class=' small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                }       
            
        }
}
// end of function

function get_shop_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(isset($_GET['shop'])){
            
                $trader_id = $_GET['shop'];            
                
                 $sql = "select * from product where fk_trader_id = $trader_id ";

                $get_shop_prod = oci_parse($con, $sql);

                oci_execute($get_shop_prod);

                while($row = oci_fetch_assoc($get_shop_prod)){

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
                        <div class='pretty  warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class='pretty  success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                }       
            
        }
}
// end of function

function get_shop() {
     
         // fetching shop name from database
         global $con;
         $get_shop= oci_parse($con, 'select * from trader');

         oci_execute($get_shop);

         while($row = oci_fetch_assoc($get_shop)){

            $trader_id = $row['TRADER_ID'];
            $shop_name = $row['SHOP_NAME'];

            echo "<li class='medium oval btn default'><a href='shop.php?shop=$trader_id'>$shop_name</a></li>";


        }
						
}
// end of function

function get_category() {
         // fetching categories from database
        global $con;
         $get_cat= oci_parse($con, 'select * from category');

         oci_execute($get_cat);

         while($row = oci_fetch_assoc($get_cat)){

            $cat_id = $row['CAT_ID'];
            $cat_title = $row['CAT_TITLE'];

            echo "<li class='medium oval btn default'><a href='shop.php?cat=$cat_id'>$cat_title</a></li>";


        }

}


?>
