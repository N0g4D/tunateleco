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

<body>
<h1 class="text-center"> Qualcosa è andato storto... </h1>
<p class="text-center lead"> Si prega di riprovare più tardi </p>
<p class="text-center lead"> <a href="index.php"> Torna alla home </a> </p>
<?php require_once('footer.php'); ?>
</body>
</html>