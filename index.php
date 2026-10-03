<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tugas Fonnte</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .navbar {
            background: #2563eb;
            color: white;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
        }

        .menu a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-weight: bold;
        }

        .hero {
            min-height: 500px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 55px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #bfdbfe;
        }

        .hero p {
            font-size: 19px;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 5px;
        }

        .btn-primary {
            background: white;
            color: #2563eb;
        }

        .btn-secondary {
            border: 2px solid white;
            color: white;
        }

        .features {
            padding: 60px 40px;
            text-align: center;
        }

        .features h2 {
            font-size: 32px;
            margin-bottom: 40px;
        }

        .cards {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            width: 280px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .card .icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .card h3 {
            margin-bottom: 12px;
        }

        .card p {
            color: #64748b;
            line-height: 1.6;
        }

        footer {
            background: #0f172a;
            color: white;
            text-align: center;
            padding: 25px;
        }

        @media (max-width: 700px) {
            .navbar {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
            }

            .hero h1 {
                font-size: 38px;
            }
        }
    </style>
</head>

<body>

    <div class="navbar">

        <div class="logo">
            📱 Tugas Fonnte
        </div>

        <div class="menu">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>

    </div>


    <section class="hero">

        <div class="hero-content">

            <h1>
                Sistem User <span>Fonnte</span>
            </h1>

            <p>
                Selamat datang di sistem manajemen akun
                dengan integrasi WhatsApp Gateway Fonnte.
                Kelola akun, profil, dan notifikasi WhatsApp
                dengan mudah.
            </p>

            <a href="register.php" class="btn btn-primary">
                Daftar Sekarang
            </a>

            <a href="login.php" class="btn btn-secondary">
                Login
            </a>

        </div>

    </section>


    <section class="features">

        <h2>Fitur Sistem</h2>

        <div class="cards">

            <div class="card">

                <div class="icon">
                    👤
                </div>

                <h3>
                    Login & Profil
                </h3>

                <p>
                    Pengguna dapat login dan
                    melihat profil pribadi.
                </p>

            </div>


            <div class="card">

                <div class="icon">
                    ✏️
                </div>

                <h3>
                    Edit Data
                </h3>

                <p>
                    Pengguna dapat mengubah
                    data profil seperti nama,
                    email dan nomor HP.
                </p>

            </div>


            <div class="card">

                <div class="icon">
                    📱
                </div>

                <h3>
                    WhatsApp Gateway
                </h3>

                <p>
                    Sistem dapat mengirim
                    notifikasi melalui WhatsApp
                    menggunakan Fonnte.
                </p>

            </div>

        </div>

    </section>


    <footer>

        Tugas Fonnte © 2026

    </footer>

</body>
</html>