<?php

require_once "config/config.php";
require_once "config/fonnte.php";

$error = "";

$deletedSuccess = "";

if (isset($_GET["deleted"]) && $_GET["deleted"] === "success") {
    $deletedSuccess = "Akun telah dihapus.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT * FROM users
         WHERE email = ?
         LIMIT 1"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if (
        $user &&
        password_verify($password, $user["password"])
    ) {

        session_regenerate_id(true);

        $_SESSION["user"] = $user;

        /*
        |--------------------------------------------------------------------------
        | Kirim notifikasi WhatsApp setelah login
        |--------------------------------------------------------------------------
        */

        if (!empty($user["phone"])) {

            $message =
                "Halo {$user["name"]},\n\n" .
                "Anda berhasil login ke sistem Tugas Fonnte.\n\n" .
                "Selamat datang kembali.";

            sendWhatsApp(
                $user["phone"],
                $message
            );
        }


        /*
        |----------------------------------------------------------
        | Notifikasi berhasil login
        |----------------------------------------------------------
        */

        $_SESSION["login_success"] = "Anda berhasil login.";

        if ($user["role"] === "admin") {

            header("Location: admin/dashboard.php");

        } else {

            header("Location: profile.php");
        }

        exit;

    } else {

        $error = "Email atau password salah.";
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

    <title>Login - Tugas Fonnte</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #4f46e5,
                    #7c3aed
                );

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
        }

        .login-box {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(0,0,0,0.25);
        }

        /* BAGIAN KIRI */

        .login-info {
            min-height: 600px;
            padding: 50px;
            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #4f46e5
                );

            color: white;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-icon {
            width: 75px;
            height: 75px;

            background: rgba(255,255,255,0.15);

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 25px;
        }

        .logo-icon i {
            font-size: 40px;
        }

        .login-info h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .login-info h1 span {
            color: #bfdbfe;
        }

        .login-info p {
            color: #dbeafe;
            font-size: 17px;
            line-height: 1.7;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-top: 25px;
        }

        .info-item i {
            font-size: 24px;
            margin-right: 15px;
        }

        /* BAGIAN KANAN */

        .login-form {
            padding: 55px 45px;
        }

        .login-form h2 {
            font-weight: 800;
            color: #111827;
        }

        .login-form .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .input-group-text {
            background: #f8fafc;
            border-right: none;
        }

        .form-control {
            padding: 13px;
            border-left: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #4f46e5
                );

            color: white;
            font-weight: bold;
            font-size: 16px;

            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(37,99,235,0.3);
        }

        .register-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .home-link {
            display: inline-block;
            margin-top: 20px;
            color: #6b7280;
            text-decoration: none;
        }

        .home-link:hover {
            color: #2563eb;
        }

        @media (max-width: 768px) {

            .login-info {
                min-height: auto;
                padding: 35px;
                text-align: center;
            }

            .logo-icon {
                margin-left: auto;
                margin-right: auto;
            }

            .login-info h1 {
                font-size: 34px;
            }

            .info-item {
                justify-content: center;
            }

            .login-form {
                padding: 40px 25px;
            }
        }

    </style>

</head>


<body>


<div class="login-wrapper">

    <div class="login-box">

        <div class="row g-0">


            <!-- ================================= -->
            <!-- BAGIAN KIRI -->
            <!-- ================================= -->

            <div class="col-lg-6">

                <div class="login-info">

                    <div class="logo-icon">

                        <i class="bi bi-whatsapp"></i>

                    </div>

                    <h1>
                        Tugas
                        <span>Fonnte</span>
                    </h1>

                    <p>
                        Selamat datang kembali.
                        Silakan login untuk mengakses
                        sistem manajemen akun dan
                        WhatsApp Gateway.
                    </p>


                    <div class="info-item">

                        <i class="bi bi-shield-check"></i>

                        <span>
                            Sistem login yang aman
                        </span>

                    </div>


                    <div class="info-item">

                        <i class="bi bi-person-circle"></i>

                        <span>
                            Kelola profil pribadi
                        </span>

                    </div>


                    <div class="info-item">

                        <i class="bi bi-chat-dots"></i>

                        <span>
                            Notifikasi WhatsApp
                        </span>

                    </div>

                </div>

            </div>


            <!-- ================================= -->
            <!-- BAGIAN KANAN -->
            <!-- ================================= -->

            <div class="col-lg-6">

                <div class="login-form">

                    <h2>
                        Selamat Datang 👋
                    </h2>

                    <p class="subtitle">
                        Silakan masuk ke akun Anda
                    </p>


                    <!-- NOTIFIKASI AKUN TERHAPUS -->

                    <?php if ($deletedSuccess): ?>

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle"></i>

                            <?= e($deletedSuccess) ?>

                        </div>

                    <?php endif; ?>


                    <!-- ERROR LOGIN -->

                    <?php if ($error): ?>

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-circle"></i>

                            <?= e($error) ?>

                        </div>

                    <?php endif; ?>


                    <!-- REGISTER BERHASIL -->

                    <?php if (isset($_GET["register"])): ?>

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle"></i>

                            Pendaftaran berhasil.
                            Silakan login.

                        </div>

                    <?php endif; ?>


                    <!-- FORM -->

                    <form method="POST">


                        <!-- EMAIL -->

                        <div class="mb-4">

                            <label class="form-label">
                                Email
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Masukkan email Anda"
                                    required
                                    autocomplete="email"
                                >

                            </div>

                        </div>


                        <!-- PASSWORD -->

                        <div class="mb-4">

                            <label class="form-label">
                                Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-lock"></i>

                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Masukkan password"
                                    required
                                    autocomplete="current-password"
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword()"
                                >

                                    <i
                                        class="bi bi-eye"
                                        id="eyeIcon"
                                    ></i>

                                </button>

                            </div>

                        </div>


                        <!-- LOGIN -->

                        <button
                            type="submit"
                            class="btn-login"
                        >

                            <i class="bi bi-box-arrow-in-right"></i>

                            &nbsp; Login

                        </button>


                    </form>


                    <!-- REGISTER -->

                    <div class="text-center mt-4">

                        Belum mempunyai akun?

                        <a
                            href="register.php"
                            class="register-link"
                        >
                            Daftar sekarang
                        </a>

                    </div>


                    <!-- HOME -->

                    <div class="text-center">

                        <a
                            href="index.php"
                            class="home-link"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Kembali ke halaman utama

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password =
        document.getElementById("password");

    const icon =
        document.getElementById("eyeIcon");

    if (password.type === "password") {

        password.type = "text";

        icon.classList.remove("bi-eye");

        icon.classList.add("bi-eye-slash");

    } else {

        password.type = "password";

        icon.classList.remove("bi-eye-slash");

        icon.classList.add("bi-eye");
    }
}

</script>


</body>

</html>