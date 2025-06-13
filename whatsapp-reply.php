<?php
// Ambil body JSON dari AutoResponder WA
$request = json_decode(file_get_contents("php://input"), true);

// Ambil pesan dari user
$message = strtolower(trim($request['query']['message'] ?? ''));

// Ambil CSV dari Google Spreadsheet (kamu udah publish)
$spreadsheetUrl = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQuc-dZLQ3BVBvRSY1Z5_3yf7fhePUqCGMjuCaWJ160pmL8_j676PK5gJGYj4iMT7OhdNfiBl3hFWZ9/pub?output=csv';
$csv = file_get_contents($spreadsheetUrl);

// Cek kalau berhasil diambil
if ($csv === false) {
    echo json_encode(["replies" => [["message" => "Gagal mengambil data."]]]);
    exit;
}

// Ubah CSV jadi array baris
$rows = array_map("str_getcsv", explode("\n", $csv));

// Asumsikan: Kolom A = Keyword, Kolom B = Respon
$response = "Maaf, kata kunci tidak ditemukan.";
foreach ($rows as $row) {
    if (count($row) >= 2) {
        $keyword = strtolower(trim($row[0]));
        $reply = trim($row[1]);

        if ($keyword === $message) {
            $response = $reply;
            break;
        }
    }
}

// Kirim balik ke AutoResponder
header('Content-Type: application/json');
echo json_encode([
    "replies" => [
        ["message" => $response]
    ]
]);
