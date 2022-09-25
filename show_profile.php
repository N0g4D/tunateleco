<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Accedi </title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script type="text/javascript" src="js/scripts.js"></script>
</head>

    <?php
    ob_start();
    require_once('session.php');
    require_once('mysql_credentials.php');
    require_once('input_utils.php');
    require_once('navbar.php');

    //stampa messaggi di successo/errore
    if(isset($_GET['success'])) {
        $msg = $_GET['success'];
        if($msg=="personal_updated")
        echo '<script> swal("Fatto!", "Dati personali modificati!", "success"); </script>';
        elseif($msg=="password_updated")
        echo '<script> swal("Fatto!", "Password modificata!", "success"); </script>';
    }
    if(isset($_GET['error'])) {
        $msg = $_GET['error'];
        if($msg=="personal_format")
        echo '<script> swal("Ops!", "Formato nome/cognome non valido!", "error"); </script>';
        elseif($msg=="password_format")
        echo '<script> swal("Ops!", "Formato password non valido!", "error"); </script>';
        elseif($msg=="password_not_correct")
        echo '<script> swal("Ops!", "La tua vecchia password non è corretta!", "error"); </script>';
        elseif($msg=="diff_passwords")
        echo '<script> swal("Ops!", "Le password non coincidono!", "error"); </script>';
    } 

    if (!isset($_SESSION['email'])) exit(header("Location: index.php"));
    else {
        $query = "SELECT * FROM utenti WHERE email=?";
        $stmt = safeExecQuery($query, 's', [$_SESSION['email']], $mysqli);
        $user_data = ($stmt && !empty($res = $stmt->get_result()) && $res->num_rows === 1 ? $res->fetch_assoc() : NAN);
        if (empty($user_data)) {
            exit(header("Location: error.php"));
        }

    }
    ?>

<body>
    <div class="container"> 
        <div class="large-8 large-centered colum col-lg-12">
            <div class="title col-xs-12">
                <h1 id="title" class="text-center">Il tuo profilo</h1>
            </div>
        </div>              
    </div>

<hr class="fineline">

<div class="container text-center">
    <div class="justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5"> 
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img style="padding-top: 50px;" class="rounded-circle mt-5" src="images/Capa.gif">
            </div> 
        </div> 
        <div class="col-md-5 border-right"> 
            <form method="POST" action="update_profile.php">
            <div class="p-3 py-5"> 
                <div class="row mt-2"> 
                    <div class="col-md-6"><label for="firstname" class="bg-label">Nome</label><input id="firstname" name="firstname" type="text" class="form-control" value="<?php echo $_SESSION['firstname'] ?>"></div> 
                    <div class="col-md-6"><label for="lastname" class="bg-label">Cognome</label><input id="lastname" name="lastname" type="text" class="form-control" value="<?php echo $_SESSION['lastname'] ?>"></div> 
                </div> <br>
                <div class="row mt-3"> 
                    <div class="col-md-12"><label for="email" class="bg-label">E-mail</label><input id="email" name="email" type="email" class="form-control text-center"value="<?php echo $_SESSION['email'] ?>" readonly><br></div> 
                    <button class="btn profile-button" name="submit" type="submit">Modifica dati</button>
                </div>
            </form>
            <hr>
            <form method="POST" action="update_profile.php">
                <div class="row mt-1"> 
                    <div class="col-md-12"><label for="old-pass" class="bg-label">Vecchia password</label><input id="old-pass" name="old-pass" type="password" class="form-control"><br></div> 
                    <div class="col-md-12"><label for="pass" class="bg-label">Nuova password</label><input id="pass" name="pass" type="password" class="form-control"><br></div>
                    <div class="col-md-12"><label for="confirm" class="bg-label">Conferma nuova password</label><input id="confirm" name="confirm" type="password" class="form-control"></div>
                </div>  <br>
                <div class="mt-5 text-center">
                    <button class="btn profile-button" name="submit" type="submit">Modifica password</button>
                </div> 
            </div> 
            </form>
        </div> 
    </div>
</div>
<br>
<?php require_once('footer.php'); ?>
</body>
</html>