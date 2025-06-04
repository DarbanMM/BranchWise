<?php
    session_start(); // Mulai session untuk mengambil data user
    require_once 'db_connect.php'; // Sertakan file koneksi database (menyediakan $conn)

    // Cek apakah user sudah login, jika tidak, redirect ke halaman login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Ambil data user dari session
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];

    // Jika role bukan 'user', redirect ke halaman yang sesuai (misal: admin.php)
    if ($role !== 'user') {
        header("Location: admin.php"); // Atau halaman lain yang sesuai untuk admin
        exit();
    }

    // Ambil project_id dari session, jika tidak ada, redirect ke dashboard
    if (!isset($_SESSION['current_project_id'])) {
        header("Location: dashboard.php");
        exit();
    }
    $project_id = $_SESSION['current_project_id'];

    // Inisialisasi pesan
    $message = '';
    $error = '';
    $locations = [];
    $criteria_list = [];
    $matrix_decision = [];

    // Fetch locations for the current project menggunakan MySQLi
    $stmt_locations = $conn->prepare("SELECT id, branch_name FROM locations WHERE project_id = ? ORDER BY branch_name ASC");
    if ($stmt_locations) {
        $stmt_locations->bind_param("i", $project_id);
        $stmt_locations->execute();
        $result_locations = $stmt_locations->get_result();
        while ($row = $result_locations->fetch_assoc()) {
            $locations[] = $row;
        }
        $stmt_locations->close();
    } else {
        $error = "Gagal menyiapkan statement untuk mengambil lokasi: " . $conn->error;
    }


    // Fetch criteria for the current project menggunakan MySQLi
    if (empty($error)) {
        $stmt_criteria = $conn->prepare("SELECT id, criteria_code, criteria_name, weight_percentage, type FROM criteria WHERE project_id = ? ORDER BY criteria_code ASC");
        if ($stmt_criteria) {
            $stmt_criteria->bind_param("i", $project_id);
            $stmt_criteria->execute();
            $result_criteria = $stmt_criteria->get_result();
            while ($row = $result_criteria->fetch_assoc()) {
                $criteria_list[] = $row;
            }
            $stmt_criteria->close();
        } else {
            $error = "Gagal menyiapkan statement untuk mengambil kriteria: " . $conn->error;
        }
    }

    // Fetch existing matrix data menggunakan MySQLi
    if (empty($error)) {
        $stmt_matrix_data = $conn->prepare("SELECT location_id, criteria_id, value FROM matrix_data WHERE project_id = ?");
        if ($stmt_matrix_data) {
            $stmt_matrix_data->bind_param("i", $project_id);
            $stmt_matrix_data->execute();
            $result_matrix_data = $stmt_matrix_data->get_result();
            while ($row = $result_matrix_data->fetch_assoc()) {
                if (!isset($matrix_decision[$row['location_id']])) {
                    $matrix_decision[$row['location_id']] = [];
                }
                $matrix_decision[$row['location_id']][$row['criteria_id']] = (float)$row['value'];
            }
            $stmt_matrix_data->close();
        } else {
            $error = "Gagal menyiapkan statement untuk mengambil data matriks: " . $conn->error;
        }
    }
    
    // --- Validasi Data Minimum ---
    if (empty($error)) {
        if (count($criteria_list) < 3) {
            $error = "Untuk melakukan perhitungan, Anda perlu minimal 3 kriteria. Saat ini ada " . count($criteria_list) . " kriteria.";
        } elseif (count($locations) === 0) {
            $error = "Tidak ada lokasi cabang yang terdaftar untuk proyek ini.";
        } elseif (empty($matrix_decision) && count($locations) > 0 && count($criteria_list) > 0) {
             $error = "Data matriks keputusan kosong atau belum diisi. Harap lengkapi di halaman Matriks.";
        } else {
            if (count($locations) > 0 && count($criteria_list) > 0) {
                $all_matrix_filled = true;
                foreach ($locations as $location) {
                    foreach ($criteria_list as $criteria) {
                        if (!isset($matrix_decision[$location['id']][$criteria['id']])) {
                            $all_matrix_filled = false;
                            break 2; 
                        }
                    }
                }
                if (!$all_matrix_filled) {
                    $error = "Data matriks keputusan belum lengkap untuk semua alternatif dan kriteria. Harap lengkapi semua nilai di halaman Matriks.";
                }
            }
        }
    }

    // --- Perhitungan Weighted Product Model (WPM) ---
    $calculation_results = [];
    $sum_si = 0;
    $ranking_data = [];

    if (empty($error) && !empty($locations) && !empty($criteria_list)) {
        $total_weight_sum = 0;
        foreach ($criteria_list as $criteria) {
            $total_weight_sum += (float)$criteria['weight_percentage'];
        }

        if ($total_weight_sum !== 100.0 && count($criteria_list) >= 3) {
             $error = "Total bobot kriteria harus 100%. Saat ini total bobot adalah " . $total_weight_sum . "%. Harap perbarui di halaman Kriteria & Bobot.";
        } else {
            $normalized_weights = [];
            if ($total_weight_sum > 0) {
                foreach ($criteria_list as $criteria) {
                    $normalized_weights[$criteria['id']] = (float)$criteria['weight_percentage'] / $total_weight_sum;
                }
            } elseif (count($criteria_list) > 0) {
                 $error = "Total bobot kriteria adalah 0. Tidak dapat melakukan normalisasi bobot.";
            }

            if (empty($error)) {
                foreach ($locations as $location) {
                    $location_id = $location['id'];
                    $si_value = 1.0; 

                    foreach ($criteria_list as $criteria) {
                        $criteria_id = $criteria['id'];
                        $value = isset($matrix_decision[$location_id][$criteria_id]) ? (float)$matrix_decision[$location_id][$criteria_id] : 0;
                        $weight = isset($normalized_weights[$criteria_id]) ? (float)$normalized_weights[$criteria_id] : 0;

                        if ($value <= 0 && $criteria['type'] === 'cost' && $weight > 0) {
                             $error = "Nilai kriteria '" . htmlspecialchars($criteria['criteria_name']) . "' untuk lokasi '" . htmlspecialchars($location['branch_name']) . "' adalah " . $value . ", yang tidak valid untuk perhitungan kriteria Cost. Harap perbaiki di halaman Matriks.";
                            break 2; 
                        }
                         if ($value < 0 && $weight != 0) { 
                            $error = "Nilai kriteria '".htmlspecialchars($criteria['criteria_name'])."' untuk lokasi '".htmlspecialchars($location['branch_name'])."' adalah negatif (".$value."), yang dapat menyebabkan masalah perhitungan. Harap perbaiki di halaman Matriks.";
                            break 2;
                        }
                         if ($value == 0 && $weight < 0) { 
                            $error = "Nilai kriteria '".htmlspecialchars($criteria['criteria_name'])."' untuk lokasi '".htmlspecialchars($location['branch_name'])."' adalah 0 dan bobotnya negatif, menyebabkan nilai tak hingga. Harap perbaiki di halaman Matriks.";
                            break 2;
                        }

                        if ($value > 0) { 
                            if ($criteria['type'] === 'cost') {
                                $si_value *= pow($value, -$weight);
                            } else {
                                $si_value *= pow($value, $weight);
                            }
                        } elseif ($value == 0 && $weight == 0) { 
                             $si_value *= 1;
                        } elseif ($value == 0 && $weight > 0) { 
                             $si_value *= 0;
                        }
                    }
                    if (!empty($error)) break; 

                    $calculation_results[$location_id]['Si'] = $si_value;
                    $sum_si += $si_value; 
                }

                if (empty($error)) {
                    if ($sum_si > 0) {
                        foreach ($locations as $location) {
                            $location_id = $location['id'];
                            $si_for_location = isset($calculation_results[$location_id]['Si']) ? (float)$calculation_results[$location_id]['Si'] : 0;
                            $vi_value = $si_for_location / $sum_si;
                            $calculation_results[$location_id]['Vi'] = $vi_value;
                            $ranking_data[] = [
                                'location_id' => $location_id,
                                'branch_name' => $location['branch_name'],
                                'Vi' => $vi_value,
                                'Si' => $si_for_location
                            ];
                        }
                    } else if (count($locations) > 0) { 
                        foreach ($locations as $location) {
                            $location_id = $location['id'];
                            $calculation_results[$location_id]['Vi'] = 0;
                            $ranking_data[] = [
                                'location_id' => $location_id,
                                'branch_name' => $location['branch_name'],
                                'Vi' => 0,
                                'Si' => isset($calculation_results[$location_id]['Si']) ? (float)$calculation_results[$location_id]['Si'] : 0
                            ];
                        }
                         $message = "Total Vektor S (ΣSi) adalah nol. Semua alternatif memiliki nilai preferensi (Vi) nol.";
                    }

                    usort($ranking_data, function($a, $b) {
                        return $b['Vi'] <=> $a['Vi']; 
                    });

                    foreach ($ranking_data as $rank => $item) {
                        if (isset($calculation_results[$item['location_id']])) {
                             $calculation_results[$item['location_id']]['ranking'] = $rank + 1;
                        }
                        $ranking_data[$rank]['ranking'] = $rank + 1;
                    }
                }
            }
        }
    }

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message']; 
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error']; 
        unset($_SESSION['error']);
    }

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan - BranchWise</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            margin-right: 20px; 
            color: rgba(0,0,0,0.6);
        }
        .sidenav li>a.active {
            background-color: rgba(21,101,192,0.1);
            color: var(--primary-color);
            font-weight: 500;
        }
        .sidenav li>a.active>i.material-icons { color: var(--primary-color); }
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
        .btn, .btn-large, .btn-small { 
            border-radius: 7px !important; 
            padding-left: 25px; 
            padding-right: 25px; 
        }
        .btn .material-icons.left { margin-right: 8px; }
        .page-title {
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
            padding-left: 12px; 
        }
        .header .sidenav-trigger i { color: var(--dark-gray); }
        .content-wrapper { padding: 24px; }
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
        .table-responsive { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: 500;
            padding: 12px 15px;
            text-align: center; 
        }
        table th[rowspan="2"] { text-align: left; }
        table td {
            padding: 10px 15px; 
            border-bottom: 1px solid #eee;
            text-align: center; 
        }
        table td.alternatif-cell { text-align: left; }
        .criteria-header {
            background-color: var(--primary-color) !important; 
            color: white !important; 
            text-align: center;
        }
        .criteria-subheader {
            background-color: var(--primary-dark) !important; 
            color: white !important; 
            text-align: center;
            font-size: 0.85rem;
            font-weight: 400; 
        }
        .alternatif-cell { font-weight: 500; }
        .value-cell { text-align: center; }
        .result-cell {
            font-weight: 600;
            text-align: center;
        }
        .ranking-cell {
            font-weight: 700;
            text-align: center;
            color: var(--primary-color);
            font-size: 1.1em; 
        }
        .highlight-row { 
            background-color: #e3f2fd; 
            font-weight: 500;
        }
        .highlight-row .ranking-cell { color: #0d47a1; }
        .error-message-card {
            padding: 20px;
            text-align: center;
            background-color: #ffebee; 
            color: #c62828; 
            border: 1px solid #ef9a9a;
            border-radius: 8px;
        }
        @media only screen and (max-width: 992px) {
            main { margin-left: 0; }
            .sidenav { transform: translateX(-105%); }
            .page-title { padding-left: 0; }
        }

        /* === PRINT STYLES START === */
        @media print {
            body * {
                visibility: hidden; /* Sembunyikan semua elemen secara default saat mencetak */
            }
            /* Tampilkan hanya bagian yang ingin dicetak */
            .printable-area, .printable-area * { /* Kontainer utama yang ingin dicetak dan semua isinya */
                visibility: visible;
            }
            .printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 15px; /* Padding untuk konten yang dicetak */
                box-shadow: none !important;
                border: none !important;
            }
            main {
                margin-left: 0 !important; /* Hapus margin dari sidebar */
                padding:0 !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                margin-bottom: 15px !important;
                border-radius: 0 !important;
            }
            .card-header {
                border-bottom: 1px solid #ccc !important;
                padding: 10px 15px !important;
            }
            .card-header .btn, .card-header a.btn { /* Sembunyikan tombol di header kartu saat mencetak */
                display: none !important;
            }
            .card-title {
                font-size: 1.1rem !important;
            }
            .card-panel {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                background-color: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                padding: 15px !important;
                border-radius: 0 !important;
            }
            .card-panel h5 {
                font-size: 1rem !important;
                margin-bottom: 10px !important;
            }
            /* Sembunyikan elemen UI yang tidak perlu dicetak */
            .sidenav, .header, #export-pdf-btn-js, .toast, .content-wrapper > .card-panel.green {
                display: none !important;
            }
            table {
                width: 100% !important;
                font-size: 9pt !important; /* Ukuran font lebih kecil untuk cetak */
                border: 1px solid #ccc !important;
            }
            table th, table td {
                padding: 4px 6px !important; /* Padding lebih kecil untuk sel tabel */
                border: 1px solid #ddd !important; /* Border untuk setiap sel */
            }
            .criteria-header, .criteria-subheader {
                background-color: #e0e0e0 !important; /* Warna abu-abu untuk header cetak */
                color: #000 !important; /* Teks hitam */
                -webkit-print-color-adjust: exact; /* Memaksa browser mencetak warna latar */
                color-adjust: exact;
                font-weight: bold !important;
            }
            .highlight-row {
                background-color: #f0f0f0 !important; /* Warna highlight yang lebih ramah printer */
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            /* Kontrol page break */
            table, .card, .card-panel {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group; /* Ulangi header tabel di setiap halaman cetak */
            }
            /* Sembunyikan URL dari link saat cetak */
            a[href]:after {
                content: none !important;
            }
            /* Atur agar konten mulai dari atas halaman */
            html, body {
                margin: 0;
                padding: 0;
                background-color: white !important; /* Latar belakang putih untuk cetak */
            }
        }
        /* === PRINT STYLES END === */

        .sets{
           margin: 13px 10px 0px 0px; 
        }
    </style>
</head>
<body>
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo">
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;"><i class="sets material-icons left">settings</i>BranchWise</span>
                </div>
            </div>
        </li>
        <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a href="lokasi.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
        <li><a href="kriteria.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">assessment</i>Kriteria & Bobot</a></li>
        <li><a href="matriks.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a class="active" href="hasil_perhitungan.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <main>
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s8 m9 l10"> 
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Hasil Perhitungan WPM</h1>
                </div>
                <div class="col s4 m3 l2 right-align"> 
                    <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
                        <i class="material-icons left" style="margin-right:4px;">account_circle</i>
                        <?php echo htmlspecialchars($username); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
             <?php if (!empty($message) && empty($error)): ?> 
                <div class="card-panel green lighten-4 green-text text-darken-3" style="padding: 15px; margin-bottom:20px; border-radius: 8px;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="printable-area">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Perhitungan Weighted Product Model (WPM)</h2>
                        <a href="#!" id="export-pdf-btn-js" class="btn waves-effect waves-light blue darken-2">
                            <i class="material-icons left">print</i>Cetak Laporan
                        </a>
                    </div>
                    
                    <?php if (!empty($error)): ?>
                        <div class="error-message-card">
                            <i class="material-icons" style="font-size: 3rem; margin-bottom: 10px;">error_outline</i>
                            <h5>Terjadi Masalah</h5>
                            <p><?php echo htmlspecialchars($error); ?></p>
                            <p>Silakan periksa kembali data Anda di halaman Kriteria, Lokasi, atau Matriks.</p>
                        </div>
                    <?php elseif (empty($locations) || empty($criteria_list)): ?>
                         <div class="error-message-card" style="background-color: #fff9c4; color: #795548; border-color: #fff176;">
                            <i class="material-icons" style="font-size: 3rem; margin-bottom: 10px;">info_outline</i>
                            <h5>Data Belum Lengkap</h5>
                            <p>Belum ada lokasi cabang atau kriteria yang terdaftar untuk proyek ini. Tidak ada data untuk dihitung.</p>
                            <p>Silakan tambahkan data di halaman Lokasi Cabang dan Kriteria & Bobot.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Alternatif</th>
                                        <th colspan="<?php echo count($criteria_list); ?>" class="criteria-header">Matriks Keputusan (Terkonversi Jika Cost)</th>
                                        <th rowspan="2" class="criteria-header" title="Vektor S (Si)">S<sub>i</sub></th>
                                        <th rowspan="2" class="criteria-header" title="Nilai Preferensi (Vi)">V<sub>i</sub></th>
                                        <th rowspan="2" class="criteria-header">Rank</th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($criteria_list as $criteria): ?>
                                            <th class="criteria-subheader" title="<?php echo htmlspecialchars($criteria['criteria_name']) . ' (' . ucfirst($criteria['type']) . ')'; ?>">
                                                <?php echo htmlspecialchars($criteria['criteria_code']); ?>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($ranking_data)): ?>
                                        <?php $no_table = 1; ?>
                                        <?php foreach ($ranking_data as $data_row): ?>
                                            <?php
                                                $current_location_id = $data_row['location_id'];
                                                $current_rank = $data_row['ranking']; 
                                                $is_best_rank = ($current_rank == 1);
                                            ?>
                                            <tr class="<?php echo $is_best_rank ? 'highlight-row' : ''; ?>">
                                                <td><?php echo $no_table++; ?></td>
                                                <td class="alternatif-cell"><?php echo htmlspecialchars($data_row['branch_name']); ?></td>
                                                <?php
                                                    $matrix_display_row = [];
                                                    foreach ($criteria_list as $criteria) {
                                                        $criteria_id_loop = $criteria['id']; 
                                                        $value_matrix = isset($matrix_decision[$current_location_id][$criteria_id_loop]) ? (float)$matrix_decision[$current_location_id][$criteria_id_loop] : 0;
                                                        if ($criteria['type'] === 'cost') {
                                                            $matrix_display_row[$criteria_id_loop] = ($value_matrix != 0) ? (1 / $value_matrix) : 0; 
                                                        } else {
                                                            $matrix_display_row[$criteria_id_loop] = $value_matrix;
                                                        }
                                                    }
                                                ?>
                                                <?php foreach ($criteria_list as $criteria): ?>
                                                    <td class="value-cell"><?php echo htmlspecialchars(number_format((float)$matrix_display_row[$criteria['id']], 4, '.', '')); ?></td>
                                                <?php endforeach; ?>
                                                <td class="result-cell"><?php echo htmlspecialchars(number_format((float)$data_row['Si'], 6, '.', '')); ?></td>
                                                <td class="result-cell"><?php echo htmlspecialchars(number_format((float)$data_row['Vi'], 6, '.', '')); ?></td>
                                                <td class="ranking-cell"><?php echo htmlspecialchars($current_rank); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?php echo 5 + count($criteria_list); ?>" class="center-align" style="padding: 20px;">
                                                Tidak ada data peringkat yang dapat ditampilkan. Ini mungkin karena data input belum lengkap atau ada masalah dalam perhitungan.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($error) && !empty($ranking_data)): ?>
                <div class="card"> <div class="card-header">
                        <h2 class="card-title">Kesimpulan & Rekomendasi</h2>
                    </div>
                    <div class="card-content" style="padding: 20px;">
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="card-panel blue lighten-5" style="border-left: 5px solid var(--primary-color);">
                                    <h5 style="margin-top: 0; color: var(--primary-dark);">Hasil Akhir Ranking:</h5>
                                    <ol style="padding-left: 20px;">
                                        <?php foreach ($ranking_data as $data_row_rank): ?>
                                            <li style="<?php if($data_row_rank['ranking'] == 1) echo 'font-weight: bold; color: var(--primary-dark);'; ?>">
                                                <?php echo htmlspecialchars($data_row_rank['branch_name']); ?>
                                                (V<sub>i</sub> = <?php echo htmlspecialchars(number_format((float)$data_row_rank['Vi'], 6, '.', '')); ?>)
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="card-panel teal lighten-5" style="border-left: 5px solid var(--secondary-color);">
                                    <h5 style="margin-top: 0; color: #004d40;">Rekomendasi:</h5>
                                    <?php $best_alternative_data = $ranking_data[0]; ?>
                                    <p>Berdasarkan perhitungan Weighted Product Model (WPM), <strong><?php echo htmlspecialchars($best_alternative_data['branch_name']); ?></strong> merupakan alternatif terbaik dengan nilai preferensi tertinggi (V<sub>i</sub> = <?php echo htmlspecialchars(number_format((float)$best_alternative_data['Vi'], 6, '.', '')); ?>).</p>
                                    <p>Keputusan akhir sebaiknya juga mempertimbangkan faktor-faktor kualitatif lain yang mungkin tidak tercakup dalam model perhitungan ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div> </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('.sidenav').sidenav();
            $('.modal').modal(); 
            $('select').formSelect(); 
            $('.tooltipped').tooltip(); 

            <?php if (!empty($message) && empty($error)): ?>
                M.toast({html: '<?php echo addslashes($message); ?>', classes: 'green lighten-1 white-text'});
            <?php endif; ?>
            
            // Fungsi untuk tombol Cetak Laporan (Export PDF via Print Dialog)
            $('#export-pdf-btn-js').on('click', function(e) {
                e.preventDefault(); 
                window.print(); // Memicu dialog cetak browser
            });
        });
    </script>
</body>
</html>