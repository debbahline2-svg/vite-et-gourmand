<?php
session_start();
session_destroy(); // detruit le badge
header('Location: login.php'); // On renvoie a la connec
exit;
?>