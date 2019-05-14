<?php 
include 'includes/db.php';
include 'functions/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Shop</title>
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
                    <li class="link active">
                        <a href="shop_crud.php">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Shop</span>
                            <span class="pull-right label label-success hidden-xs hidden-sm"><?=get_shop_count();?></span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="product_crud.php">
                            <span class="glyphicon glyphicon-gift" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Products</span>
                            <span class="pull-right label label-success hidden-xs hidden-sm"><?=get_prod_count();?></span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="http://localhost:8080/apex/f?p=109:LOGIN_DESKTOP:30954860993183:::::">
                            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                            <span class="hidden-xs hidden-sm">Sales Report</span>

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
                        <h2 class="page_title">Manage Shop</h2>
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
                            <div class="col-md-4">
                                <table class="table table-striped table-hover" id="shop_table"></table>
                            </div>
                        </div>
                        <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <form action="add_shop.php" method="post" enctype="multipart/form-data" class="form">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Add Shop</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <!--error-->
                                                <div class="status">
                                                </div>
                                                <!--error end-->
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="sname" type="text" name="sname" placeholder="Shop Name">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" required>Add</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal fade" id="update-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <!--update form-->
                                <form action="update_shop.php" id="update-form" method="post" enctype="multipart/form-data" class="form">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Update Shop</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!--error-->
                                            <div class="status">

                                            </div>
                                            <!--error end-->
                                            <div class="form-group"> <input type="hidden" class="id" name="id">
                                                <input class="form-control sname" id="sname" type="text" name="sname" placeholder="Shop Name">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Update Shop</button>
                                        </div>
                                    </div>
                                </form>
                                <!--update-form-end-->
                            </div>
                        </div>
                        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <!--delete form-->
                                <form action="delete_shop.php" id="delete-form" method="post" enctype="multipart/form-data" class="form">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Are you sure? All products in this shop will be removed!</h4>
                                        </div>
                                        <div class="modal-body">
                                            <!--error-->
                                            <div class="status">

                                            </div>
                                            <!--error end-->
                                            <div class="form-group"> <input type="hidden" class="id" name="id">
                                                <input disabled class="form-control sname" type="text" name="sname" placeholder="Shop Name">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete Shop</button>
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
    <script src="js/custom.js"></script>
</body>

</html>
