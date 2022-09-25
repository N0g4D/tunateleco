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
	if($msg=="login")
	  echo '<script> swal("Benvenuto!", "Login effettuato con successo!", "success"); </script>';
	elseif($msg=="logout")
	  echo '<script> swal("A presto!", "Logout effettuato con successo!", "success"); </script>';
    elseif($msg=="signup")
	  echo '<script> swal("Il piacere è nostro!", "Registrazione effettuata con successo!", "success"); </script>';
}
?>
<script> document.getElementById('nav_home').classList.add('active'); </script> <!--focus navbar -->

<body>
<div class="container"> 
<div class="large-8 large-centered colum col-lg-12"">
                   <div class="col-xs-12">
                       <h1 class="title text-center">Tuna de Ingenieros de Telecomunicación</h1>
                       <p class="lead text-center">La tuna è una confraternita di studenti universitari che
                       cantano, suonano e viaggiano per portare <br>le canzoni tradizionali spagnole in tutto il mondo,  utilizzando strumenti a corda e a percussione <br>
                       ...anche se non tutti (pochissimi) sono davvero musicisti professionisti 😏</p>
                   </div>
               </div>
               
               <hr>
</div>

<div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="subtitle text-center">Ma come lo fanno?</h1>
                <p class="lead text-center">Indossando l'antico "pajaro", l'abbigliamento che rappresenta la loro facoltà di provenienza, i <i>Tunos</i>
                si servono <br> di tre strumenti principali per performare le loro canzoni: Chitarra, Bandurria e Pandereta!</p>
            </div>
        </div>
    </div>

<div class="container">
        <div class="row">
            <div class="col-md-4 promo-item item-1">
                <img class="img-responsive img-fluid img-thumbnail" data-action="zoom" src="images/chitarra.jpg" alt="">
                <h3 class="text-center">
                    Una chitarra flamenca
                </h3>
            </div>
            <div class="col-md-4 promo-item item-2">
                <img class="img-responsive img-fluid img-thumbnail rounded" data-action="zoom" src="images/bandurria.jpg" alt="">
                <h3 class="text-center">
                    Una bandurria
                </h3>
            </div>
        
            <div class="col-md-4 promo-item item-3">
                <img class="img-responsive img-fluid img-thumbnail" data-action="zoom" src="images/pandereta.jpg" alt="">
                <h3 class="text-center">
                    Una pandereta (sì, è un tamburello)
                </h3>
            </div>
        </div>
        <hr>
    </div>

<div class="container">
    <h1 class="subtitle text-center">Cos'è questo sito?</h1>
    <p class="lead text-center">Ti trovi in un piccolo tributo al gruppo della Tuna: più precisamente, la tuna della facoltà di Telecomunicazioni
        <br>(nella quale è compresa anche informatica!) di Valencia. 
        <br> <i>Clicca sul pulsante sottostante per accedere all'area acquisti:</i> un <strong> piccolo bonus </strong> oltre a quando si suona <br> nei ristoranti non può che 
        <strong>farci piacere</strong> ;)
    <br><button onclick="location.href='songs.php';" class="btn" id="song-btn" style="font-size: 20px;" type="submit">🎵🎵🎵</button>
    </p>
    <br>
</div>

<?php require_once('footer.php'); ?>
</body>
</html>