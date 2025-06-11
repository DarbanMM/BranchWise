<?php
session_start(); // Perubahan: [1] Memulai sesi PHP di awal script. Penting untuk manajemen login.

// Sertakan file koneksi PDO yang baru
include 'db_connect.php'; 

$login_error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $login_error = "Username dan password harus diisi.";
    } else {
        // Gunakan prepared statement dengan PDO
        // Variabel koneksi sekarang adalah $pdo, bukan $conn
        $stmt = $pdo->prepare("SELECT id, full_name, username, password, role, status FROM users WHERE username = ?");
        $stmt->execute([$username]); // Parameter dilewatkan sebagai array ke execute()
        $user = $stmt->fetch(); // fetch() untuk satu baris, fetchAll() untuk banyak baris

        if ($user) { // Jika user ditemukan
            if ($password === $user['password']) {
                if ($user['status'] == 'active') {
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect berdasarkan role
                    if ($user['role'] == 'admin') {
                        header("Location: admin.php");
                        exit();
                    } else if ($user['role'] == 'user') {
                        header("Location: dashboard.php");
                        exit();
                    }
                } else {
                    $login_error = "Akun Anda tidak aktif. Silakan hubungi administrator.";
                }
            } else {
                $login_error = "Username atau password salah.";
            }
        } else {
            $login_error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BranchWise</title>
    <link rel="shortcut icon" href="assets/image/logo.png" type="image/gif">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> 
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        
        .login-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
            margin-top: 64px; /* Perubahan: Tambahkan margin-top setinggi navbar */
        }
        
        .login-card {
            width: 100%;
            max-width: 500px;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .login-header {
            margin-bottom: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .input-field {
            margin-bottom: 20px;
        }
        
        .password-hint {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        
        .login-button {
            background-color: #1565c0 !important;
            width: 100%;
            margin-top: 20px;
            padding: 0 16px;
            height: 36px;
            line-height: 36px;
        }
        
        .divider {
            margin: 30px 0;
            border-top: 1px solid #ddd;
        }
        
        /* Footer Styles */
        .page-footer {
            padding-top: 40px;
            background-color: #0d47a1 !important;
        }

        .footer-content {
            padding-bottom: 30px;
        }

        .footer-section {
            margin-bottom: 20px;
        }

        .footer-section h5 {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
            color: white;
        }

        .footer-section h5:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: #64b5f6;
        }

        .footer-section ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .footer-section li {
            margin-bottom: 10px;
            line-height: 1.6;
            display: flex;
            align-items: center;
        }

        .footer-section li i {
            margin-right: 8px;
            font-size: 16px;
            color: #bbdefb;
        }

        .footer-section a {
            color: #e3f2fd !important;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-section a:hover {
            color: #bbdefb !important;
            transform: translateX(5px);
            text-decoration: none;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-icon {
            position: relative; 
            font-size: 20px;
            color: white;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Perbaikan untuk perataan ikon di tengah lingkaran */
        .social-icon i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); 
            font-size: 20px; 
            color: white; 
        }

        .social-icon:hover {
            color: #bbdefb;
            background: rgba(255,255,255,0.2);
            transform: translateY(-3px);
        }

        .footer-copyright {
            background-color: rgba(0,0,0,0.2) !important;
            padding: 15px 0;
            font-size: 14px;
        }

        .footer-copyright .container {
            display: flex;
            justify-content: center;
        }

        /* Perubahan: Gaya baru untuk navbar full width */
        .navbar-fixed {
            position: fixed;
            z-index: 999; /* Pastikan navbar di atas elemen lain */
            width: 100%;
        }

        nav {
            background-color: white; /* Latar belakang navbar */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Bayangan navbar */
        }

        /* Nav wrapper tanpa kelas container untuk full width, tapi dengan padding manual */
        .nav-wrapper-full-width {
            padding: 0 15px; /* Padding kiri kanan agar konten tidak menempel tepi */
            /* Pastikan brand-logo dan ul.right tetap diatur di dalam Materialize */
        }

        nav .brand-logo {
            font-size: 1.8rem;
            color: #1565c0; /* Warna logo */
            font-weight: bold;
            /* padding-left: 20px; /* Jika ada padding default dari index.php, sesuaikan */ */
        }

        nav ul a {
            color: #444; /* Warna link default */
        }

        /* Gaya tombol login di navbar */
        nav .btn-login {
            border-radius: 50px !important; /* Membuat tombol melengkung */
            height: 36px; /* Tinggi tombol */
            line-height: 36px; /* Perataan vertikal teks */
            padding: 0 20px; /* Padding horizontal */
            background-color: #0d47a1 !important; /* Warna biru tombol */
            color: white !important; /* Warna teks tombol */
        }

        nav .btn-login:hover {
            background-color: #1a56b4 !important; /* Warna hover */
        }

        /* Media queries untuk responsivitas navbar */
        @media only screen and (max-width : 992px) {
            nav .brand-logo {
                padding-left: 10px; /* Padding logo untuk mobile */
            }
            .nav-wrapper-full-width {
                padding: 0 10px; /* Padding untuk mobile */
            }
        }
        /* Akhir Perubahan: Gaya navbar full width */
    </style>
</head>
<body>
    <div class="navbar-fixed">
        <nav class="white" role="navigation">
            <div class="nav-wrapper nav-wrapper-full-width">
                <a id="logo-container" href="index.php" class="brand-logo blue-text text-darken-4"><i class="sets material-icons left">settings</i>BranchWise</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="index.php" class="waves-effect waves-light btn btn-login">Home</a></li>
                </ul>

                <ul id="nav-mobile" class="sidenav">
                    <li><a href="login.php" class="btn btn-login">Login</a></li>
                </ul>
                <a href="#" data-target="nav-mobile" class="sidenav-trigger blue-text text-darken-4"><i class="material-icons">menu</i></a>
            </div>
        </nav>
    </div>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Masuk ke BranchWise</h1>
                <?php if (!empty($login_error)): ?>
                    <p class="red-text text-darken-2" style="font-size: 0.9rem; margin-top: 10px;"><?php echo $login_error; ?></p>
                <?php endif; ?>
            </div>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="input-field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="validate" required>
                </div>
                
                <div class="input-field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="validate" required>
                    <a href="https://wa.me/qr/ZAB5NJRTIWNBE1" style="font-size: 13px; color: #3498db; display: block; margin-top: 5px;">Lupa Password?</a>
                </div>
                
                <div class="password-hint">
                    Gunakan minimum 8 karakter dengan kombinasi huruf dan angka
                </div>
                
                <button class="btn waves-effect waves-light login-button" type="submit">Masuk</button>
            </form>
            
            <div class="divider"></div>
        </div>
    </div>

    <footer class="page-footer">
        <div class="container-full-width-padding"> 
            <div class="row footer-content">
                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">HUBUNGI KAMI</h5>
                        <ul>
                            <li><i class="material-icons tiny">phone</i><a class="grey-text text-lighten-3" >+62-21-888-2424</a></li>
                            <li><i class="material-icons tiny">email</i><a class="grey-text text-lighten-3" >contact@branchwise.com</a></li>
                            <li><i class="material-icons tiny">location_on</i><a class="grey-text text-lighten-3" >Jakarta, Indonesia</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">BRANCHWISE</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="login.php">Dashboard</a></li>
                            <li><a class="grey-text text-lighten-3" href="login.php">Alternatif</a></li>
                            <li><a class="grey-text text-lighten-3" href="login.php">Kriteria</a></li>
                            <li><a class="grey-text text-lighten-3" href="login.php">Bobot</a></li>
                            <li><a class="grey-text text-lighten-3" href="login.php">Matriks</a></li>
                            <li><a class="grey-text text-lighten-3" href="login.php">Hasil</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">SOCIAL MEDIA</h5>
                        <p class="grey-text text-lighten-4">Ikuti kami di media sosial untuk update terbaru</p>
                        <div class="social-icons">
                            <a class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                            <a class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a class="social-icon"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="container-full-width-padding">
                <div class="row">
                    <div class="col s12 center">
                        <span class="white-text">Copyright © 2025 BranchWise. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Inisialisasi Sidenav untuk mobile
            $('.sidenav').sidenav();
            
            // Perubahan: Memastikan Materialize JS diinisialisasi setelah jQuery
            // Ini akan memastikan komponen seperti select atau modal bekerja jika ada di halaman login
            // (Tidak ada modal atau select yang aktif di halaman login ini, tapi ini praktik yang baik)
            // Materialize.updateTextFields(); // Jika perlu untuk label input setelah PHP mengisi form
        });
    </script>
</body>
</html>