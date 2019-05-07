<?php 
include 'includes/db.php';
include 'functions/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<title>Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
<div class="container-fluid display-table">
<div class="row display-table-row">
<!-- Side Menu -->
<div class="col-md-2 col-sm-1 hidden-xs display-table-cell valign-top" id="side-menu">
<h1 class="hidden-xs hidden-sm"><?=$p_line?></h1>
<ul>
<li class="link">
<a href="index.php">
<span class="glyphicon glyphicon-th" aria-hidden="true"></span>
<span class="hidden-xs hidden-sm">Dashboard</span>
</a>
</li>
<li class="link">
<a href="profile.php">
<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
<span class="hidden-xs hidden-sm">Profile</span>
</a>
</li>
<li class="link">
<a href="shop_crud.php">
<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
<span class="hidden-xs hidden-sm">Shop</span>
<span class="pull-right label label-success hidden-xs hidden-sm"><?=get_shop_count();?></span>
</a>
</li>
<li class="link active">
<a href="product_crud.php">
<span class="glyphicon glyphicon-gift" aria-hidden="true"></span>
<span class="hidden-xs hidden-sm">Products</span>
<span class="pull-right label label-success hidden-xs hidden-sm"><?=get_prod_count();?></span>
</a>
</li>

</ul>
</div>
<!-- Main Content-->
<div class="col-md-10 col-sm-11 display-table-cell valign-top">
<div class="row">

<header id="nav-header" class="clearfix">
<div class="col-md-5">

<nav class="pull-left navbar-default">

<button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#slide-menu">
<span class="sr-only">Toggle navigtion</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>

</nav>

</div>
<div class="col-md-7">
<ul class="pull-right">
<li id="welcome">Trader Area</li>

<li>
<a href="logout.php" class="logout">
<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
<span>logout</span>
</a>
</li>
<li>

</li>
</ul>
</div>
</header>
</div>
<div id="content">
<header>
<h2 class="page_title">Manage Product</h2>
</header>
<div class="content-inner">


<div class="row">
<div class="col-md-5">
<a data-toggle="modal" data-target="#add" href="" class="btn btn-success btn-sm">
<i class="glyphicon glyphicon-plus"></i>
Add</a>
</div>

</div>
<div class="row">
<div class="col-md-12">
<!--product table-->
<table class="table table-striped table-hover table-responsive" id="prod_table"></table>
<!--product table end--> 
</div> 
</div>
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<!--add form-->
<form action="add_prod.php" method="post" enctype="multipart/form-data" class="add_update">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Add Product</h4>
</div>
<div class="modal-body">

<div class="form-group">

<!--error-->
<div class="status"></div>
<!--error end-->
</div>
<!-- Title-->
<div class="form-group ">
<label for="product_name">Product Title</label>

<input id="product_name" name="prod_name" placeholder="Product Title" class="form-control" type="text" required>


</div>
<!-- Category -->
<div class="form-group ">
<label for="product_category">Category</label>

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
<!-- Shop -->
<div class="form-group ">
<label for="shop">Shop</label>

<select id="shop" name="shop_id" class="form-control" required>
<option disabled selected>--Choose a Shop--</option>

<?php

$get_shop= oci_parse($con, "select * from shop where fk_trader_id = $trader_id");

oci_execute($get_shop);

while($row = oci_fetch_assoc($get_shop)){

$shop_id = $row['SHOP_ID'];
$shop_name = $row['SHOP_NAME'];

echo "<option value='$shop_id'>$shop_name</option>";


}
?>
</select>
</div>
<!-- Stock-->
<div class="form-group ">
<label  for="available_quantity">Stock</label>

<input id="available_quantity" min="1" name="stock" placeholder="Stock Available" class="form-control" required type="number">

</div>
<!-- Price-->
<div class="form-group ">

<label for="price" >Price</label>

<input min="1" type="number" name="price" class="form-control" required placeholder="Product Price" id="price">


</div>
<!-- Allergy Information-->
<div class="form-group ">

<label for="ai">Allergy Information</label>

<textarea rows="5" name="allergy" class="form-control" required placeholder="Alergy Information" id="ai"></textarea>


</div>
<!-- Image Upload -->
<div class="form-group ">
<label  for="filebutton">Image</label>

<input id="filebutton" name="image" class="input-file" type="file" required multiple>

</div>
<!-- Description -->
<div class="form-group ">
<label  for="product_description">Product Description</label>

<textarea rows="5" placeholder="Product Description" class="form-control" id="product_description" name="prod_desc" required></textarea>

</div>
<!-- Keywords -->
<div class="form-group">
<label  for="keywords">Keywords</label>

<textarea rows="5" placeholder="Keywords" class="form-control" id="keywords" name="keywords" required></textarea>

</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-success submitBtn" required>Add</button>
</div>
</div>
</form>
<!--add form end-->
</div>
</div>
<div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<!--update form-->
<form action="update_prod.php" class="add_update" method="post" enctype="multipart/form-data">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Update Product</h4>
</div>
<div class="modal-body">

<div class="form-group"><input type="hidden" class="id" name="id">

<!--error-->
<div class="status"></div>
<!--error end-->
</div>
<!-- Title-->
<div class="form-group ">
<label for="product_name">Product Title</label>

<input id="product_name" name="prod_name" placeholder="Product Title" class="form-control pname" type="text" required>


</div>
<!-- Category -->
<div class="form-group ">
<label for="product_category">Category</label>

<select id="product_category" name="prod_cat" class="form-control cat" required>
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
<!-- Shop -->
<div class="form-group ">
<label for="shop">Shop</label>

<select id="shop" name="shop_id" class="form-control shop" required>
<option disabled selected>--Choose a Shop--</option>

<?php

$get_shop= oci_parse($con, "select * from shop where fk_trader_id = $trader_id");

oci_execute($get_shop);

while($row = oci_fetch_assoc($get_shop)){

$shop_id = $row['SHOP_ID'];
$shop_name = $row['SHOP_NAME'];

echo "<option value='$shop_id'>$shop_name</option>";


}
?>
</select>
</div>
<!-- Stock-->
<div class="form-group ">
<label  for="available_quantity">Stock</label>

<input id="available_quantity" min="1" name="stock" placeholder="Stock Available" class="stock form-control" required type="number">

</div>
<!-- Price-->
<div class="form-group ">

<label for="price" >Price</label>

<input min="1" type="number" name="price" class="price form-control" required placeholder="Product Price" id="price">


</div>
<!-- Min Order-->
<div class="form-group ">

<label for="min" >Minimum Order</label>


<input min="1" type="number" name="min_order" class="min_order form-control" required placeholder="Min Order" id="min">


</div>
<!-- Max Order-->
<div class="form-group ">

<label for="max" >Maximum Order</label>

<input min="1" type="number" name="max_order" class="max_order form-control" required placeholder="Max Order" id="max">


</div>
<!-- Discount-->
<div class="form-group ">

<label for="dis" >Discount Percent</label>

<input min="0" max="100" type="number" name="discount" class="discount form-control" required placeholder="Discount" id="dis">


</div>
<!-- Allergy Information-->
<div class="form-group ">

<label for="ai">Allergy Information</label>

<textarea rows="5" name="allergy" class="a_info form-control" required placeholder="Alergy Information" id="ai"></textarea>


</div>
<!-- Image Upload -->
<div class="form-group ">
<label  for="filebutton">Image</label>

<input id="filebutton" name="image" class="input-file" type="file">

</div>
<!-- Description -->
<div class="form-group ">
<label  for="product_description">Product Description</label>

<textarea rows="5" placeholder="Product Description" class="desc form-control" id="product_description" name="prod_desc" required></textarea>

</div>
<!-- Keywords -->
<div class="form-group">
<label  for="keywords">Keywords</label>

<textarea rows="5" placeholder="Keywords" class="tags form-control" id="keywords" name="keywords" required></textarea>

</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-success">Update</button>
</div>
</div>
</form>
<!--update-form-end-->
</div>
</div>
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<!--delete form-->
<form action="delete_prod.php"  id="delete-form" method="post" enctype="multipart/form-data" class="form">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Are you sure? This cannot be undone!</h4>
</div>
<div class="modal-body">
<!--error-->
<div class="status">

</div>
<!--error end-->
<div class="form-group"> <input type="hidden" class="id" name="id">
<input disabled class="form-control pname" type="text" name="pname" placeholder="Product Title">
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger">Delete</button>
</div>
</div>
</form>
<!--delete-form-end-->
</div>
</div>

</div>

</div>
<div class="row">
<footer id="footer" class="clearfix">
<div class="pull-left"><b>Copyright</b> &copy; 2019</div>
<div class="pull-right">SHOPURB</div>
</footer>
</div>
</div>
</div>
</div>


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/main.js"></script>
<script src="js/product.js"></script>
</body>

</html>
