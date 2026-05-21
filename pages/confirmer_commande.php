<?php
// Patch de secours au cas ouu : redirection automatique 
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
header("Location: commande.php?id=" . $id);
exit;