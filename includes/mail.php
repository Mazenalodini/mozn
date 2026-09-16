<?php
// includes/mail.php

function sendMail($to, $subject, $body) {
    // In a real production environment, you would use PHPMailer or a similar library here.
    // For this professional demo setup, we will:
    // 1. Log the email to a file (so you can see it).
    // 2. Return true to simulate success.
    
    $logDir = __DIR__ . '/../logs';
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "=================================================\n";
    $logEntry .= "Time: $timestamp\n";
    $logEntry .= "To: $to\n";
    $logEntry .= "Subject: $subject\n";
    $logEntry .= "-------------------------------------------------\n";
    $logEntry .= $body . "\n";
    $logEntry .= "=================================================\n\n";
    
    // Append to logs/emails.txt
    file_put_contents($logDir . '/emails.txt', $logEntry, FILE_APPEND);
    
    return true;
}
