<?php
/**
 * Legacy contact handler (optional for PHP hosting).
 *
 * NOTE:
 * The site currently uses FormSubmit AJAX from the frontend for static hosting.
 * This file is kept as a fallback for environments where PHP is enabled.
 */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Method Not Allowed';
  exit;
}

$to = 'contact@navidniknezhad.me';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? 'Portfolio Contact Form');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo 'Invalid input.';
  exit;
}

$safe_subject = preg_replace('/[\r\n]+/', ' ', $subject);
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$body = "New message from portfolio contact form\n\n";
$body .= "Name: {$name}\n";
$body .= "Email: {$email}\n";
$body .= "Subject: {$safe_subject}\n\n";
$body .= "Message:\n{$message}\n";

if (@mail($to, $safe_subject, $body, $headers)) {
  echo 'OK';
} else {
  http_response_code(500);
  echo 'Unable to send email.';
}
