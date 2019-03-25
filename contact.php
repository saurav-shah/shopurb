<!DOCTYPE html>
<html>
<head>
<title>CONTACT US</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="main.js"></script>
    <link rel="stylesheet" href="gumby/css/gumby.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/contact.css" />
    <script src="../gumby/js/libs/modernizr-2.6.2.min.js"></script>
</head>
<body>
<div id="heading">
        <h1>SHOPURB</h1>
    </div>
    <?php include('navbar.php');
    include('searchetc.php');?> 
<div id="wrapper">
    <div class="rows">
    <header>
    <h2>CONTACT US</h2>
    </header>
    </div>

    <div class="row">
        <div class="six columns">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d73101.14200218451!2d-1.825212318534018!3d53.
                686985774811674!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487962132bcdb7bb%3A0x653c3a498c896a17!
                 2sHuddersfield%2C+UK!5e0!3m2!1sen!2snp!4v1552878973239" 
                 width="450" height="400" frameborder="0" style="border:0" allowfullscreen>
                </iframe>
</div>
 <div class="six columns">
    <h3>LEAVE A MESSAGE</h3>
    <br/>
                <form>
                    <input name="name" id="name" type="text" placeholder="Name*" required autocomplete="off"><br/>
                    <input name="email" id="email" type="email" placeholder="Email Address*" required autocomplete="off">
                    <br/>
                    <textarea name="message" id="message" placeholder="Message" required rows="4"></textarea><br/>
                    <div class="medium default btn" id="button"><input id="submit" type="submit" value="Send" /></div>
                </form>
</div>
    </div>
</div>
<br/>
<footer>
<?php include('footer.php')?>
</footer>
</body>
</html>