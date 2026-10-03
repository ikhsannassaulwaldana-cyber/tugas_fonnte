<?php

require_once "config/config.php";

login_required();

$user = $_SESSION["user"];

/*
|--------------------------------------------------------------------------
| Notifikasi Login
|--------------------------------------------------------------------------
*/

$loginSuccess = $_SESSION["login_success"] ?? "";

unset($_SESSION["login_success"]);


/*
|--------------------------------------------------------------------------
| Notifikasi Edit
|--------------------------------------------------------------------------
*/

$editSuccess = $_SESSION["edit_success"] ?? "";

unset($_SESSION["edit_success"]);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Profil - Tugas Fonnte</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-weight: bold;
            color: #2563eb !important;
            font-size: 23px;
        }

        .profile-container {
            max-width: 900px;
            margin: 60px auto;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .profile-icon {
            width: 100px;
            height: 100px;
            margin: auto;

            background: linear-gradient(
                135deg,
                #2563eb,
                #7c3aed
            );

            color: white;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 50px;
        }

        .profile-header h2 {
            margin-top: 20px;
            font-weight: bold;
        }

        .profile-header p {
            color: #64748b;
        }

        .data-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 15px;
        }

        .data-label {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .data-value {
            font-size: 17px;
            font-weight: bold;
            color: #1e293b;
        }

        .btn-edit {
            background: #2563eb;
            color: white;
        }

        .btn-edit:hover {
            background: #1d4ed8;
            color: white;
        }

    </style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a
            class="navbar-brand"
            href="index.php"
        >

            <i class="bi bi-whatsapp"></i>

            Tugas Fonnte

        </a>

        <div>

            <a
                href="index.php"
                class="btn btn-outline-primary me-2"
            >
                Home
            </a>

            <a
                href="logout.php"
                class="btn btn-outline-danger"
            >
                Logout
            </a>

        </div>

    </div>

</nav>


<!-- CONTENT -->

<div class="container profile-container">

    <!-- NOTIFIKASI LOGIN -->

    <?php if ($loginSuccess): ?>

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-check-circle-fill"></i>

            <strong>
                <?= e($loginSuccess) ?>
            </strong>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- NOTIFIKASI EDIT -->

    <?php if ($editSuccess): ?>

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-check-circle-fill"></i>

            <strong>
                <?= e($editSuccess) ?>
            </strong>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-icon">

                <i class="bi bi-person-fill"></i>

            </div>

            <h2>
                Profil Saya
            </h2>

            <p>
                Informasi akun pribadi Anda
            </p>

        </div>


        <div class="row">


            <!-- NAMA -->

            <div class="col-md-6">

                <div class="data-box">

                    <div class="data-label">
                        <i class="bi bi-person"></i>
                        Nama
                    </div>

                    <div class="data-value">
                        <?= e($user["name"]) ?>
                    </div>

                </div>

            </div>


            <!-- EMAIL -->

            <div class="col-md-6">

                <div class="data-box">

                    <div class="data-label">
                        <i class="bi bi-envelope"></i>
                        Email
                    </div>

                    <div class="data-value">
                        <?= e($user["email"]) ?>
                    </div>

                </div>

            </div>


            <!-- NOMOR HP -->

            <div class="col-md-6">

                <div class="data-box">

                    <div class="data-label">
                        <i class="bi bi-phone"></i>
                        Nomor HP
                    </div>

                    <div class="data-value">

                        <?= !empty($user["phone"])
                            ? e($user["phone"])
                            : "Belum diisi"
                        ?>

                    </div>

                </div>

            </div>


            <!-- ROLE -->

            <div class="col-md-6">

                <div class="data-box">

                    <div class="data-label">
                        <i class="bi bi-shield"></i>
                        Role
                    </div>

                    <div class="data-value">

                        <?php if ($user["role"] === "admin"): ?>

                            <span class="badge bg-danger">
                                Administrator
                            </span>

                        <?php else: ?>

                            <span class="badge bg-primary">
                                User
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>


        <div class="d-flex gap-2 mt-4">

            <a
                href="edit_profile.php"
                class="btn btn-edit"
            >

                <i class="bi bi-pencil-square"></i>

                Edit Profil

            </a>

            <a
                href="delete_account.php"
                class="btn btn-outline-danger"
                onclick="return confirm('Apakah Anda yakin ingin menghapus akun?')"
            >

                <i class="bi bi-trash"></i>

                Hapus Akun

            </a>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>