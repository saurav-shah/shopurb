<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
    <title>Trader Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
</head>

<body>
    <div class="container-fluid display-table">
        <div class="row display-table-row">
            <!-- Side Menu -->
            <?php include 'includes/sidebar.php'; ?>
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
                                    <a href="" class="logout">
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
    <script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html
