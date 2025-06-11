<?php
    session_start(); // Mulai session untuk mengambil data user
    require_once 'db_connect.php'; // Sertakan file koneksi database (MySQLi version)

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
    if ($role !== 'user') { // Untuk admin, halaman manajemen akun ada di admin.php [cite: 5]
        header("Location: admin.php"); // Atau halaman lain yang sesuai untuk admin
        exit();
    }

    // --- PERBAIKAN: Ambil project_id dari URL (GET) dan simpan ke session ---
    // Ini penting agar project_id tetap ada saat navigasi ke halaman kriteria
    if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
        $_SESSION['current_project_id'] = (int)$_GET['project_id'];
    }
    // --- AKHIR PERBAIKAN ---

    // Ambil project_id dari session, jika tidak ada, redirect ke dashboard
    // Untuk bisa mengakses halaman ini, user harus memilih salah satu proyek pada halaman dashboard [cite: 18]
    if (!isset($_SESSION['current_project_id'])) {
        header("Location: dashboard.php");
        exit();
    }
    $project_id = $_SESSION['current_project_id']; // Gunakan project_id dari session untuk seluruh halaman ini

    // Inisialisasi pesan
    $message = '';
    $error = '';

    // Handle form submission for adding/editing criteria
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil project_id dari POST data karena form disubmit kembali ke halaman ini
        // Pastikan project_id_from_post selalu ada dan valid
        $project_id_from_post = $_POST['project_id'] ?? null;
        if (!$project_id_from_post || !is_numeric($project_id_from_post)) {
            // Fallback to session project_id if POST is missing or invalid
            $project_id_from_post = $project_id;
        }
        
        if (isset($_POST['add_criteria']) || isset($_POST['edit_criteria'])) {
            $criteria_code = $_POST['criteria_code'];
            $criteria_name = $_POST['criteria_name'];
            $weight_percentage = $_POST['weight_percentage'];
            $type = $_POST['type'];
            $value_unit = $_POST['value_unit'];

            // Fetch current criteria count for validation [cite: 30]
            $stmt_count = $conn->prepare("SELECT COUNT(*) FROM criteria WHERE project_id = ?"); // MySQLi: positional placeholder '?'
            if ($stmt_count) {
                $stmt_count->bind_param("i", $project_id_from_post); // MySQLi: bind_param with type string "i" for integer
                $stmt_count->execute();
                $stmt_count->bind_result($current_criteria_count); // Bind result to a variable
                $stmt_count->fetch();
                $stmt_count->close();
            } else {
                $error = 'Gagal menyiapkan statement count: ' . $conn->error;
                $current_criteria_count = 0; // Set default
            }

            if (isset($_POST['add_criteria'])) {
                // Validasi jumlah kriteria [cite: 30]
                if ($current_criteria_count >= 5) {
                    $_SESSION['error'] = 'Tidak bisa menambahkan lebih dari 5 kriteria.';
                } else {
                    // Insert new criteria [cite: 29, 33]
                    $stmt = $conn->prepare("INSERT INTO criteria (project_id, criteria_code, criteria_name, weight_percentage, type, value_unit) VALUES (?, ?, ?, ?, ?, ?)");
                    // MySQLi: bind_param with type string "ississ" (int, string, string, int, string, string)
                    // Sesuaikan tipe data bind_param: project_id(i), criteria_code(s), criteria_name(s), weight_percentage(i), type(s), value_unit(s)
                    if ($stmt) {
                        $stmt->bind_param("ississ", $project_id_from_post, $criteria_code, $criteria_name, $weight_percentage, $type, $value_unit);
                        if ($stmt->execute()) {
                            $_SESSION['message'] = 'Kriteria berhasil ditambahkan!';
                        } else {
                            // Check for duplicate entry error number (e.g., 1062 for MySQL duplicate key)
                            if ($conn->errno == 1062) {
                                $_SESSION['error'] = 'Kode kriteria sudah ada untuk proyek ini. Mohon gunakan kode lain.';
                            } else {
                                $_SESSION['error'] = 'Gagal menambahkan kriteria: ' . $stmt->error;
                            }
                        }
                        $stmt->close();
                    } else {
                         $_SESSION['error'] = 'Gagal menyiapkan statement tambah: ' . $conn->error;
                    }
                }
            } elseif (isset($_POST['edit_criteria'])) {
                $criteria_id = $_POST['criteria_id'];
                // Update existing criteria [cite: 31, 33]
                $stmt = $conn->prepare("UPDATE criteria SET criteria_code = ?, criteria_name = ?, weight_percentage = ?, type = ?, value_unit = ? WHERE id = ? AND project_id = ?");
                // MySQLi: bind_param with type string "ssisssii"
                // Sesuaikan tipe data bind_param: criteria_code(s), criteria_name(s), weight_percentage(i), type(s), value_unit(s), id(i), project_id(i)
                if ($stmt) {
                    $stmt->bind_param("ssisssii", $criteria_code, $criteria_name, $weight_percentage, $type, $value_unit, $criteria_id, $project_id_from_post);
                    if ($stmt->execute()) {
                        $_SESSION['message'] = 'Kriteria berhasil diperbarui!';
                    } else {
                        // Check for duplicate entry error number
                        if ($conn->errno == 1062) {
                            $_SESSION['error'] = 'Kode kriteria sudah ada untuk proyek ini. Mohon gunakan kode lain.';
                        } else {
                            $_SESSION['error'] = 'Gagal memperbarui kriteria: ' . $stmt->error;
                        }
                    }
                    $stmt->close();
                } else {
                     $_SESSION['error'] = 'Gagal menyiapkan statement edit: ' . $conn->error;
                }
            }
            // Redirect setelah POST, sertakan project_id di URL
            header("Location: kriteria.php?project_id=" . htmlspecialchars($project_id_from_post));
            exit();
        }
    } elseif (isset($_GET['delete_criteria'])) {
        $criteria_id = $_GET['delete_criteria'];
        // Ambil project_id dari GET data karena URL ini datang dari link hapus
        $project_id_from_get = $_GET['project_id'] ?? null;
        if (!$project_id_from_get || !is_numeric($project_id_from_get)) {
            // Fallback to session project_id if GET is missing or invalid
            $project_id_from_get = $project_id;
        }

        // Hapus kriteria [cite: 32, 33]
        $stmt = $conn->prepare("DELETE FROM criteria WHERE id = ? AND project_id = ?"); // MySQLi: positional placeholders '?'
        if ($stmt) {
            $stmt->bind_param("ii", $criteria_id, $project_id_from_get); // MySQLi: bind_param with type string "ii"
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Kriteria berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus kriteria: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = 'Gagal menyiapkan statement delete: ' . $conn->error;
        }
        // Redirect setelah DELETE, sertakan project_id di URL
        header("Location: kriteria.php?project_id=" . htmlspecialchars($project_id_from_get));
        exit();
    }

    // Fetch criteria data for the current project [cite: 28]
    $stmt = $conn->prepare("SELECT id, criteria_code, criteria_name, weight_percentage, type, value_unit FROM criteria WHERE project_id = ? ORDER BY criteria_code ASC");
    if ($stmt) {
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $result = $stmt->get_result(); // Get result set for fetching
        $criteria_data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $criteria_data[] = $row;
            }
        }
        $stmt->close();
    } else {
        $error = 'Gagal mengambil data kriteria: ' . $conn->error;
        $criteria_data = []; // Ensure it's an empty array if there's an error
    }

    // Koneksi ditutup setelah semua data diambil, sebelum HTML dimulai
    $conn->close();

    // Calculate total weight percentage
    $total_weight = 0;
    foreach ($criteria_data as $criteria) {
        $total_weight += $criteria['weight_percentage'];
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriteria & Bobot - BranchWise</title>
    <link rel="shortcut icon" href="assets/image/logo.png" type="image/gif">
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
            transition: margin-left 0.3s ease-in-out; /* Tambahkan animasi transisi */
        }
        
        /* Sidebar Styles */
        .sidenav {
            width: 250px;
            padding-top: 20px;
            position: fixed;
            height: 100vh;
            z-index: 999;
            transition: width 0.3s ease-in-out; /* Tambahkan animasi transisi */
        }
        
        /* Kelas baru untuk main content saat sidebar kecil */
        main.expanded {
            margin-left: 70px; /* Jarak baru saat sidebar kecil (sesuaikan jika perlu) */
        }

        /* Kelas baru untuk sidebar saat kecil */
        .sidenav.minimized {
            width: 70px; /* Lebar baru sidebar (sesuaikan jika perlu) */
        }

        /* Menyembunyikan teks & menyesuaikan ikon saat sidebar kecil */
        .sidenav.minimized .link-text {
            display: none;
        }
        
        .sidenav.minimized .logo .link-text {
            display: none;
            
        }
        
        .sidenav.minimized li > a {
            justify-content: center;
            padding: 0 0 0 20px;
        }
        
        .sidenav.minimized li > a > i {
            margin: 0 !important;
        }
        .sidenav.minimized .logo {
            text-align: center;
        }
        
        .sidenav.minimized .logo .material-icons {
            margin: 13px 0 0 -13px !important;
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
        
        /* Perubahan: Menambahkan atau menimpa gaya untuk tombol .btn Materialize */
        .btn, .btn-large, .btn-small { /* Target semua ukuran tombol Materialize */
            border-radius: 7px !important; /* Membuat tombol melengkung seperti kapsul */
            padding-left: 25px; /* Menyesuaikan padding agar tombol lebih proporsional */
            padding-right: 25px; /* Menyesuaikan padding agar tombol lebih proporsional */
        }

        /* Jika Anda ingin tombol dengan ikon tetap memiliki padding yang lebih kecil */
        .btn .material-icons.left {
            margin-right: 8px; /* Jarak default antara ikon dan teks */
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
            color: var(--primary-color);
        }
        
        .edit-btn:hover {
            color: var(--primary-color);
        }
        
        .delete-btn:hover {
            color: #e74c3c;
        }
        
        .total-weight {
            padding: 15px 20px;
            background-color: #f5f5f5;
            border-top: 1px solid #eee;
            font-weight: 500;
            text-align: right;
        }
        
        /* Add Criteria Button */
        .add-criteria-btn {
            margin-bottom: 20px;
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

        /* Mengatur agar logo bisa diklik (TETAP SAMA) */
        .sidenav .logo {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo" style="cursor: pointer;"> 
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;">
                        <i class="sets material-icons left">settings</i>
                        <span class="link-text">BranchWise</span> </span>
                </div>
            </div>
        </li>
        <li><a href="dashboard.php"><i class="material-icons">dashboard</i><span class="link-text">Dashboard</span></a></li>
        <li><a href="lokasi.php"><i class="material-icons">location_on</i><span class="link-text">Lokasi Cabang</span></a></li>
        <li><a class="active" href="kriteria.php"><i class="material-icons">assessment</i><span class="link-text">Kriteria & Bobot</span></a></li>
        <li><a href="matriks.php"><i class="material-icons">grid_on</i><span class="link-text">Matriks</span></a></li>
        <li><a href="hasil_perhitungan.php"><i class="material-icons">calculate</i><span class="link-text">Hasil Perhitungan</span></a></li>
        <li><div class="divider"></div></li>
        <li><a href="index.php"><i class="material-icons">exit_to_app</i><span class="link-text">Keluar</span></a></li>
    </ul>

    <main>
        <div class="main-content">
            <div class="header">
                <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                    <div class="col s6">
                        <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                        <h1 class="page-title">Kriteria & Bobot</h1>
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
                        <div class="row" style="margin-bottom: 0; width: 100%;"> 
                            <div class="col s12 m6"> 
                                <h2 class="card-title">Daftar Kriteria & Bobot</h2>
                            </div>
                            <div class="col s12 m6 right-align"> 
                                <a href="#add-criteria-modal" class="btn waves-effect waves-light blue darken-2 modal-trigger">
                                    <i class="material-icons left">add</i>Tambah Kriteria
                                </a>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Bobot</th>
                                    <th>Jenis</th>
                                    <th>Nilai Kriteria</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($criteria_data) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($criteria_data as $criteria): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($criteria['criteria_code']); ?></td>
                                            <td><?php echo htmlspecialchars($criteria['criteria_name']); ?></td>
                                            <td><?php echo htmlspecialchars($criteria['weight_percentage']); ?>%</td>
                                            <td><span class="badge badge-<?php echo htmlspecialchars($criteria['type']); ?>"><?php echo ucfirst(htmlspecialchars($criteria['type'])); ?></span></td>
                                            <td><?php echo htmlspecialchars($criteria['value_unit']); ?></td>
                                            <td>
                                                <button class="action-btn edit-btn" title="Edit"
                                                    data-id="<?php echo $criteria['id']; ?>"
                                                    data-code="<?php echo htmlspecialchars($criteria['criteria_code']); ?>"
                                                    data-name="<?php echo htmlspecialchars($criteria['criteria_name']); ?>"
                                                    data-weight="<?php echo htmlspecialchars($criteria['weight_percentage']); ?>"
                                                    data-type="<?php echo htmlspecialchars($criteria['type']); ?>"
                                                    data-value_unit="<?php echo htmlspecialchars($criteria['value_unit']); ?>">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <a href="kriteria.php?delete_criteria=<?php echo $criteria['id']; ?>&project_id=<?php echo htmlspecialchars($project_id); ?>" class="action-btn delete-btn" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kriteria <?php echo htmlspecialchars($criteria['criteria_name']); ?>?');">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="center-align">Tidak ada kriteria yang ditemukan untuk proyek ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="total-weight">
                        Total Bobot: <?php echo $total_weight; ?>%
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <div id="add-criteria-modal" class="modal">
        <div class="modal-content">
            <h4 id="modal-title">Tambah Kriteria Baru</h4>
            <form id="criteria-form" method="POST" action="kriteria.php">
                <input type="hidden" name="criteria_id" id="criteria_id">
                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>"> <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="criteria_code" name="criteria_code" type="text" class="validate" required>
                        <label for="criteria_code">Kode Kriteria</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="criteria_name" name="criteria_name" type="text" class="validate" required>
                        <label for="criteria_name">Nama Kriteria</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="criteria_weight" name="weight_percentage" type="number" min="1" max="100" class="validate" required>
                        <label for="criteria_weight">Bobot (%)</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="criteria_type" name="type" required>
                            <option value="" disabled selected>Pilih Jenis Kriteria</option>
                            <option value="benefit">Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                        <label>Jenis Kriteria</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="criteria_value_unit" name="value_unit" type="text" class="validate">
                        <label for="criteria_value_unit">Nilai Kriteria (misal: 0-10, juta jiwa, ribu)</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button type="submit" form="criteria-form" name="add_criteria" id="submit-btn" class="waves-effect waves-green btn blue">Simpan Kriteria</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Initialize modal
            $('.modal').modal({
                onOpenEnd: function(el) {
                    // Ensure labels are correctly activated after content load
                    M.updateTextFields();
                    $('select').formSelect();
                },
                onCloseEnd: function(el) {
                    // Reset form on modal close
                    $('#criteria-form')[0].reset();
                    $('#modal-title').text('Tambah Kriteria Baru');
                    $('#submit-btn').attr('name', 'add_criteria').text('Simpan Kriteria');
                    $('#criteria_id').val('');
                    // Deactivate labels again
                    M.updateTextFields();
                }
            });
            
            // Initialize select
            $('select').formSelect();

            // Display messages (if any)
            <?php if ($message): ?>
                M.toast({html: '<?php echo $message; ?>'});
            <?php endif; ?>
            <?php if ($error): ?>
                M.toast({html: '<?php echo $error; ?>', classes: 'red darken-2'});
            <?php endif; ?>
            
            // Edit button functionality
            $('.edit-btn').click(function() {
                const criteriaId = $(this).data('id');
                const criteriaCode = $(this).data('code');
                const criteriaName = $(this).data('name');
                const criteriaWeight = $(this).data('weight');
                const criteriaType = $(this).data('type');
                const criteriaValueUnit = $(this).data('value_unit');

                $('#modal-title').text(`Edit Kriteria: ${criteriaName}`);
                $('#submit-btn').attr('name', 'edit_criteria').text('Perbarui Kriteria');
                
                $('#criteria_id').val(criteriaId);
                $('#criteria_code').val(criteriaCode);
                $('#criteria_name').val(criteriaName);
                $('#criteria_weight').val(criteriaWeight);
                $('#criteria_type').val(criteriaType);
                $('#criteria_value_unit').val(criteriaValueUnit); 

                $('select').formSelect();     
                M.updateTextFields();         
                
                $('#add-criteria-modal').modal('open');
            });
        });

        $(document).ready(function(){

            // Inisialisasi komponen Materialize yang sudah ada
            $('.sidenav').sidenav();
            // ... inisialisasi lain seperti modal, dll. jika ada ...

            
            // --- JAVASCRIPT KUSTOM BARU DIMULAI DI SINI ---
            $('.logo').on('click', function(e) {
                e.preventDefault();

                // Cukup toggle kelas, CSS akan menangani sisanya
                $('#slide-out').toggleClass('minimized');
                $('main').toggleClass('expanded');
                $('.header').toggleClass('expanded');
            });
        });
    </script>
</body>
</html>