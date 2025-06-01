<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BranchWise</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Assests CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Font Awesome for social icons -->
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
            position: relative; /* Tambahkan ini */
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
            transform: translate(-50%, -50%); /* Geser ikon kembali setengah dari lebar/tingginya */
            font-size: 20px; /* Pertahankan ukuran font */
            color: white; /* Pertahankan warna */
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
    </style>
</head>
<body>
    <!-- Login Form -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Masuk ke BranchWise</h1>
            </div>
            
            <div class="input-field">
                <label for="username">Username</label>
                <input type="text" id="username" class="validate">
            </div>
            
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" id="password" class="validate">
                <a href="#" style="font-size: 13px; color: #3498db; display: block; margin-top: 5px;">Lupa Password?</a>
            </div>
            
            <div class="password-hint">
                Gunakan minimum 8 karakter dengan kombinasi huruf dan angka
            </div>
            
            <button class="btn waves-effect waves-light login-button">Masuk</button>
            
            <div class="divider"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer">
        <div class="container">
            <div class="row footer-content">
                <!-- Hubungi Kami -->
                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">HUBUNGI KAMI</h5>
                        <ul>
                            <li><i class="material-icons tiny">phone</i><a class="grey-text text-lighten-3" href="#!">+62-21-888-2424</a></li>
                            <li><i class="material-icons tiny">email</i><a class="grey-text text-lighten-3" href="#!">contact@branchwise.com</a></li>
                            <li><i class="material-icons tiny">location_on</i><a class="grey-text text-lighten-3" href="#!">Jakarta, Indonesia</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Menu BranchWise -->
                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">BRANCHWISE</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#!">Dashboard</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Alternatif</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Kriteria</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Bobot</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Matriks</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Hasil</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col s12 m4">
                    <div class="footer-section">
                        <h5 class="white-text">SOCIAL MEDIA</h5>
                        <p class="grey-text text-lighten-4">Ikuti kami di media sosial untuk update terbaru</p>
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Copyright -->
        <div class="footer-copyright">
            <div class="container">
                <div class="row">
                    <div class="col s12 center">
                        <span class="white-text">Copyright © 2025 BranchWise. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>