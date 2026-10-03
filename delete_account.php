<?php

require_once "config/config.php";
require_once "config/fonnte.php";

login_required();

$user = $_SESSION["user"];

/*
|--------------------------------------------------------------------------
| Simpan data user sebelum akun dihapus
|--------------------------------------------------------------------------
*/

$name = $user["name"];
$email = $user["email"];
$phone = $user["phone"] ?? "";

/*
|--------------------------------------------------------------------------
| Kirim notifikasi WhatsApp
|--------------------------------------------------------------------------
*/

if (!empty($phone)) {

    $message =
        "Halo {$name},\n\n" .
        "Akun Anda telah dihapus.\n\n" .
        "Email: {$email}\n\n" .
        "Terima kasih telah menggunakan layanan kami.";

    sendWhatsApp(
        $phone,
        $message
    );
}

/*
|--------------------------------------------------------------------------
| Hapus akun dari database
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "DELETE FROM users WHERE id = ?"
);

$stmt->execute([
    $user["id"]
]);

/*
|--------------------------------------------------------------------------
| Hapus session
|--------------------------------------------------------------------------
*/

session_unset();
session_destroy();

/*
|--------------------------------------------------------------------------
| Redirect ke login
|--------------------------------------------------------------------------
*/

header("Location: login.php?deleted=success");
exit;