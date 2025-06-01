<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BranchWise</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
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
            padding-top: 20px;
            background-color: #0d47a1;
        }
        
        .footer-content {
            padding-bottom: 20px;
        }
        
        .footer-section h5 {
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .footer-section ul {
            margin: 0;
            padding: 0;
        }
        
        .footer-section li {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .social-icon {
            font-size: 20px;
            color: white;
            transition: color 0.3s ease;
        }
        
        .social-icon:hover {
            color: #b3e5fc;
        }
        
        .footer-copyright {
            background-color: #0a3a8a !important;
            padding: 10px 0;
        }
        
        .copyright-content {
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
                            <li class="grey-text text-lighten-4">+62-21-888-2424</li>
                            <li class="grey-text text-lighten-4">contact@branchwise.com</li>
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
                        <div class="social-icons">
                            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Footer Copyright -->
        <div class="footer-copyright">
            <div class="container copyright-content">
                <span class="white-text">Copyright © 2025 BranchWise. All rights reserved.</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>