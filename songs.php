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
    <script type="text/javascript" src="js/scripts.js"></script>

    <!--per zoom immagini-->
    <link rel="stylesheet" type="text/css" href="css/zoom.css">
    <script src="js/zoom.js"></script>
</head>

<?php
ob_start();
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
	elseif($msg=="song_added_in_cart")
	  echo '<script> swal("Ottima scelta...", "Canzone aggiunta nel carrello!", "success"); </script>';
}
if(isset($_GET['error'])) {
	$msg = $_GET['error'];
	if($msg=="searching_error")
	  echo '<script> swal("Errore database", "Ci scusiamo per il disagio. Riprovare più tardi", "error"); </script>';
	elseif($msg=="song_already_in_cart")
	  echo '<script> swal("Ops!", "Ti piace proprio questa canzone, eh? Vai al carrello per acquistarla!", "warning"); </script>';
	elseif($msg=="song_not_added")
	  echo '<script> swal("Errore database", "Impossibile aggiungere la canzone. Riprovare più tardi", "error"); </script>';
  }  
?>
<script> document.getElementById('nav_canzoniere').classList.add('active'); </script> <!--focus navbar -->

<body>
<div class="container"> 
	<div class="large-8 large-centered colum col-lg-12">
		<div class="col-xs-12">
			<h1 class="title text-center">Canzoniere ufficiale</h1>
			<?php if (isset($_SESSION['email'])) { ?>
				<p class="lead text-center">Qua troverete le canzoni attualmente consultabili della Tuna: 
				puoi visualizzare i testi e gli accordi quanto vuoi in questa pagina, ma perchè non farli <b>direttamente tuoi?</b> 
				Leggi i ritornelli che più ti aggradano sotto ogni titolo, aggiungi le canzoni al carrello e comprale per
				ottenere la versione NFT 8K da consultare quando vuoi!
				</p>
			<?php } else { ?>
				<p class="lead text-center">Qua troverete le canzoni attualmente consultabili della Tuna: 
				<a href="login.php?from=songs.php">ACCEDI</a> o <a href="registration.php?from=songs.php">REGISTRATI</a> se non sei ancora dei nostri 
				per poter comprare ufficialmente gli NFT completi delle nostre canzoni!
				</p>
			<?php } ?>
		</div>
    </div>              
</div>

<hr class="fineline">

<div class="container"> 
	<div class="large-8 large-centered colum col-lg-12 row">
		<form class="form-inline" action="songs.php" method="GET">
			<p class="lead text-center"> <strong>Filtra le canzoni:</strong>
			<input name="adv-search" id="searchinput" class="form-control mr-sm-2" type="search" placeholder="Ricordi qualcosa del ritornello?" aria-label="Search" required>
            ||
			<label for="adv-price" style="font-size: 16px;"><i>Prezzo soglia massima: </i> </label>
			<input id="adv-price" placeholder="€" min="0.00" class="mr-sm-2" step=".01" style="max-width: 7%" name="adv-price" type="number" required> 
			||
			<label for="adv-order" style="font-size: 16px;"><i>Ordina per prezzo: </i> </label>
			<select name="adv-order" id="adv-order">
				<option value="asc">Ascendente</option>
				<option value="desc">Discendente</option>
			</select>
			<button id="adv-search-btn" class="btn btn-outline-success my-2 my-sm-0" type="submit">Ricerca avanzata</button>
			</p>
		</form>
    </div>              
</div>

	<div class="catalog text-center">
		<?php
		$query = "SELECT * FROM canzoni";
		//se non è stato cercato nulla
		if(!isset($_GET['search']) && !isset($_GET['adv-search'])){
			$res = mysqli_query($mysqli,$query);
		}
		else { 
			//ricerca navbar
			if(isset($_GET['search']) && !empty($search = $_GET["search"])){ 
				$query .= " WHERE titolo LIKE ? OR immagine LIKE CONCAT(?,'%')"; 
				$stmt = safeExecQuery($query, "ss", [$search, $search], $mysqli);
				$res = mysqli_stmt_get_result($stmt);
			}
			//ricerca avanzata
			elseif(!empty($search = $_GET["adv-search"]) && !empty($price = floatval($_GET["adv-price"])) && !empty($order = $_GET["adv-order"])) { 
				if($order!=="desc") $order = "asc"; //per prevenire ordini pericolosi... 
				$query .= " WHERE (ritornello LIKE CONCAT('%',?,'%') 
							OR titolo LIKE CONCAT('%',?,'%')) 
							AND prezzo <= ?  
							ORDER BY prezzo ".$order;
				$stmt = safeExecQuery($query, "ssd", [$search, $search, $price], $mysqli);
				$res = mysqli_stmt_get_result($stmt);
				mysqli_stmt_close($stmt);
			}          
		}
		
		if(!$res) {
			error_log("Query error in browsing");
			exit(header("Location: songs.php?error=searching_error"));
		}
		else {
			if(mysqli_num_rows($res)==0)
						echo '<div class="msg"><p class="msg">Nessun canzone trovata...</p></div>';
            $counter_row = 0;
            echo '<div class="container-fluid"><form method="POST" action="songs.php">';
			while($row = mysqli_fetch_assoc($res)) {
                if($counter_row%3===0) echo '<div class="row">';
                echo '
                    <div class="col-md-6 songtainer">
                        <a>
                        <img class="img-responsive img-thumbnail img-fluid songimage" data-action="zoom" src="images/'.$row["immagine"].'" alt="">
                        </a>
                        <h5>
                        <p class="subtitle" style="font-size: 30px">
							'.$row["titolo"].' - '.$row["prezzo"].'€ 
							';
							if (isset($_SESSION['email'])) echo '
							<div class="input-group">
								<span class="input-group-btn">
									<button value="'.$row["immagine"].'" class="btn" type="submit" name="add_to_cart">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
										<path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
										</svg> 
										Aggiungi
									</button>
								</span>
							</div>
							';
						echo '
						</p>
                        </h5>
                        <p>🎶'.$row["ritornello"].'🎶
						</p>
                    </div>
                ';
                $counter_row++;
                if($counter_row%3===0) echo '</div>';
			}
            echo '</form></div>';
			mysqli_free_result($res);
		}

		if (isset($_POST['add_to_cart'])) {
			if (!empty($songname = trim($_POST['add_to_cart']))) {
				echo '<script>alert("'.$songname.'")</script>';
				$query = "SELECT * FROM carrello WHERE utente=? AND canzone=?";
				$stmt = safeExecQuery($query, 'ss', [$_SESSION['email'], $songname], $mysqli);
    			if ($stmt && $stmt->get_result()->num_rows === 1) 
					exit(header("Location: songs.php?error=song_already_in_cart"));
				$query = "INSERT INTO carrello VALUES (?,?)";
				$stmt = safeExecQuery($query, 'ss', [$_SESSION['email'], $songname], $mysqli);
				if (!$stmt || $stmt->affected_rows != 1) 
					exit(header("Location: songs.php?error=song_not_added"));
				exit(header("Location: songs.php?success=song_added_in_cart")); 
			}
		}

		?>
	</div>
</body>
<?php require_once('footer.php'); ?>
</html>