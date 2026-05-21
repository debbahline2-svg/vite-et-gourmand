<?php
// Patch de secours : redirection automatique et transparente
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
header("Location: commande.php?id=" . $id);
exit;