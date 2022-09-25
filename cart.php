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

<body>
<?php
ob_start();
require_once('session.php');
require_once('mysql_credentials.php');
require_once('navbar.php');

$tot_price = 0; //per il totale da pagare nel carrello

//stampa messaggi di successo/errore
if(isset($_GET['success'])) {
	$msg = $_GET['success'];
	if($msg=="payment")
	  echo '<script> swal("Grazie per l\'acquisto!", "E grazie per i soldi!", "success"); </script>';
    if($msg=="buying_void")
	  echo '<script> swal("Emmh...", "Per poter comprare le canzoni dobbiamo sapere quali vuoi!", "warning"); </script>';
}

//per togliere le canzoni singole (pulsante ❌)
if(isset($_POST['delete'])) {
    $query = "DELETE FROM carrello WHERE utente=? AND canzone=?";
    $stmt = safeExecQuery($query, 'ss', [$_SESSION['email'], $_POST['delete']], $mysqli);
    if ($stmt && $stmt->affected_rows !== 1) {
        error_log("Errore query nell'eliminare una canzone dal carrello");
        exit(header("Location: error.php"));
    }
    exit(header("Location: cart.php"));
}

//accesso limitato agli utenti
if (!isset($_SESSION['email'])) exit(header("Location: login.php?error=not_logged"));
$query = "SELECT * FROM carrello WHERE utente=?";
$stmt = safeExecQuery($query, 's', [$_SESSION['email']], $mysqli);
$res = mysqli_stmt_get_result($stmt);
if(!$res) {
    error_log("Errore query nel caricare il carrello");
    exit(header("Location: error.php"));
}
?>
<script> document.getElementById('nav_cart').classList.add('active'); </script> <!--focus navbar -->
<br>
<div class="container">
	<table id="cart" class="table table-hover table-condensed">
        <thead>
            <tr>
                <th style="width:90%">Canzoni</th>
                <th style="width:100%"></th>
                <th style="width:20%">Prezzo</th>
                <th style="width:10%"></th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($res)) { ?>
            <tr>
                <td data-th="canzone">
                    <div class="row">
                        <div class="col-sm-2 hidden-xs"><img src="images/<?php echo $row['canzone'] ?>" alt="..." class="img-responsive"/></div>
                        <div class="col-sm-10">
                        <?php 
                        //query per prendere le informazioni relative alla canzone in fase di compra
                        $query = "SELECT * FROM canzoni WHERE immagine=?";
                        $stmt2 = safeExecQuery($query, 's', [$row['canzone']], $mysqli);
                        if($stmt2 && !empty($res2 = $stmt2->get_result()) && $res2->num_rows === 1) {
                            mysqli_stmt_close($stmt2);
                            $song_data = $res2->fetch_assoc();
                        }
                        ?>
                            <h4 class="nomargin"><?php echo $song_data['titolo'] ?></h4>
                            <p><?php echo $song_data['ritornello'] ?></p>
                        </div>
                    </div>
                </td>
                <td style="width:3%">
                    <form action="cart.php" method="POST"> 
                        <button type="submit" value="<?php echo $song_data['immagine'] ?>" name="delete" class="btn">❌</button> 
                    </form>
                </td>
                <td data-th="Price"><?php echo $song_data['prezzo'] ?>€</td>
            </tr>
            <?php $tot_price += $song_data['prezzo']; } ?>
        </tbody>
        <tfoot>
            <tr>
                <td><a href="songs.php" class="btn">Continua a comprare</a></td>
                <td colspan="1" class="hidden-xs"></td>
                    <td class="hidden-xs text-center"><strong><?php echo $tot_price ?>€</strong></td>
                    <td><a href="checkout.php" class="btn btn-block">Checkout</i></a></td>
            </tr>
        </tfoot>
	</table>
</div>
<?php require_once('footer.php'); ?>
</body>
</html>