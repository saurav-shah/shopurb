<?php
@session_start();
if(isset($_SESSION['trader_id'])) {
    header('location: trader_area/index.php');
}

// Getting all the products
function get_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(!isset($_GET['cat'])){ // If the get category variable is not set
            
            
            if(!isset($_GET['shop'])){ // If the get shop variable is not set
                
                 $sql = "select * from (select * from product order by dbms_random.value) where rownum <= 9 and prod_status = 'active'";

                $get_prod = oci_parse($con, $sql);

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
                        <div class=' small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class=' small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                }       
            }
        }
}


// Getting products by category
function get_cat_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(isset($_GET['cat'])){ // If the get category variable is set
            
                $cat_id = $_GET['cat'];            
                
                 $sql = "select * from product where fk_cat_id = $cat_id and prod_status = 'active'";

                $get_cat_prod = oci_parse($con, $sql);

                oci_execute($get_cat_prod);
                $num_rows = oci_fetch_all($get_cat_prod,$out);
                if($num_rows != 0) {
                    oci_execute($get_cat_prod);
                while($row = oci_fetch_assoc($get_cat_prod)){

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
                        <div class=' small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class=' small success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";


                } 
                }
            else{
                echo '<center><h3>NO Products Found in this Category!</h3><center>';
            }
            
                      
            
        }
}


// Getting products by shop_name
function get_shop_products() {  
    
        //fetching 9 random products from database

        global $con; // $con variable remains outside the scope of function unless made global
    
        if(isset($_GET['shop'])){ // If the get shop variable is set
            
                $shop_id = $_GET['shop'];            
                
                 $sql = "select * from product where fk_shop_id = $shop_id and prod_status = 'active'";

                $get_shop_prod = oci_parse($con, $sql);

                oci_execute($get_shop_prod);
            $num_rows = oci_fetch_all($get_shop_prod,$out);
            if($num_rows != 0){
                
                oci_execute($get_shop_prod);
                
                while($row = oci_fetch_assoc($get_shop_prod)){

                    $prod_id = $row['PROD_ID'];
                    $prod_title = $row['PROD_TITLE'];
                    $prod_img = $row['PROD_IMG'];
                    $prod_price = $row['PROD_PRICE'];
                    $discount = $row['DISCOUNT'];
                    
                    if($discount != 0) {
                       
                        $new_price = $prod_price - (($discount / 100) * $prod_price);
                        
                         echo "


                 <div class='product_box'>

                    <center><div class='primary badge'>$prod_title</div></center>

                    <img src='trader_area/product_images/$prod_img' alt='$prod_img'>

                    <center>
                        <p><div class='default badge'>Price: <s>$$prod_price</s> &nbsp;&nbsp; $$new_price</div></p>
                        <div class='small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class='small  success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";
                    }

                else {
                     echo "


                 <div class='product_box'>

                    <center><div class='primary badge'>$prod_title</div></center>

                    <img src='trader_area/product_images/$prod_img' alt='$prod_img'>

                    <center>
                        <p><div class='default badge'>Price: $$prod_price</div></p>
                        <div class='small warning btn'><a href='shop.php?add_cart=$prod_id'>Add to Cart</a></div>

                        <div class='small  success btn'><a href='details.php?pro_id=$prod_id'>Details</a></div>
                    </center>

                </div>

                ";
                }
                   


                }  
            }
            else {
                echo '<center><h3>No Products Found in this Shop!</h3></center>';
            }     
            
        }
}


// Listing all shop_name
function get_shop() {
     
         // fetching shop name from database
         global $con;
         $get_shop= oci_parse($con, 'select * from shop');

         oci_execute($get_shop);

         while($row = oci_fetch_assoc($get_shop)){

            $shop_id = $row['SHOP_ID'];
            $shop_name = $row['SHOP_NAME'];

            echo "<li class='medium oval btn default'><a href='shop.php?shop=$shop_id'>$shop_name</a></li>";


        }
						
}


// Listing all category
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



function cart() {
    global $con;
    if(isset($_GET['add_cart'])) {
       
        if(isset($_SESSION['cust_id'])){
            
            $p_id = $_GET['add_cart'];
            
            $qry = 'select * from product where prod_id = '.$p_id.'';
            $get = oci_parse($con, $qry);
            oci_execute($get);
            while($data = oci_fetch_assoc($get)) {
                $stock = $data['STOCK'];
                $min_order = $data['MIN_ORDER'];
                
                if($stock > 1) {
                    $sql = 'select * from cart where user_id = '.$_SESSION['cust_id'].' and prod_id = '.$p_id.'';
                    $prep = oci_parse($con, $sql);
                    oci_execute($prep);
                    $num_rows = oci_fetch_all($prep, $out);
            
                    if($num_rows == 0) {
                    $sql = 'insert into cart (prod_id, user_id, quantity) values ('.$p_id.','.$_SESSION['cust_id'].','.$min_order.')';
                    $prep = oci_parse($con, $sql);
                    oci_execute($prep);
                    echo '<center class = "secondary alert">Item added to cart</center>';
                    echo "<script>window.open('shop.php','_self')</script>";
               
                    }
                    else {
                         echo '<center class = "warning alert">Item already exists in cart</center>';
                    }
                }
                else {
                    echo '<center class = "warning alert">Could not add to cart. Product out of stock.</center>';
                }
                
            }
            
            
            
               
            }
        else {
                  echo '<center class = "warning alert">You must be logged in to add to cart!</center>';
                 
            }
            
    } 
       
    
    
    
}

function cart_items() {
    global $con;
    if(isset($_SESSION['cust_id'])) {
        $sql = 'select * from cart where user_id = '.$_SESSION['cust_id'].'';
        $prep = oci_parse($con, $sql);
        oci_execute($prep);
        $num_rows = oci_fetch_all($prep, $out);        
        return $num_rows;     
    }
    else {
        return 0;
    }
         
}


function get_slots() {
	
    $date = date("Y-m-d");

    $day = date("D",strtotime($date));
    
    switch($day) {


      case "Sun":  
      $a= strtotime($date."+ 3 days");
      $b= strtotime($date."+ 4 days");
      $c= strtotime($date."+ 5 days");  
      break;

      case "Mon":  
      $a= strtotime($date."+ 2 days");
      $b= strtotime($date."+ 3 days");
      $c= strtotime($date."+ 4 days");
      break;

      case "Tue":
      $a= strtotime($date."+ 1 days");
      $b= strtotime($date."+ 2 days");
      $c= strtotime($date."+ 3 days");
      break;

      case "Wed": 
      $a= strtotime($date."+ 1 days");
      $b= strtotime($date."+ 2 days");
      $c= strtotime($date."+ 7 days");
      break;

      case "Thu":
      $a= strtotime($date."+ 1 days");
      $b= strtotime($date."+ 6 days");
      $c= strtotime($date."+ 7 days");
      break;

      case "Fri":
      $a= strtotime($date."+ 5 days");
      $b= strtotime($date."+ 6 days");
      $c= strtotime($date."+ 7 days");
      break;

      case "Sat":
      $a= strtotime($date."+ 4 days");
      $b= strtotime($date."+ 5 days");
      $c= strtotime($date."+ 6 days");
      break;
  
    }

    $slots = array(date("l, d F Y",$a), date("l, d F Y",$b), date("l, d F Y",$c));

    return $slots;	
}




?>
