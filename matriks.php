<?php
    session_start(); // Mulai session untuk mengambil data user
    // require_once 'db_connect.php'; // Sertakan file koneksi database LAMA
    // GANTIKAN DENGAN YANG DI BAWAH INI JIKA db_connect.php MENGHASILKAN $conn
    require_once 'db_connect.php'; // Pastikan ini menghasilkan variabel $conn (MySQLi)

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
        header("Location: admin.php");
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

    // Handle form submission for updating matrix data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_matrix'])) {
        $data_matrix = $_POST['matrix_data'];
        $all_successful = true;

        foreach ($data_matrix as $location_id => $criteria_values) {
            foreach ($criteria_values as $criteria_id => $value) {
                $value = (float)$value;
                if ($value < 0) $value = 0;

                // Update or Insert matrix_data menggunakan MySQLi
                $stmt = $conn->prepare("INSERT INTO matrix_data (project_id, location_id, criteria_id, value) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE value = ?");
                if ($stmt) {
                    // Parameter types: i = integer, d = double
                    // project_id, location_id, criteria_id, value_for_insert, value_for_update
                    $stmt->bind_param("iiidd", $project_id, $location_id, $criteria_id, $value, $value);
                    if (!$stmt->execute()) {
                        $_SESSION['error'] = 'Gagal memperbarui matriks keputusan: ' . $stmt->error;
                        $all_successful = false;
                        break 2; // Hentikan loop jika ada error
                    }
                    $stmt->close();
                } else {
                    $_SESSION['error'] = 'Gagal menyiapkan statement matriks: ' . $conn->error;
                    $all_successful = false;
                    break 2; // Hentikan loop jika ada error
                }
            }
        }

        if ($all_successful) {
            $_SESSION['message'] = 'Matriks keputusan berhasil diperbarui!';
        }
        header("Location: matriks.php?project_id=" . htmlspecialchars($project_id)); // Tambahkan project_id untuk konsistensi
        exit();
    }

    // Fetch locations for the current project menggunakan MySQLi
    $locations = [];
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
        $error = 'Gagal mengambil data lokasi: ' . $conn->error;
    }


    // Fetch criteria for the current project menggunakan MySQLi
    $criteria_list = [];
    // Modifikasi kueri untuk menyertakan value_unit
    $stmt_criteria = $conn->prepare("SELECT id, criteria_code, criteria_name, type, weight_percentage, value_unit FROM criteria WHERE project_id = ? ORDER BY criteria_code ASC");
    if ($stmt_criteria) {
        $stmt_criteria->bind_param("i", $project_id);
        $stmt_criteria->execute();
        $result_criteria = $stmt_criteria->get_result();
        while ($row = $result_criteria->fetch_assoc()) {
            $criteria_list[] = $row;
        }
        $stmt_criteria->close();
    } else {
        $error = 'Gagal mengambil data kriteria: ' . $conn->error; //
    }


    // Fetch existing matrix data menggunakan MySQLi
    $matrix_decision = [];
    $stmt_matrix_data = $conn->prepare("SELECT location_id, criteria_id, value FROM matrix_data WHERE project_id = ?");
    if ($stmt_matrix_data) {
        $stmt_matrix_data->bind_param("i", $project_id);
        $stmt_matrix_data->execute();
        $result_matrix_data = $stmt_matrix_data->get_result();
        while ($row = $result_matrix_data->fetch_assoc()) {
            // Pastikan location_id dan criteria_id ada sebagai kunci array sebelum mengisi nilai
            if (!isset($matrix_decision[$row['location_id']])) {
                $matrix_decision[$row['location_id']] = [];
            }
            $matrix_decision[$row['location_id']][$row['criteria_id']] = $row['value'];
        }
        $stmt_matrix_data->close();
    } else {
        $error = 'Gagal mengambil data matriks keputusan: ' . $conn->error;
    }


    // --- Perhitungan Matriks Terkonversi (Normalized Matrix) ---
    $matrix_normalized = [];
    if (!empty($locations) && !empty($criteria_list)) {
        foreach ($locations as $location) {
            $location_id = $location['id'];
            foreach ($criteria_list as $criteria) {
                $criteria_id = $criteria['id'];
                // Pastikan kunci ada sebelum mengakses untuk menghindari warning
                $value = isset($matrix_decision[$location_id][$criteria_id]) ? (float)$matrix_decision[$location_id][$criteria_id] : 0;

                if ($criteria['type'] === 'cost') {
                    $matrix_normalized[$location_id][$criteria_id] = ($value != 0) ? (1 / $value) : 0;
                } else {
                    $matrix_normalized[$location_id][$criteria_id] = $value;
                }
            }
        }
    }

    // Check for messages from previous redirect
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    // Sebaiknya tutup koneksi di akhir script jika semua operasi database selesai
    // $conn->close(); // Baris ini bisa ditambahkan jika diperlukan, atau biarkan PHP menutupnya otomatis di akhir script.
    // Namun, karena Anda menggunakan redirect, pastikan semua operasi DB selesai sebelum redirect.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriks - BranchWise</title>
    
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

        .btn, .btn-large, .btn-small {
            border-radius: 7px !important; 
            padding-left: 25px; 
            padding-right: 25px;
        }

        .btn .material-icons.left {
            margin-right: 8px; 
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
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .badge-benefit {
            background-color: #2ecc71;
            color: white;
        }
        
        .badge-cost {
            background-color: #e74c3c;
            color: white;
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #7f8c8d;
            font-size: 16px;
            transition: color 0.2s;
            padding: 5px;
            border-radius: 50%;
            margin: 0 3px;
        }
        
        .action-btn:hover {
            background-color: rgba(0,0,0,0.05);
        }
        
        .edit-btn:hover {
            color: var(--primary-color);
        }
        
        .delete-btn:hover {
            color: #e74c3c;
        }
        
        /* Matrix specific styles */
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
        
        /* Responsive Adjustments */
        @media only screen and (max-width: 992px) {
            main {
                margin-left: 0;
            }
            
            .sidenav {
                transform: translateX(-105%);
            }
        }

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
        <li><a class="active" href="matriks.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a href="hasil_perhitungan.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <main>
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Matriks Keputusan</h1>
                </div>
                <div class="col s6 right-align">
                     <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
                        <i class="material-icons left">account_circle</i>
                        <?php echo htmlspecialchars($username); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Matriks Keputusan</h2>
                    <button type="submit" form="matrix-form" name="update_matrix" class="btn waves-effect waves-light blue darken-2">
                        <i class="material-icons left">save</i>Simpan Matriks </button>
                </div>
                
                <div class="table-responsive">
                    <form id="matrix-form" method="POST" action="matriks.php?project_id=<?php echo htmlspecialchars($project_id); ?>"> <table>
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center;">No</th>
                                    <th rowspan="2">Alternatif (Lokasi)</th>
                                    <th colspan="<?php echo count($criteria_list); ?>" class="criteria-header">Kriteria</th>
                                </tr>
                                <tr>
                                    <?php foreach ($criteria_list as $criteria): ?>
                                        <th class="criteria-subheader">
                                            <?php echo htmlspecialchars($criteria['criteria_code']); ?>
                                            <br>
                                            (<?php echo htmlspecialchars($criteria['value_unit']); ?>)
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($locations) && !empty($criteria_list)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($locations as $location): ?>
                                        <tr>
                                            <td style="text-align:center;"><?php echo $no++; ?></td>
                                            <td class="alternatif-cell"><?php echo htmlspecialchars($location['branch_name']); ?></td>
                                            <?php foreach ($criteria_list as $criteria): ?>
                                                <td class="value-cell">
                                                    <input type="number" step="any" name="matrix_data[<?php echo $location['id']; ?>][<?php echo $criteria['id']; ?>]" 
                                                           value="<?php echo isset($matrix_decision[$location['id']][$criteria['id']]) ? htmlspecialchars($matrix_decision[$location['id']][$criteria['id']]) : ''; ?>" 
                                                           required style="width: 80px; text-align: center; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?php echo 2 + count($criteria_list); ?>" class="center-align">
                                            Silakan tambahkan lokasi dan kriteria terlebih dahulu untuk proyek ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Matriks Terkonversi (Cost menjadi Benefit)</h2>
                </div>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align:center;">No</th>
                                <th rowspan="2">Alternatif (Lokasi)</th>
                                <th colspan="<?php echo count($criteria_list); ?>" class="criteria-header">Kriteria</th>
                            </tr>
                            <tr>
                                <?php foreach ($criteria_list as $criteria): ?>
                                     <th class="criteria-subheader"><?php echo htmlspecialchars($criteria['criteria_code']); ?><br>(Terkonversi)</th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($locations) && !empty($criteria_list) && !empty($matrix_normalized)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($locations as $location): ?>
                                    <tr>
                                        <td style="text-align:center;"><?php echo $no++; ?></td>
                                        <td class="alternatif-cell"><?php echo htmlspecialchars($location['branch_name']); ?></td>
                                        <?php foreach ($criteria_list as $criteria): ?>
                                            <td class="value-cell">
                                                <?php 
                                                    $display_value = isset($matrix_normalized[$location['id']][$criteria['id']]) ? 
                                                                     number_format((float)$matrix_normalized[$location['id']][$criteria['id']], 4, '.', '') : 'N/A'; // Format angka
                                                    echo htmlspecialchars($display_value);
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                     <td colspan="<?php echo 2 + count($criteria_list); ?>" class="center-align">
                                        Tidak ada data matriks terkonversi. Silakan input matriks keputusan di atas dan simpan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('.sidenav').sidenav();
            // $('.dropdown-trigger').dropdown(); // Hapus atau pastikan elemen .dropdown-trigger ada jika ingin digunakan
            $('.modal').modal();
            $('select').formSelect();

            <?php if ($message): ?>
                M.toast({html: '<?php echo addslashes($message); ?>', classes: 'green'}); // Tambah kelas untuk styling
            <?php endif; ?>
            <?php if ($error): ?>
                M.toast({html: '<?php echo addslashes($error); ?>', classes: 'red darken-2'});
            <?php endif; ?>
        });
    </script>
</body>
</html>