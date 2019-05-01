<?php
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Insert Product</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
</head>

<body>

    <div class="container">

        

            
            <div class="row justify-content-center" id="insert_form">
            <div class="col-md-8">


                <form method="post" action="insert_product.php" enctype="multipart/form-data" >

                    <fieldset>

                        <!-- Form Name -->
                        <legend>Insert Product</legend>



                        <!-- Title-->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="product_name">Product Title</label>
                            <div class="col-md-6">
                                <input id="product_name" name="prod_name" placeholder="Product Title" class="form-control" type="text" required>

                           </div>
                        </div>



                        <!-- Category -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="product_categorie">Category</label>
                            <div class="col-md-6">
                                <select id="product_category" name="prod_cat" class="form-control" required>
                                <option disabled selected>--Select a category--</option>
                                
                                <?php

						 $get_cat= oci_parse($con, 'select * from category');

						 oci_execute($get_cat);

						 while($row = oci_fetch_assoc($get_cat)){

							$cat_id = $row['CAT_ID'];
							$cat_title = $row['CAT_TITLE'];

							echo "<option value='$cat_id'>$cat_title</option>";


						  }
				            ?>

                                
                                
                                </select>
                            </div>
                        </div>

                        <!-- Stock-->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="available_quantity">Stock</label>
                            <div class="col-md-6">
                                <input id="available_quantity" name="stock" placeholder="Stock Available" class="form-control" required type="number">

                           </div>
                        </div>

                        <!-- Price-->
                        <div class="form-group row">
                            
                            <label for="price" class="col-md-4 col-form-label">Price</label>
                            <div class="col-md-6">
                                <input type="number" name="price" class="form-control" required placeholder="Product Price" id="price">
                            </div>
                            
                        </div>
                         <!-- Allergy Information-->
                        <div class="form-group row">
                            
                            <label for="ai" class="col-md-4 col-form-label">Allergy Information</label>
                            <div class="col-md-6">
                                <textarea rows="5" name="allergy" class="form-control" required placeholder="Alergy Information" id="ai"></textarea>
                            </div>
                            
                        </div>


                        <!-- Image Upload -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="filebutton">Image</label>
                           <div class="col-md-6">
                                <input id="filebutton" name="image" class="input-file" type="file" required>
                           </div>
                        </div>

                        
                        <!-- Description -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="product_description">Product Description</label>
                            <div class="col-md-6">
                                <textarea rows="5" placeholder="Product Description" class="form-control" id="product_description" name="prod_desc" required></textarea>
                            </div>
                        </div>
                        
                        <!-- Keywords -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="keywords">Keywords</label>
                            <div class="col-md-6">
                                <textarea rows="5" placeholder="Keywords" class="form-control" id="keywords" name="keywords" required></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group row justify-content-right">
                            <div class="col-md-10">
                                <button type="submit" name="insert_product" class="form-control btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </fieldset>
                </form>



            </div>

</div>
    </div>
</body>

</html>

<?php

if(isset($_POST['insert_product'])){
    
    // get form data
    
    $pname = $_POST['prod_name'];
    $cat_id = $_POST['prod_cat'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $allergy = $_POST['allergy'];
    $desc = $_POST['prod_desc'];
    $keywords = $_POST['keywords'];
    $max_order = $stock - 1;
    
    
    $image = $_FILES['image']['name'];    
    $image_temp = $_FILES['image']['tmp_name'];
    
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    
    if($check == 0) {
        
        // invalid image
        echo "<script>alert('Please Upload a Valid Image')</script>";
        

    } else {
        
        // uploading image
        move_uploaded_file($image_temp,"product_images/$image");
        
        
        $sql = "insert into product  (prod_id, prod_title, fk_cat_id, stock, prod_price,allergy_info, prod_desc,keywords,prod_img, max_order ,fk_shop_id) values  (prod_id.nextval,'$pname','$cat_id',$stock,$price,'$allergy','$desc','$keywords','$image',$max_order, 5)";
        
        
        $insert = oci_parse($con, $sql);
        oci_execute($insert);
        
        if($insert){
            
            echo "<script>alert('Product added successfully')</script>";
            
        } else {
            
            echo "<script>alert('Product could not be added. Something went wrong!')</script>";
            
        }
        
        
    }
    
    
}


?>






