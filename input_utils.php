<?php
    $name_pattern = "/^[\w\'\s]{1,100}$/"; 
	$psw_pattern = "/^[\w0-9]{8,}$/"; 

    function validateInput($input, $pattern) {
        $input = trim($input);
        return (preg_match($pattern, $input)) ? $input : false;
    }
?>