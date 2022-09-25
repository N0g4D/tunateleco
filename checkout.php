<?php
session_start();
ob_start();
require_once('mysql_credentials.php');
$query = "DELETE FROM carrello WHERE utente=?";
$stmt = safeExecQuery($query, 's', [$_SESSION['email']], $mysqli);
if ($stmt && $stmt->affected_rows !== -1) {
    if($stmt->affected_rows === 0) exit(header("Location: cart.php?success=buying_void"));
    exit(header("Location: cart.php?success=payment"));
}
exit(header("Location: error.php"));
?>