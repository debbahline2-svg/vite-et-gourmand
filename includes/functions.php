function envoyer_email_simulation($destinataire, $sujet, $message) {
    $date = date('Y-m-d H:i:s');
    $log = "[$date] À: $destinataire | Sujet: $sujet | Message: $message" . PHP_EOL;
    // On écrit dans ton fichier logs_emails.txt
    file_put_contents(__DIR__ . '/../pages/logs_emails.txt', $log, FILE_APPEND);
}