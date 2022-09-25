<?php
//$mysqli = new mysqli('sql104.epizy.com', 'epiz_32633643', '7284tq19', 'epiz_32633643_tunateleco');
$mysqli = new mysqli('localhost', 'root', '', 'si');
//$mysqli = new mysqli('localhost', 'S4858043', 'ALMAsaw22!', 'S4858043');

//esegue una query tramite prepared statements
function safeExecQuery($query, $format, array $params, $conn){
	try {
		if(!($stmt = mysqli_prepare($conn, $query))) {
			throw new mysqli_sql_exception("Errore in preparazione in ".__FILE__.": (".mysqli_errno($conn).") ".mysqli_error($conn));
		}
		else if(!mysqli_stmt_bind_param($stmt, $format, ...$params)) {
			throw new mysqli_sql_exception("Errore nel binding in ".__FILE__.": (".mysqli_stmt_errno($stmt).") ".mysqli_stmt_error($stmt));
		}
		else if(!mysqli_stmt_execute($stmt)) {
			throw new mysqli_sql_exception("Errore in esecuzione in ". __FILE__ .": (".mysqli_stmt_errno($stmt).") ".mysqli_stmt_error($stmt));
		}
	} catch (mysqli_sql_exception $e) {
		error_log($e . $conn->error);
		return false;
    }
	return $stmt;
}