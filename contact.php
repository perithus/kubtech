<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    kubtech_json_response(['message' => 'Dozwolone jest tylko zadanie POST.'], 405);
}

$isJson = stripos((string) ($_SERVER['CONTENT_TYPE'] ?? ''), 'application/json') !== false;
$payload = $isJson
    ? json_decode((string) file_get_contents('php://input'), true)
    : $_POST;

if (!is_array($payload)) {
    kubtech_json_response(['message' => 'Niepoprawny format danych.'], 422);
}

if (trim((string) ($payload['company'] ?? '')) !== '') {
    kubtech_json_response(['message' => 'Dziekujemy za wiadomosc.'], 200);
}

$fullName = trim((string) ($payload['fullName'] ?? ''));
$phone = trim((string) ($payload['phone'] ?? ''));
$email = trim((string) ($payload['email'] ?? ''));
$projectType = trim((string) ($payload['projectType'] ?? ''));
$location = trim((string) ($payload['location'] ?? ''));
$message = trim((string) ($payload['message'] ?? ''));
$requiredConsent = !empty($payload['requiredConsent']);
$marketingConsent = !empty($payload['marketingConsent']);

if ($fullName === '' || $phone === '' || $message === '' || !$requiredConsent) {
    kubtech_json_response(['message' => 'Uzupelnij imie i nazwisko, telefon, opis projektu i zgode na kontakt.'], 422);
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    kubtech_json_response(['message' => 'Podaj poprawny adres e-mail.'], 422);
}

$subject = 'Nowe zapytanie ze strony KUB-TECH';
$bodyLines = [
    'Nowe zapytanie ze strony internetowej KUB-TECH',
    '',
    'Imie i nazwisko: ' . $fullName,
    'Telefon: ' . $phone,
    'E-mail: ' . ($email !== '' ? $email : 'brak'),
    'Rodzaj zabudowy: ' . ($projectType !== '' ? $projectType : 'nie podano'),
    'Lokalizacja: ' . ($location !== '' ? $location : 'nie podano'),
    '',
    'Opis projektu:',
    $message,
    '',
    'Zgoda na kontakt: tak',
    'Zgoda marketingowa: ' . ($marketingConsent ? 'tak' : 'nie'),
    '',
    'Data wysylki: ' . date('Y-m-d H:i:s'),
    'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'nieznane'),
];

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
];

if ($email !== '') {
    $headers[] = 'Reply-To: ' . $email;
}

$sent = mail(
    KUBTECH_CONTACT_EMAIL,
    '=?UTF-8?B?' . base64_encode($subject) . '?=',
    implode("\r\n", $bodyLines),
    implode("\r\n", $headers)
);

if (!$sent) {
    kubtech_json_response([
        'message' => 'Nie udalo sie wyslac wiadomosci. Sprawdz konfiguracje funkcji mail() na serwerze albo ustaw wysylke SMTP.',
    ], 500);
}

kubtech_json_response([
    'message' => 'Dziekujemy. Wiadomosc zostala wyslana, odezwiemy sie najszybciej jak to mozliwe.',
]);
