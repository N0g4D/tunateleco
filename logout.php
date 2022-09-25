<?php
session_start();
session_destroy();
exit(header("Location: ".$_GET['from']."?success=logout"));
?>