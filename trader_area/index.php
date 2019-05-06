<?php 
include 'includes/db.php';
include 'functions/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
    <title>Trader Dashboard</title>
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
                   <li class="link active">
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
                    <li class="link">
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
                        <h2 class="page_title">Dashboard</h2>
                    </header>
                    <div class="content-inner">
<!--                        cards start-->
                       <div class="row">
                        <div class="col-md-3">
    	    <a class="info-tiles tiles-inverse has-footer" href="#">
    		    <div class="tiles-heading">
			        <div class="pull-left">Orders</div>
			        <div class="pull-right">
			        	<div id="tileorders" class="sparkline-block"><canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas></div>
			        </div>
			    </div>
			    <div class="tiles-body">
			        <div class="text-center"><?=get_paid_order_count();?></div>
			    </div>
			    <div class="tiles-footer">
			    </div>
			</a>
    	</div>
        
                        <div class="col-md-3">
                            <a class="info-tiles tiles-green has-footer" href="#">
                                <div class="tiles-heading">
                                    <div class="pull-left">Revenues</div>
                                    <div class="pull-right">
                                        <div id="tilerevenues" class="sparkline-block"><canvas width="40" height="13" style="display: inline-block; width: 40px; height: 13px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                                <div class="tiles-body">
                                    <div class="text-center">$<?=get_revenues();?></div>
                                </div>
                                <div class="tiles-footer">
                                </div>
                            </a>
</div>    
        
        <div class="col-md-3">
        	<a class="info-tiles tiles-blue has-footer" href="#">
			    <div class="tiles-heading">
			        <div class="pull-left">Products</div>
			        <div class="pull-right">
			        	<div id="tiletickets" class="sparkline-block"><canvas width="13" height="13" style="display: inline-block; width: 13px; height: 13px; vertical-align: top;"></canvas></div>
			        </div>
			    </div>
			    <div class="tiles-body">
			        <div class="text-center"><?=get_prod_count();?></div>
			    </div>
			    <div class="tiles-footer">
			    </div>
			</a>
    	</div>
        
        <div class="col-md-3">
        	<a class="info-tiles tiles-midnightblue has-footer" href="#">
			    <div class="tiles-heading">
			        <div class="pull-left">Customers</div>
			        <div class="pull-right">
			        	<div id="tilemembers" class="sparkline-block"><canvas width="39" height="13" style="display: inline-block; width: 39px; height: 13px; vertical-align: top;"></canvas></div>
			        </div>
			    </div>
			    <div class="tiles-body">
			        <div class="text-center"><?=get_customer_count();?></div>
			    </div>
			    <div class="tiles-footer">
			    </div>
			</a>
    	</div>
                        </div>
<!--                   cards end-->
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
</body>

</html>
