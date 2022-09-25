<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Registrati </title>
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

    if(isset($SESSION['email'])) {
      exit(header("Location: index.php"));
    } 
    //stampa messaggi di successo/errore
    if(isset($_GET['error'])) {
      $msg = $_GET['error'];
      if($msg=="personal_format")
        echo '<script> swal("Ops!", "Formato nome/cognome non valido!", "error"); </script>';
      if($msg=="email_format")
        echo '<script> swal("Ops!", "Formato email non valido!", "error"); </script>';
      elseif($msg=="password_format")
        echo '<script> swal("Ops!", "Formato password non valido!", "error"); </script>';
      elseif($msg=="diff_passwords")
        echo '<script> swal("Ops!", "Le password non coincidono!", "error"); </script>';
      elseif($msg=="email_not_available")
        echo '<script> swal("Ops!", "E-mail già registrata!", "error"); </script>';
    } 

    if (isset($_POST['submit'])) {
        if (
            !isset($_POST['firstname'])
            || !($firstname = validateInput($_POST['firstname'], $name_pattern))
            || !isset($_POST['lastname'])
            || !($lastname = validateInput($_POST['lastname'], $name_pattern))
        ) {
            exit(header("Location: registration.php?error=personal_format"));
        }
        if (
            !isset($_POST['email'])
            || empty($email = filter_var(htmlspecialchars(trim($_POST['email']), FILTER_VALIDATE_EMAIL)))
        ) { 
            exit(header("Location: registration.php?error=email_format"));
        }
        if (
            !isset($_POST['pass'])
            || !($psw = validateInput($_POST['pass'], $psw_pattern))
            || !isset($_POST['confirm'])
            || !($psw = validateInput($_POST['confirm'], $psw_pattern))
        ) {
            exit(header("Location: registration.php?error=password_format"));
        }
        if ($_POST['pass']!==$_POST['confirm']) exit(header("Location: registration.php?error=diff_passwords"));

        // calcolo dell'hash della password
        $psw = password_hash($psw, PASSWORD_DEFAULT);

        $query = "INSERT INTO utenti (nome,cognome,email,password) VALUES (?,?,?,?)";
        $stmt = safeExecQuery($query, 'ssss', [$firstname, $lastname, $email, $psw], $mysqli);
        if((!$stmt || $stmt->affected_rows != 1)) 
            exit(header("Location: registration.php?error=email_not_available"));

        //assegnazione parametri di sessione
        initSessionParams($firstname, $lastname, $email); 
        if(!isset($_GET['from'])) exit(header("Location: index.php?success=signup"));
        else exit(header("Location: ".$_GET['from']."?success=signup"));
    } ?>
<script> document.getElementById('nav_registration').classList.add('active'); </script> <!--focus navbar -->
<body>
<div class="container">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
      <br>
        <img src="images/tunos.png"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form method="POST" onsubmit="return checkRegistration(this)">
  
        <br><br>
        <p class="subtitle text-center lead fw-bold">Registrati qui</p>
          
        <!-- Nome input -->
        <div class="form-outline mb-4">
        <label class="form-label" for="firstname">Nome</label>
            <input type="text" id="firstname" class="form-control form-control-lg inputform"
              placeholder="Scrivi il tuo nome" name="firstname" required/>
        </div>

        <!-- Cognome input -->
        <div class="form-outline mb-4">
        <label class="form-label" for="lastname">Cognome</label>
            <input type="text" id="lastname" class="form-control form-control-lg inputform"
              placeholder="Scrivi il tuo cognome" name="lastname" required/>
        </div>

          <!-- Email input -->
          <div class="form-outline mb-4">
          <label class="form-label" for="email">Indirizzo email</label>
            <input type="email" id="email" class="form-control form-control-lg inputform"
              placeholder="Scrivi la tua e-mail" name="email" onchange="checkPresentEmail(this)" required/>
              <span id="emailStatus"></span>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
          <label class="form-label" for="pass">Password</label>
            <input type="password" id="pass" class="form-control form-control-lg"
              placeholder="Scrivi la tua password" name="pass" required/>
          </div>

          <!-- Conferma password input -->
          <div class="form-outline mb-3">
          <label class="form-label" for="confirm">Conferma Password</label>
            <input type="password" id="confirm" class="form-control form-control-lg"
              placeholder="Conferma la tua password" name="confirm" required/>
          </div>
            <br>
          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" name="submit" class="btn btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Registrati</button>
            <p class="lead fw-bold mt-2 pt-1 mb-0">Sei già iscritto? <a href="login.php"
                class="link-danger">Accedi</a></p>
          </div>

        </form>
      </div>
    </div>
  </div>
<?php require_once('footer.php'); ?>
</body>
</html>
