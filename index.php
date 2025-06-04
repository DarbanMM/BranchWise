<!DOCTYPE html>
<html>
<head>
    <title>BranchWise - A smart decision support system</title>
    <meta property="og:image" content="assets/image/laptop.jpg" />
    <meta name="description" content="BranchWise adalah sistem pendukung keputusan untuk mengoptimalkan operasional retail." />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="assets/css/materialize.css" media="screen,projection" />
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="apple-touch-icon" sizes="76x76" href="assets/image/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/image/favicon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/materialize.js"></script>

    <style>
        /* Custom Animation Styles */
        .pulse-slow {
            animation: pulse 3s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .float-slow {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .branch-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .branch-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        }
        
        .btn-hover-grow {
            transition: all 0.3s ease;
        }
        
        .btn-hover-grow:hover {
            transform: scale(1.05);
        }
        
        .fade-in-section {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .hero-title {
            animation: fadeInDown 1s both;
        }
        
        .hero-subtitle {
            animation: fadeInDown 1s both 0.3s;
        }
        
        .hero-button {
            animation: fadeInUp 1s both 0.6s;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #1565C0;
            animation: expandLine 1s both 0.5s;
        }
        
        @keyframes expandLine {
            from { width: 0; }
            to { width: 80px; }
        }

        /* Navbar animation */
        nav {
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        
        nav.scrolled {
            background-color: rgba(21, 101, 192, 0.9) !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        nav.scrolled .brand-logo, 
        nav.scrolled .button-collapse,
        nav.scrolled .button-collapse i {
            color: white !important;
        }

        /* Layout Structure */
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
        }

        main {
            flex: 1 0 auto;
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

        /* Responsive Adjustments */
        @media (max-width: 600px) {
            .footer-section {
                text-align: center;
            }
            
            .footer-section h5:after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .social-icons {
                justify-content: center;
            }
            
            .footer-section li {
                justify-content: center;
            }
        }

        /* Additional Improvements */
        .section-gap {
            padding: 80px 0;
        }

        .hero-content-center {
            padding-top: 100px;
        }

        /* Perubahan: Gaya tombol melengkung seperti kapsul */
        .btn-rounded {
            border-radius: 50px !important; /* Membuat sudut tombol sangat melengkung */
            padding-left: 25px; /* Menyesuaikan padding agar tombol lebih proporsional */
            padding-right: 25px; /* Menyesuaikan padding agar tombol lebih proporsional */
        }
        /* Akhir Perubahan: Gaya tombol melengkung */

        /* Perubahan: Gaya Tombol Hero Section "Mulai" dengan panah */
        .hero-button {
            font-size: 1.1rem; /* Ukuran font tombol hero */
            height: 50px; /* Tinggi tombol */
            line-height: 50px; /* Perataan vertikal teks */
            display: inline-flex; /* Menggunakan flexbox untuk perataan ikon */
            align-items: center; /* Perataan vertikal item dalam flexbox */
            justify-content: center; /* Perataan horizontal item dalam flexbox */
            background-color: #0d47a1 !important; /* Warna biru tombol mulai */
            color: white !important; /* Warna teks tombol mulai */
        }

        .hero-button i.material-icons {
            margin-left: 8px; /* Jarak antara teks dan ikon panah */
            font-size: 20px; /* Ukuran ikon panah */
            line-height: inherit; /* Inherit line-height dari parent */
        }

        .hero-button:hover {
            background-color: #1a56b4 !important; /* Warna hover yang sedikit lebih terang */
        }
        /* Akhir Perubahan: Gaya Tombol Hero Section "Mulai" */

        /* Perubahan: Gaya Tombol Login di Navbar (melengkung) */
        nav .login-btn {
            border-radius: 50px !important; /* Membuat sudut tombol sangat melengkung */
            padding: 0 20px; /* Menyesuaikan padding */
            height: 36px; /* Menyesuaikan tinggi */
            line-height: 36px; /* Menyesuaikan line-height */
            background-color: #0d47a1 !important; /* Warna biru tombol login */
            color: white !important; /* Warna teks tombol login */
        }

        nav .login-btn:hover {
            background-color: #1a56b4 !important; /* Warna hover yang sedikit lebih terang */
        }
        /* Akhir Perubahan: Gaya Tombol Login di Navbar */

        /* Perubahan: Gaya Tombol "Mulai Analisis Sekarang" di Ayo Pilih Lokasi Section */
        .btn-white-blue-text {
            background-color: white !important; 
            color: #1565C0 !important; 
            border-radius: 50px !important; /* Membuat sudut tombol sangat melengkung */
            height: 50px; /* Tinggi tombol */
            line-height: 50px; /* Perataan vertikal teks */
            padding: 0 30px; /* Menyesuaikan padding */
        }

        .btn-white-blue-text:hover {
            background-color: #e0e0e0 !important; 
            color: #0d47a1 !important; 
        }

        #hero-background-img {
            width: 70%; /* atau persentase lain */
            height: auto; /* menjaga rasio aspek */
            /* Hapus transform: rotate() jika hanya ingin mengecilkan dan menengahkan */
        }

        /* Jika Anda ingin gambar tetap di tengah setelah dikecilkan dalam container parallax */
        .parallax {
            display: flex;
            justify-content: center; /* Tengah secara horizontal */
            align-items: center;   /* Tengah secara vertikal */
            overflow: hidden;      /* Sembunyikan bagian gambar yang keluar dari container jika gambar lebih besar */
        }

    </style>
    <script>
        $(document).ready(function() {
            // Inisialisasi komponen
            $('.button-collapse').sideNav({
                menuWidth: 300, 
                edge: 'left', 
                closeOnClick: true, 
                draggable: true 
            });
            $('.parallax').parallax(); 
            $('.modal').modal(); 
            $('.collapsible').collapsible();
            
            // Animate elements when they come into view
            const fadeInSections = document.querySelectorAll('.fade-in-section');
            
            const fadeInObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.1 });
            
            fadeInSections.forEach(section => {
                fadeInObserver.observe(section);
            });
            
            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('nav').addClass('scrolled');
                } else {
                    $('nav').removeClass('scrolled');
                }
            });
            
            // Add hover effect to cards
            $('.branch-card').hover(
                function() {
                    $(this).find('.card-image img').addClass('pulse-slow');
                },
                function() {
                    $(this).find('.card-image img').removeClass('pulse-slow');
                }
            );
            
            // Add animation to CTA button
            setInterval(function() {
                // Perubahan: Mengubah selector dari '.btn-large.blue' menjadi '.hero-button'
                // karena tombol "Mulai" sekarang memiliki kelas khusus
                $('.hero-button').toggleClass('pulse'); 
            }, 4000);
            
            // Handle login button click
            $('.login-btn').on('click', function(e) {
                e.preventDefault();
                window.location.href = 'login.php';
            });
        });
    </script>
</head>
<body>
    <div class="navbar-fixed">
        <nav class="white" role="navigation">
            <div class="nav-wrapper container">
                <a id="logo-container" href="index.php" class="brand-logo blue-text text-darken-4" style="font-weight: bold;"><i class="sets material-icons left">settings</i>BranchWise</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="login.php" class="waves-effect waves-light btn blue darken-3 btn-hover-grow login-btn btn-rounded">Login</a></li>
                </ul>

                <ul id="nav-mobile" class="side-nav">
                    <li><a href="login.php" class="login-btn btn-rounded">Login</a></li>
                </ul>
                <a href="#" data-activates="nav-mobile" class="button-collapse blue-text text-darken-4"><i class="material-icons">menu</i></a>
            </div>
        </nav>
    </div>

    <main>
        <div id="index-banner" class="parallax-container" style="min-height: 550px;">
            <div class="section no-pad-bot">
                <div class="container hero-content-center">
                    <div class="row">
                        <div class="col s12 m8 l7"> 
                            <h1 class="header white-text text-lighten-2 hero-title">A smart decision support system to grow your retail business</h1>
                            <p class="flow-text white-text text-lighten-3 hero-subtitle">Temukan lokasi strategis untuk cabang toko Anda berikutnya dengan lebih cepat dan tepat. Sistem kami membantu menganalisis berbagai faktor penting, seperti kepadatan penduduk, kompetitor, aksesibilitas hingga potensi pasar lokal</p>
                            <div class="row">
                                <a href="login.php" id="download-button" class="btn-large waves-effect waves-light blue darken-3 hero-button btn-hover-grow pulse login-btn btn-rounded">Mulai <i class="material-icons right">arrow_forward</i></a>
                            </div>
                        </div>
                        <div class="col s12 m4 l5">
                        </div>
                    </div>
                </div>
            </div>
            <div class="parallax"><img id="hero-background-img" src="assets/image/building_hero.jpg" alt="Building background"></div>
            </div>

        <div id="locations" class="container section-gap">
            <div class="fade-in-section">
                <h3 class="center blue-text text-darken-4 section-title">Cabang dengan potensi terbaik dari semua pilihan</h3>
                <p class="center grey-text text-darken-2">Ekspansi bisnis yang cerdas dimulai dari keputusan yang tepat</p>
            </div>
            <br>
            <div class="row">
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/semarang.jpg" alt="Semarang" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Semarang<i class="material-icons right">more_vert</i></span>
                            <p>Kawasan industri dan pelabuhan aktif</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Semarang<i class="material-icons right">close</i></span>
                            <p>Lokasi strategis dengan akses ke pelabuhan dan kawasan industri yang berkembang pesat.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/surabaya.jpg" alt="Surabaya" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Surabaya<i class="material-icons right">more_vert</i></span>
                            <p>Pusat Ekonomi Timur</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Surabaya<i class="material-icons right">close</i></span>
                            <p>Kota metropolitan dengan pertumbuhan ekonomi tercepat di Indonesia Timur.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/malang.jpg" alt="Malang" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Malang<i class="material-icons right">more_vert</i></span>
                            <p>Kota Pendidikan</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Malang<i class="material-icons right">close</i></span>
                            <p>Kota dengan populasi muda yang tinggi dan daya beli yang kuat dari kalangan akademisi.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/bandung.jpg" alt="Bandung" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Bandung<i class="material-icons right">more_vert</i></span>
                            <p>Pusat Kreatif & Wisata</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Bandung<i class="material-icons right">close</i></span>
                            <p>Destinasi wisata dan pusat kreativitas dengan konsumen yang memiliki daya beli tinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/jakarta.png" alt="Jakarta" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Jakarta<i class="material-icons right">more_vert</i></span>
                            <p>Metropolitan Utama</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Jakarta<i class="material-icons right">close</i></span>
                            <p>Pusat ekonomi Indonesia dengan jumlah penduduk dan daya beli tertinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 fade-in-section">
                    <div class="card medium branch-card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img src="assets/image/batam.jpg" alt="Batam" class="activator">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator blue-text text-darken-4">Batam<i class="material-icons right">more_vert</i></span>
                            <p>Gerbang Industri</p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title blue-text text-darken-4">Batam<i class="material-icons right">close</i></span>
                            <p>Kawasan ekonomi khusus dengan akses internasional dan pertumbuhan industri yang pesat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="blue darken-4 section-gap" style="padding: 80px 0;">
            <div class="container">
                <div class="row">
                    <div class="col s12 m8 offset-m2 center">
                        <h2 class="white-text animate__animated animate__fadeIn">Ayo Pilih Lokasi</h2>
                        <h2 class="white-text animate__animated animate__fadeIn animate__delay-1s">Cabang Terbaikmu!</h2>
                        <p class="flow-text white-text animate__animated animate__fadeIn animate__delay-2s" style="max-width: 800px; margin: 20px auto;">
                            Ekspansi bisnis yang cerdas dimulai dari keputusan yang tepat. Temukan lokasi terbaik untuk cabang baru Anda bersama BranchWise, berdasarkan data dan analisis yang objektif.
                        </p>
                        <div class="section" style="padding-top: 20px;">
                            <a href="login.php" class="waves-effect waves-light btn-large btn-white-blue-text btn-hover-grow animate__animated animate__pulse animate__infinite animate__slower login-btn btn-rounded">Mulai Analisis Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="faq" class="container section-gap">
            <div class="fade-in-section">
                <h3 class="center blue-text text-darken-4 section-title">Frequently Asked Questions</h3>
                <p class="center grey-text text-darken-2">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            <br>
            <ul class="collapsible popout" data-collapsible="accordion">
                <li class="fade-in-section">
                    <div class="collapsible-header active"><i class="material-icons blue-text">help_outline</i>Bagaimana metode pengambilan laporan hasil perhitungan WPM?</div>
                    <div class="collapsible-body"><p>Untuk mengambil laporan hasil perhitungan WPM, Anda dapat menavigasi ke halaman Rekomendasi, lalu klik tombol 'Lihat Laporan' yang tersedia setelah perhitungan selesai. Laporan akan tersedia dalam format PDF yang bisa diunduh atau dicetak.</p></div>
                </li>
                <li class="fade-in-section">
                    <div class="collapsible-header"><i class="material-icons blue-text">help_outline</i>Siapa saja yang bisa mengunduh laporan hasil perhitungan WPM?</div>
                    <div class="collapsible-body"><p>Laporan hasil perhitungan WPM hanya dapat diunduh oleh pengguna yang sudah memiliki akun</p></div>
                </li>
                <li class="fade-in-section">
                    <div class="collapsible-header"><i class="material-icons blue-text">help_outline</i>Apakah saya bisa menambahkan atau mengatur bobot kriteria?</div>
                    <div class="collapsible-body"><p>Ya, Anda dapat menambahkan kriteria baru serta mengatur bobot untuk setiap kriteria melalui halaman kriteria. Sistem kami fleksibel dan dapat disesuaikan dengan kebutuhan bisnis spesifik Anda.</p></div>
                </li>
            </ul>
        </div>
    </main>

    <footer class="page-footer">
        <div class="container">
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
            <div class="container">
                <div class="row">
                    <div class="col s12 center">
                        <span class="white-text">Copyright © 2025 BranchWise. All rights reserved.</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>