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

<body>
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
      if($msg=="email_format")
        echo '<script> swal("Ops!", "Formato email non valido!", "error"); </script>';
      elseif($msg=="password_format")
        echo '<script> swal("Ops!", "Formato password non valido!", "error"); </script>';
      elseif($msg=="email_not_registered")
        echo '<script> swal("Ops!", "E-mail non registrata!", "error"); </script>';
      elseif($msg=="password_not_correct")
        echo '<script> swal("Ops!", "Password non corretta!", "error"); </script>';
      elseif($msg=="not_logged")
        echo '<script> swal("Nope", "Accedi per poter accedere al carrello!", "error"); </script>';
    }   

    if (isset($_POST['submit'])) {
        if (
            !isset($_POST['email'])
            || empty($email = filter_var(htmlspecialchars(trim($_POST['email']), FILTER_VALIDATE_EMAIL)))
        ) {
            exit(header("Location: login.php?error=email_format"));
        }
        if (
            !isset($_POST['pass'])
            || !($psw = validateInput($_POST['pass'], $psw_pattern))
        ) {
            exit(header("Location: login.php?error=password_format"));
        }
        //ricerca dell'utente corrispondente alla mail inserita
        $query = "SELECT * FROM utenti WHERE email=?";
        $stmt = safeExecQuery($query, 's', [$email], $mysqli);
        $user_data = ($stmt && !empty($res = $stmt->get_result()) && $res->num_rows === 1 ? $res->fetch_assoc() : false);
        if (empty($user_data)) {
            exit(header("Location: login.php?error=email_not_registered"));
        }
        if (password_verify($psw, trim($user_data['password']))) {
            initSessionParams($user_data['nome'], $user_data['cognome'], $user_data['email']);
            if(!isset($_GET['from'])) exit(header("Location: index.php?success=login"));
            else exit(header("Location: ".$_GET['from']."?success=login"));
        } else {
            exit(header("Location: login.php?error=password_not_correct"));
        }
    }

    ?>

  <div class="container">
    <div class="justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <form method="POST">
  
        <br><br>
        <p class="subtitle text-center lead fw-bold">Accedi qui</p>
          
          <!-- Email input -->
          <div class="form-outline mb-4">
            <label class="form-label" for="email">Indirizzo email</label>
            <input type="email" id="email" class="form-control form-control-lg inputform"
              placeholder="Scrivi la tua e-mail" name="email" required/>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-3">
          <label class="form-label" for="pass">Password</label>
            <input type="password" id="pass" class="form-control form-control-lg"
              placeholder="Scrivi la tua password" name="pass" required/>
          </div>

            <br>
          <div class="text-center text-lg-start mt-4 pt-2">
            <button type="submit" name="submit" value="submit" class="btn btn-lg"
              style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button> 
            <p class="lead fw-bold mt-2 pt-1 mb-0"><br>Non hai un account? <a href="registration.php"
                class="link-danger">Registrati</a></p>
          </div>

        </form>
      </div>
      <div class="text-center col-md-8 col-lg-6 col-xl-4 offset-xl-1">
      <br>
        <img src="images/tuno.png"
          class="img-fluid" alt="Sample image">
      </div>
    </div>
  </div>

<script> document.getElementById('nav_login').classList.add('active'); </script> <!--focus navbar -->

<?php require_once('footer.php'); ?>
</body>
</html>