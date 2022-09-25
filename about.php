<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Tuna Teleco </title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!--per zoom immagini-->
    <link rel="stylesheet" type="text/css" href="css/zoom.css">
    <script src="js/zoom.js"></script>
</head>

<?php
require_once('session.php');
require_once('mysql_credentials.php');
require_once('navbar.php');

//stampa messaggi di successo/errore
if(isset($_GET['success'])) {
	$msg = $_GET['success'];
	if($msg=="logout")
	  echo '<script> swal("A presto!", "Logout effettuato con successo!", "success"); </script>';
}
?>
<script> document.getElementById('nav_about').classList.add('active'); </script> <!--focus navbar -->

<body>
<h1 class="text-center"> Under construction </h1>
<hr>
<div class="container" style="display: flex; align-items: center; justify-content: center; max-width: 50%">
    <div>
        <img class="img-responsive img-fluid img-thumbnail" data-action="zoom" src="images/tunateleco.jpg" alt="Immagine non disponibile">
        <h3 class="text-center">
            Tunos felici...
        </h3>
    </div>
</div>
<hr>
<h1 class="text-center"> Under construction </h1>
<?php require_once('footer.php'); ?>
</body>
</html>