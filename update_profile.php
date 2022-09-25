<?php
ob_start();
require_once('session.php');
require_once('mysql_credentials.php');
require_once('input_utils.php');

if (isset($_POST['submit'])) {
    if (isset($_POST['firstname']) && isset($_POST['lastname'])) {
        if (empty($firstname = validateInput($_POST['firstname'], $name_pattern))
            || empty($lastname = validateInput($_POST['lastname'], $name_pattern))) {
            exit(header("Location: show_profile.php?error=personal_format"));
        }
        $query = "UPDATE utenti SET nome=?, cognome=? WHERE email=?";
        if(!safeExecQuery($query, 'sss', [$firstname, $lastname, $_SESSION['email']], $mysqli)) 
            exit(header("Location: error.php"));
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        exit(header("Location: show_profile.php?success=personal_updated"));
    }
    if (isset($_POST['old-pass']) && isset($_POST['pass']) && isset($_POST['confirm'])) {
        if ( empty($old_psw = validateInput($_POST['old-pass'], $psw_pattern))
            || empty($new_psw = validateInput($_POST['pass'], $psw_pattern))
            || empty(validateInput($_POST['confirm'], $psw_pattern))) {
            exit(header("Location: show_profile.php?error=password_format"));
        }
        if($_POST['confirm']!==$_POST['pass']) exit(header("Location: show_profile.php?error=diff_passwords"));
        //ricerca dell'utente corrispondente alla mail inserita
        $query = "SELECT * FROM utenti WHERE email=?";
        $stmt = safeExecQuery($query, 's', [$_SESSION['email']], $mysqli);
        $user_data = ($stmt && !empty($res = $stmt->get_result()) && $res->num_rows === 1 ? $res->fetch_assoc() : false);
        if (empty($user_data)) {
            exit(header("Location: error.php"));
        }
        // la vecchia password inserita deve essere corretta
        if (!password_verify($old_psw, trim($user_data['password']))) {
            exit(header("Location: show_profile.php?error=password_not_correct"));
        }
        // si calcola l'hash della nuova password e la si salva nel DB
        $new_psw_hash = password_hash($new_psw, PASSWORD_DEFAULT);
        echo'<script>alert("'.$new_psw_hash.'")</script>';
        $query = "UPDATE utenti SET password=? WHERE email=?";
        if(!safeExecQuery($query, 'ss', [$new_psw_hash, $_SESSION['email']], $mysqli))
            exit(header("Location: error.php"));
        exit(header("Location: show_profile.php?success=password_updated"));
    }
}
exit(header("Location: error.php"));
?>