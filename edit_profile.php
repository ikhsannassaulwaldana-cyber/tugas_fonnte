<?php

require_once "config/config.php";
require_once "config/fonnte.php";

login_required();

$user = $_SESSION["user"];

$error = "";


/*
|--------------------------------------------------------------------------
| Proses update
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Menentukan data yang berubah
    |--------------------------------------------------------------------------
    */

    $changed = [];

    if ($name !== $user["name"]) {
        $changed[] = "nama";
    }

    if ($email !== $user["email"]) {
        $changed[] = "email";
    }

    if ($phone !== ($user["phone"] ?? "")) {
        $changed[] = "nomor HP";
    }


    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    if ($name === "") {

        $error = "Nama wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Email tidak valid.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Cek email
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare(
            "SELECT id
             FROM users
             WHERE email = ?
             AND id != ?
             LIMIT 1"
        );

        $stmt->execute([
            $email,
            $user["id"]
        ]);

        $emailExists = $stmt->fetch();


        if ($emailExists) {

            $error = "Email sudah digunakan oleh akun lain.";

        } else {


            /*
            |--------------------------------------------------------------------------
            | Simpan nomor HP lama
            |--------------------------------------------------------------------------
            */

            $oldPhone = $user["phone"] ?? "";


            /*
            |--------------------------------------------------------------------------
            | Update database
            |--------------------------------------------------------------------------
            */

            $stmt = $conn->prepare(
                "UPDATE users
                 SET name = ?,
                     email = ?,
                     phone = ?
                 WHERE id = ?"
            );

            $stmt->execute([
                $name,
                $email,
                $phone,
                $user["id"]
            ]);


            /*
            |--------------------------------------------------------------------------
            | Update session
            |--------------------------------------------------------------------------
            */

            $_SESSION["user"]["name"] = $name;
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["phone"] = $phone;


            /*
            |--------------------------------------------------------------------------
            | Kirim notifikasi WhatsApp
            |--------------------------------------------------------------------------
            */

            if (count($changed) > 0) {

                $changedText = implode(", ", $changed);


                $_SESSION["edit_success"] =
                    "Data Anda berhasil diubah ($changedText).";


                /*
                | Nomor tujuan:
                | jika nomor baru diisi, gunakan nomor baru.
                | jika kosong, gunakan nomor lama.
                */

                $targetPhone = !empty($phone)
                    ? $phone
                    : $oldPhone;


                if (!empty($targetPhone)) {

                    $message =
                        "Halo {$name},\n\n" .
                        "Data Anda berhasil diubah.\n\n" .
                        "Data yang diubah: {$changedText}.\n\n" .
                        "Terima kasih.";


                    sendWhatsApp(
                        $targetPhone,
                        $message
                    );
                }


            } else {

                $_SESSION["edit_success"] =
                    "Tidak ada data yang diubah.";

            }


            /*
            |--------------------------------------------------------------------------
            | Kembali ke profile
            |--------------------------------------------------------------------------
            */

            header("Location: profile.php");

            exit;
        }
    }
}

?>


<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Profil - Tugas Fonnte</title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        body {

            margin: 0;

            min-height: 100vh;

            background:
                linear-gradient(
                    135deg,
                    #eef4ff,
                    #f8f9ff
                );

            font-family: Arial, sans-serif;

        }


        .navbar {

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #4f46e5
                );

        }


        .page-container {

            max-width: 700px;

            margin: 50px auto;

            padding: 0 15px;

        }


        .edit-card {

            background: white;

            border-radius: 20px;

            overflow: hidden;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.10);

        }


        .edit-header {

            padding: 30px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #4f46e5
                );

        }


        .edit-header h2 {

            margin-bottom: 5px;

        }


        .edit-header p {

            margin: 0;

            opacity: 0.85;

        }


        .edit-body {

            padding: 30px;

        }


        .form-label {

            font-weight: 600;

            color: #374151;

        }


        .form-control {

            padding: 12px 15px;

            border-radius: 10px;

            border: 1px solid #d1d5db;

        }


        .form-control:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.12);

        }


        .btn {

            border-radius: 10px;

            padding: 11px 20px;

        }


        .profile-icon {

            width: 60px;

            height: 60px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(255,255,255,0.20);

            border-radius: 50%;

            font-size: 28px;

            margin-bottom: 15px;

        }


        .info-box {

            background: #f8fafc;

            border-radius: 10px;

            padding: 12px 15px;

            margin-bottom: 20px;

            color: #64748b;

            font-size: 14px;

        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<nav class="navbar navbar-dark">

    <div class="container">

        <a
            href="profile.php"
            class="navbar-brand fw-bold"
        >
            Tugas Fonnte
        </a>


        <a
            href="profile.php"
            class="btn btn-light btn-sm"
        >
            ← Kembali
        </a>

    </div>

</nav>



<!-- CONTENT -->

<div class="page-container">


    <div class="edit-card">


        <!-- HEADER -->

        <div class="edit-header">

            <div class="profile-icon">
                👤
            </div>


            <h2>
                Edit Profil
            </h2>


            <p>
                Ubah informasi akun Anda
            </p>

        </div>



        <!-- BODY -->

        <div class="edit-body">


            <?php if ($error): ?>

                <div class="alert alert-danger">

                    <?= e($error) ?>

                </div>

            <?php endif; ?>



            <div class="info-box">

                💡 Setelah data berhasil diubah,
                Anda akan mendapatkan notifikasi
                melalui WhatsApp.

            </div>



            <form method="POST">


                <!-- NAMA -->

                <div class="mb-4">

                    <label class="form-label">

                        Nama Lengkap

                    </label>


                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?= e($user["name"]) ?>"
                        required
                    >

                </div>



                <!-- EMAIL -->

                <div class="mb-4">

                    <label class="form-label">

                        Email

                    </label>


                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= e($user["email"]) ?>"
                        required
                    >

                </div>



                <!-- NOMOR HP -->

                <div class="mb-4">

                    <label class="form-label">

                        Nomor HP / WhatsApp

                    </label>


                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= e($user["phone"] ?? "") ?>"
                        placeholder="628xxxxxxxxxx"
                    >


                    <small class="text-muted">

                        Contoh:
                        6289524959949

                    </small>

                </div>



                <!-- BUTTON -->

                <div class="d-flex gap-2">


                    <a
                        href="profile.php"
                        class="btn btn-secondary w-50"
                    >

                        Batal

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary w-50"
                    >

                        💾 Simpan Perubahan

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


</body>

</html>