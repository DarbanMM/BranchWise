<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan - BranchWise</title>
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1565c0;
            --primary-dark: #0d47a1;
            --secondary-color: #26a69a;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        
        main {
            flex: 1 0 auto;
            margin-left: 250px;
        }
        
        /* Sidebar Styles */
        .sidenav {
            width: 250px;
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            z-index: 999;
        }
        
        .sidenav .user-view {
            padding: 32px 32px 16px;
        }
        
        .sidenav .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .sidenav li>a {
            padding: 0 20px;
            height: 48px;
            line-height: 48px;
            margin: 4px 0;
        }
        
        .sidenav li>a>i {
            margin-right: 12px;
            color: rgba(0,0,0,0.6);
        }
        
        .sidenav li>a.active {
            background-color: rgba(21,101,192,0.1);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .sidenav li>a.active>i {
            color: var(--primary-color);
        }
        
        /* Header Styles */
        .header {
            padding: 0 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: white;
            height: 64px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 998;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
            padding-left: 12px;
        }
        
        /* Content Styles */
        .content-wrapper {
            padding: 24px;
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--dark-gray);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th {
            background-color: #f5f5f5;
            font-weight: 500;
            padding: 12px 15px;
            text-align: left;
        }
        
        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        
        /* Calculation specific styles */
        .criteria-header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
        }
        
        .criteria-subheader {
            background-color: var(--primary-dark);
            color: white;
            text-align: center;
            font-size: 0.85rem;
        }
        
        .alternatif-cell {
            font-weight: 500;
        }
        
        .value-cell {
            text-align: center;
        }
        
        .result-cell {
            font-weight: 600;
            text-align: center;
        }
        
        .ranking-cell {
            font-weight: 700;
            text-align: center;
            color: var(--primary-color);
        }
        
        .highlight-row {
            background-color: rgba(21, 101, 192, 0.05);
        }
        
        /* Responsive Adjustments */
        @media only screen and (max-width: 992px) {
            main {
                margin-left: 0;
            }
            
            .sidenav {
                transform: translateX(-105%);
            }
        }
    </style>
</head>
<body>
    <!-- Side Navigation -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo">
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;">BranchWise</span>
                </div>
            </div>
        </li>
        <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a href="lokasi.php"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
        <li><a href="kriteria.php"><i class="material-icons">assessment</i>Kriteria & Bobot</a></li>
        <li><a href="matriks.php"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a class="active" href="hasil_perhitungan.php"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="index.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <!-- Main Content -->
    <main>
        <!-- Header -->
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Hasil Perhitungan WPM</h1>
                </div>
                <div class="col s6 right-align">
                    <span class="btn-flat dropdown-trigger" data-target="profile-dropdown">
                        <i class="material-icons left">account_circle</i>
                        Username
                    </span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-wrapper">
            <!-- Weighted Product Model Calculation Card -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Perhitungan Weighted Product Model (WPM)</h2>
                    <a href="#!" class="btn waves-effect waves-light blue darken-2">
                        <i class="material-icons left">picture_as_pdf</i>Export PDF
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Alternatif</th>
                                <th colspan="5" class="criteria-header">Kriteria</th>
                                <th rowspan="2" class="criteria-header">S<sub>i</sub></th>
                                <th rowspan="2" class="criteria-header">V<sub>i</sub></th>
                                <th rowspan="2" class="criteria-header">Ranking</th>
                            </tr>
                            <tr>
                                <th class="criteria-subheader">C1<br>Biaya Sewa (0-10)</th>
                                <th class="criteria-subheader">C2<br>Jumlah Penduduk (0-10)</th>
                                <th class="criteria-subheader">C3<br>Aksesibilitas (0-10)</th>
                                <th class="criteria-subheader">C4<br>Daya Beli (0-10)</th>
                                <th class="criteria-subheader">C5<br>Pesaing (0-10)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="alternatif-cell">Senyan Baru</td>
                                <td class="value-cell">0.80</td>
                                <td class="value-cell">0.84</td>
                                <td class="value-cell">0.93</td>
                                <td class="value-cell">0.89</td>
                                <td class="value-cell">0.83</td>
                                <td class="result-cell">0.84</td>
                                <td class="result-cell">0.21</td>
                                <td class="ranking-cell">3</td>
                            </tr>
                            <tr class="highlight-row">
                                <td>2</td>
                                <td class="alternatif-cell">Grand Hotel</td>
                                <td class="value-cell">0.71</td>
                                <td class="value-cell">1.00</td>
                                <td class="value-cell">0.89</td>
                                <td class="value-cell">0.84</td>
                                <td class="value-cell">1.00</td>
                                <td class="result-cell">0.87</td>
                                <td class="result-cell">0.22</td>
                                <td class="ranking-cell">1</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td class="alternatif-cell">Tunjungan Plaza</td>
                                <td class="value-cell">0.89</td>
                                <td class="value-cell">0.93</td>
                                <td class="value-cell">0.78</td>
                                <td class="value-cell">1.00</td>
                                <td class="value-cell">0.90</td>
                                <td class="result-cell">0.89</td>
                                <td class="result-cell">0.22</td>
                                <td class="ranking-cell">2</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td class="alternatif-cell">Bandung Timur</td>
                                <td class="value-cell">0.84</td>
                                <td class="value-cell">0.78</td>
                                <td class="value-cell">1.00</td>
                                <td class="value-cell">0.71</td>
                                <td class="value-cell">0.95</td>
                                <td class="result-cell">0.83</td>
                                <td class="result-cell">0.21</td>
                                <td class="ranking-cell">4</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td class="alternatif-cell">Mall Bali Galeria</td>
                                <td class="value-cell">0.93</td>
                                <td class="value-cell">0.89</td>
                                <td class="value-cell">0.84</td>
                                <td class="value-cell">0.93</td>
                                <td class="value-cell">0.76</td>
                                <td class="result-cell">0.88</td>
                                <td class="result-cell">0.22</td>
                                <td class="ranking-cell">3</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Conclusion Card -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Kesimpulan</h2>
                </div>
                <div class="card-content" style="padding: 20px;">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="card-panel blue lighten-5">
                                <h5 style="margin-top: 0;">Hasil Akhir Ranking:</h5>
                                <ol>
                                    <li><strong>Grand Hotel</strong> (V<sub>i</sub> = 0.22)</li>
                                    <li><strong>Tunjungan Plaza</strong> (V<sub>i</sub> = 0.22)</li>
                                    <li><strong>Senyan Baru</strong> (V<sub>i</sub> = 0.21)</li>
                                    <li><strong>Mall Bali Galeria</strong> (V<sub>i</sub> = 0.22)</li>
                                    <li><strong>Bandung Timur</strong> (V<sub>i</sub> = 0.21)</li>
                                </ol>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="card-panel teal lighten-5">
                                <h5 style="margin-top: 0;">Rekomendasi:</h5>
                                <p>Berdasarkan perhitungan Weighted Product Model (WPM), <strong>Grand Hotel</strong> merupakan pilihan terbaik dengan nilai preferensi tertinggi.</p>
                                <p>Faktor penentu utama adalah performa yang baik pada kriteria <strong>Jumlah Penduduk (C2)</strong> dan <strong>Pesaing (C5)</strong>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Initialize dropdown
            $('.dropdown-trigger').dropdown();
            
            // Initialize modal
            $('.modal').modal();
            
            // Initialize select
            $('select').formSelect();
        });
    </script>
</body>
</html>