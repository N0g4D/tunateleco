<?php
session_start();

//inizializza le valiabili di sessionie ai valori specificati in input
function initSessionParams($firstname, $lastname, $email)
{
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;
}

?>
