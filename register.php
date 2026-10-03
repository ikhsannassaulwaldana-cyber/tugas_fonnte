<?php

require_once "config/config.php";
require_once "config/fonnte.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";

    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    if ($name === "") {

        $error = "Nama wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Email tidak valid.";

    } elseif (strlen($password) < 6) {

        $error = "Password minimal 6 karakter.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Cek email
        |--------------------------------------------------------------------------
        */

        $check = $conn->prepare(
            "SELECT id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $error = "Email sudah digunakan.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Hash password
            |--------------------------------------------------------------------------
            */

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            /*
            |--------------------------------------------------------------------------
            | Simpan user
            |--------------------------------------------------------------------------
            */

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, phone, role)
                VALUES (?, ?, ?, ?, 'user')"
            );

            $stmt->execute([
                $name,
                $email,
                $passwordHash,
                $phone
            ]);

            /*
            |--------------------------------------------------------------------------
            | Notifikasi untuk dashboard admin
            |--------------------------------------------------------------------------
            */

            $notif = $conn->prepare(
                "INSERT INTO notifications
                (user_id, title, message)
                VALUES (NULL, ?, ?)"
            );

            $notif->execute([
                "User Baru",
                "User baru telah mendaftar: {$name} ({$email})"
            ]);

            /*
            |--------------------------------------------------------------------------
            | Notifikasi WhatsApp ke admin
            |--------------------------------------------------------------------------
            */

            $message =
                "🔔 USER BARU\n\n" .
                "Ada user baru yang telah mendaftar.\n\n" .
                "Nama: {$name}\n" .
                "Email: {$email}\n" .
                "No HP: " . ($phone !== "" ? $phone : "-") . "\n\n" .
                "Silakan cek dashboard admin.";

            sendWhatsApp(
                ADMIN_PHONE,
                $message
            );

            /*
            |--------------------------------------------------------------------------
            | Redirect ke login
            |--------------------------------------------------------------------------
            */

            header(
                "Location: login.php?register=success"
            );

            exit;
        }
    }
}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<title>Register</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-body">

<h3 class="text-center">
Daftar Akun
</h3>

<hr>

<?php if ($error): ?>

<div class="alert alert-danger">

<?= e($error) ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="name"
class="form-control"
value="<?= e($_POST["name"] ?? "") ?>"
required
>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= e($_POST["email"] ?? "") ?>"
required
>

</div>

<div class="mb-3">

<label>No HP</label>

<input
type="text"
name="phone"
class="form-control"
placeholder="628xxxxxxxxxx"
value="<?= e($_POST["phone"] ?? "") ?>"
>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required
>

</div>

<button
type="submit"
class="btn btn-primary w-100"
>

Daftar

</button>

</form>

<div class="text-center mt-3">

<a href="login.php">
Sudah punya akun? Login
</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>