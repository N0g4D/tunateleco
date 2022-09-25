<?php
require_once('mysql_credentials.php');

if (isset($_POST['emailID']) && !empty($email_try = $_POST['emailID'])) {
	$query = "SELECT * FROM utenti WHERE email=?";
    $stmt = safeExecQuery($query, 's', [$email_try], $mysqli);
    $present = ($stmt && !empty($res = $stmt->get_result()) && $res->num_rows === 1) ? $res->fetch_assoc() : false;
	if ($present) echo "<span style='color:red'>E-mail già registrata!</span>";
}
?>
