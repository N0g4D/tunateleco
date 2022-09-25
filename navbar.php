<?php
//query per sapere le canzoni nel carrello dell'utente loggato
$num_songs = 0;
if (isset($_SESSION['email'])) {
    $query = "SELECT COUNT(*) AS 'num' FROM carrello WHERE utente=?";
    $stmt = safeExecQuery($query, 's', [$_SESSION['email']], $mysqli);
    if ($stmt && !empty($res = $stmt->get_result())) $num_songs = $res->fetch_assoc();
    $num_songs = $num_songs['num'];
}
?>
<script src="js/bootstrap.min.js"></script> <!-- per lo script collapse -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- sweetalert -->
<nav class="navbar navbar-fixed-top" style="background-color: rgb(129, 43, 131);" role="navigation">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a id="nav_home" class="navbar-brand" href="index.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-music" viewBox="0 0 16 16">
            <path d="M11 6.64a1 1 0 0 0-1.243-.97l-1 .25A1 1 0 0 0 8 6.89v4.306A2.572 2.572 0 0 0 7 11c-.5 0-.974.134-1.338.377-.36.24-.662.628-.662 1.123s.301.883.662 1.123c.364.243.839.377 1.338.377.5 0 .974-.134 1.338-.377.36-.24.662-.628.662-1.123V8.89l2-.5V6.64z"/>
            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
            </svg>
            Tuna Teleco
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                <a id="nav_about" class="option" href="about.php">Su di noi</a>
                </li>
                <li>
                <a id="nav_canzoniere" href="songs.php">Canzoniere</a>
                </li>
                <?php if (isset($_SESSION['email'])) { ?>
                    <li>
                    <a href="logout.php?from=<?php echo strstr($_SERVER['SCRIPT_NAME'],'/') ?>">Esci</a>
                    </li>
                <?php } else { ?>
                    <li>
                    <a id="nav_login" href="login.php">Accedi</a>
                    </li>
                    <li>
                    <a id="nav_registration" href="registration.php">Registrati</a>
                    </li>
                <?php } ?>
                <li>
                <form class="form-inline" action="songs.php">
                    <input name="search" id="searchinput" class="form-control mr-sm-2" type="search" placeholder="Cerca una canzone..." aria-label="Search" required>
                    <button id="search-btn" class="btn btn-outline-success my-2 my-sm-0" type="submit">🔎</button>
                </form>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['email'])) {
                echo '
                    <li>
                    <h4 id="welcome-msg"> Ciao, <a href="show_profile.php"> '.$_SESSION['firstname'].' </a> </h4>
                    </li>   
                '; } ?>    
                <li>
                <a href="cart.php" id="nav_cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart2" viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                    </svg>
                    Carrello <h7 id="cartelems">0</h7>
                </a>  
                </li>
            </ul>
        </div>

    </div>

</nav>

<!-- n di canzoni del carrello dell'utente -->
<script> document.getElementById('cartelems').textContent="<?php echo $num_songs ?>"; </script>