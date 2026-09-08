<?php
function envoyer_email_simulation($destinataire, $sujet, $message) {
    $date = date('Y-m-d H:i:s');
    $log = "[$date] À: $destinataire | Sujet: $sujet | Message: $message" . PHP_EOL;
    // pas convaincu
}