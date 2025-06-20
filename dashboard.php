<?php
session_start(); // Memulai sesi PHP

// --- SOLUSI: Hapus project_id dari session saat kembali ke dashboard ---
unset($_SESSION['current_project_id']);

// Sertakan file koneksi database PDO yang baru
include 'db_connect.php';

// Autentikasi dan Otorisasi (Tidak ada perubahan di sini)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];
$current_full_name = $_SESSION['full_name'];

// --- PERUBAHAN: Mengambil data proyek menggunakan PDO ---
$projects_data = [];
try {
    // Query SQL ini kompatibel dengan PostgreSQL, menggunakan placeholder '?'
    $sql = "SELECT id, project_name, description, priority, status, deadline, assignee FROM projects WHERE user_id = ? ORDER BY id DESC";
    
    // Siapkan statement
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi statement dengan melewatkan user_id ke dalam array
    $stmt->execute([$current_user_id]);
    
    // Ambil semua data proyek ke dalam array $projects_data
    $projects_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Menangani error jika query gagal
    // die("Error: Tidak dapat mengambil data proyek. " . $e->getMessage());
    $projects_data = []; // Pastikan array kosong jika gagal
}
// Koneksi PDO akan ditutup secara otomatis
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manajemen Tugas</title>
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
            margin-left: 250px; /* Jarak awal (sudah benar) */
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
            border-radius: 50px !important; /* Perubahan: Radius tombol menjadi kapsul */
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
        
        /* Task Management Styles */
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            padding: 24px;
        }
        
        .task-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 20px;
            transition: transform 0.2s;
        }
        
        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .task-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .task-actions {
            display: flex;
            gap: 10px;
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
        }
        
        .action-btn:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--primary-color);
        }
        
        .delete-btn:hover {
            color: #e74c3c;
        }
        
        .task-info {
            margin-bottom: 15px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .info-label {
            color: #7f8c8d;
        }
        
        .info-value {
            font-weight: 500;
        }
        
        .priority-high {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .priority-medium {
            color: #f39c12;
            font-weight: bold;
        }
        
        .priority-low {
            color: #2ecc71;
            font-weight: bold;
        }
        
        .task-description {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
        }
        
        .status-todo {
            background-color: #95a5a6;
        }
        
        .status-progress {
            background-color: #3498db;
        }
        
        .status-completed {
            background-color: #2ecc71;
        }
        
        .add-task-btn {
            margin: 24px;
        }
        
        /* Responsive Adjustments */
        @media only screen and (max-width: 992px) {
            main {
                margin-left: 0;
            }
            
            .sidenav {
                transform: translateX(-105%);
            }
            
            .task-grid {
                grid-template-columns: 1fr;
                padding: 16px;
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
        <li><a class="active" href="dashboard.php"><i class="material-icons">dashboard</i><span class="link-text">Dashboard</span></a></li>
        <li><a href="lokasi.php"><i class="material-icons">location_on</i><span class="link-text">Lokasi Cabang</span></a></li>
        <li><a href="kriteria.php"><i class="material-icons">assessment</i><span class="link-text">Kriteria & Bobot</span></a></li>
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
                        <h1 class="page-title">Dashboard Proyek</h1>
                    </div>
                    <div class="col s6 right-align">
                        <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
                            <i class="material-icons left">account_circle</i>
                            <?php echo htmlspecialchars($current_username); ?>
                        </span>
                    </div>
                    </div>
            </div>

            <div class="add-task-btn">
                <a href="#add-task-modal" class="btn waves-effect waves-light blue darken-2 modal-trigger">
                    <i class="material-icons left">add</i>Tambah Proyek Baru
                </a>
            </div>

            <div class="task-grid">
                <?php if (!empty($projects_data)): ?>
                    <?php foreach ($projects_data as $project): 
                        // Tentukan kelas prioritas untuk styling
                        $priority_class = '';
                        if ($project['priority'] == 'tinggi') $priority_class = 'priority-high';
                        else if ($project['priority'] == 'sedang') $priority_class = 'priority-medium';
                        else if ($project['priority'] == 'rendah') $priority_class = 'priority-low';

                        // Tentukan kelas status untuk styling
                        $status_class = '';
                        if ($project['status'] == 'belum dimulai') $status_class = 'status-todo';
                        else if ($project['status'] == 'dalam pengerjaan') $status_class = 'status-progress';
                        else if ($project['status'] == 'selesai') $status_class = 'status-completed';
                    ?>
                    <div class="task-card" data-project-id="<?php echo $project['id']; ?>">
                        <div class="task-header">
                            <h2 class="task-title" data-task-title="<?php echo htmlspecialchars($project['project_name']); ?>">
                                <a href="lokasi.php?project_id=<?php echo $project['id']; ?>" style="color: inherit; text-decoration: none;">
                                    <?php echo htmlspecialchars($project['project_name']); ?>
                                </a>
                            </h2>
                            <div class="task-actions">
                                <button class="action-btn edit-btn modal-trigger" href="#edit-task-modal" title="Edit"
                                    data-project-id="<?php echo $project['id']; ?>"
                                    data-project-name="<?php echo htmlspecialchars($project['project_name']); ?>"
                                    data-description="<?php echo htmlspecialchars($project['description']); ?>"
                                    data-priority="<?php echo htmlspecialchars($project['priority']); ?>"
                                    data-deadline="<?php echo htmlspecialchars($project['deadline']); ?>"
                                    data-assignee="<?php echo htmlspecialchars($project['assignee']); ?>"
                                    data-status="<?php echo htmlspecialchars($project['status']); ?>">
                                    <i class="material-icons">edit</i>
                                </button>
                                <button class="action-btn delete-btn" title="Hapus"
                                    data-project-id="<?php echo $project['id']; ?>"
                                    data-project-name="<?php echo htmlspecialchars($project['project_name']); ?>">
                                    <i class="material-icons">delete</i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="task-info">
                            <div class="info-row">
                                <span class="info-label">Prioritas:</span>
                                <span class="info-value <?php echo $priority_class; ?>" data-task-priority="<?php echo htmlspecialchars($project['priority']); ?>">
                                    <?php echo htmlspecialchars(ucfirst($project['priority'])); ?>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Deadline:</span>
                                <span class="info-value" data-task-deadline="<?php echo htmlspecialchars($project['deadline']); ?>">
                                    <?php echo htmlspecialchars($project['deadline'] ? date('d F Y', strtotime($project['deadline'])) : 'N/A'); ?>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Penanggung Jawab:</span>
                                <span class="info-value" data-task-assignee="<?php echo htmlspecialchars($project['assignee']); ?>">
                                    <?php echo htmlspecialchars($project['assignee']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <span class="status-badge <?php echo $status_class; ?>" data-task-status="<?php echo htmlspecialchars($project['status']); ?>">
                            <?php echo htmlspecialchars(ucfirst($project['status'])); ?>
                        </span>
                        
                        <div class="task-description" data-task-description="<?php echo htmlspecialchars($project['description']); ?>">
                            <?php echo htmlspecialchars($project['description']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col s12 center-align" style="grid-column: 1 / -1;">
                        <p class="grey-text">Anda belum memiliki proyek. Klik "Tambah Proyek Baru" untuk memulai.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="add-task-modal" class="modal">
        <div class="modal-content">
            <h4>Tambah Proyek Baru</h4>
            <form id="add-task-form"> <div class="row">
                    <div class="input-field col s12">
                        <input id="task_name" name="project_name" type="text" class="validate" required>
                        <label for="task_name">Nama Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="task_description" name="description" class="materialize-textarea"></textarea>
                        <label for="task_description">Deskripsi Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="task_priority" name="priority" required>
                            <option value="" disabled selected>Pilih prioritas</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="sedang">Sedang</option>
                            <option value="rendah">Rendah</option>
                        </select>
                        <label>Prioritas</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="task_status" name="status" required>
                            <option value="belum dimulai">Belum Dimulai</option>
                            <option value="dalam pengerjaan">Dalam Pengerjaan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="task_deadline" name="deadline" type="text" class="datepicker">
                        <label for="task_deadline">Deadline</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="task_assignee" name="assignee" type="text">
                        <label for="task_assignee">Penanggung Jawab</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button class="waves-effect waves-green btn blue" id="save-add-task-btn">Simpan</button>
        </div>
    </div>

    <div id="edit-task-modal" class="modal">
        <div class="modal-content">
            <h4>Edit Proyek</h4> 
            <form id="edit-task-form"> <input type="hidden" id="edit_project_id" name="project_id"> <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_task_name" name="project_name" type="text" class="validate" required>
                        <label for="edit_task_name">Nama Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit_task_description" name="description" class="materialize-textarea"></textarea>
                        <label for="edit_task_description">Deskripsi Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="edit_task_priority" name="priority" required>
                            <option value="" disabled selected>Pilih prioritas</option>
                            <option value="tinggi">Tinggi</option>
                            <option value="sedang">Sedang</option>
                            <option value="rendah">Rendah</option>
                        </select>
                        <label>Prioritas</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="edit_task_status" name="status" required>
                            <option value="belum dimulai">Belum Dimulai</option>
                            <option value="dalam pengerjaan">Dalam Pengerjaan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_task_deadline" name="deadline" type="text" class="datepicker">
                        <label for="edit_task_deadline">Deadline</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_task_assignee" name="assignee" type="text">
                        <label for="edit_task_assignee">Penanggung Jawab</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button class="waves-effect waves-green btn blue" id="save-edit-task-btn">Simpan Perubahan</button> 
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Perubahan: Menghapus inisialisasi dropdown karena elemennya telah dihapus dari HTML
            // $('.dropdown-trigger').dropdown(); 
            
            // Initialize modal (keduanya sekarang diinisialisasi)
            $('#add-task-modal').modal(); 
            $('#edit-task-modal').modal(); 
            
            // Initialize select (penting untuk modal)
            $('select').formSelect();
            
            // Initialize datepicker (penting untuk modal)
            $('.datepicker').datepicker({
                format: 'dd mmm yyyy', // Format tanggal
                autoClose: true
            });
            
            // Perubahan: Delete button functionality
            $('.delete-btn').click(function(e) {
                e.preventDefault(); // Mencegah link default beraksi
                const projectId = $(this).data('project-id'); // Ambil ID proyek dari data attribute
                const projectName = $(this).data('project-name'); // Ambil nama proyek untuk konfirmasi
                
                if (confirm(`Apakah Anda yakin ingin menghapus proyek "${projectName}"?`)) {
                    // Mengirim request AJAX untuk menghapus proyek
                    $.ajax({
                        url: 'handle_dashboard_actions.php', // File PHP untuk menangani hapus
                        type: 'POST',
                        data: {
                            action: 'delete_project', // Tentukan aksi delete
                            project_id: projectId
                        },
                        dataType: 'json', // Harap respons dalam format JSON
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: `Proyek "${projectName}" berhasil dihapus!`});
                                location.reload(); // Refresh halaman setelah hapus
                            } else {
                                M.toast({html: `Gagal menghapus proyek: ${response.message}`});
                            }
                        },
                        error: function() {
                            M.toast({html: 'Terjadi kesalahan saat menghapus proyek.'});
                        }
                    });
                }
            });
            
            // Perubahan: Edit button functionality
            $('.edit-btn').click(function() {
                const projectId = $(this).data('project-id'); // Ambil ID proyek dari data attribute
                const projectName = $(this).data('project-name'); 
                const description = $(this).data('description');
                const priority = $(this).data('priority');
                const deadline = $(this).data('deadline');
                const assignee = $(this).data('assignee');
                const status = $(this).data('status');

                // Isi formulir di modal edit
                $('#edit_project_id').val(projectId); // Isi hidden input ID
                $('#edit_task_name').val(projectName);
                $('#edit_task_description').val(description);
                M.textareaAutoResize($('#edit_task_description')); // Penting untuk textarea materialize

                // Set nilai select dan update display Materialize
                $('#edit_task_priority').val(priority);
                $('#edit_task_status').val(status);
                $('select').formSelect(); // Re-initialize selects after setting value

                // Set deadline dan update datepicker
                // Pastikan deadline tidak null atau kosong sebelum diubah menjadi objek Date
                if (deadline && deadline !== '0000-00-00') { 
                    const deadlineDate = new Date(deadline); 
                    $('#edit_task_deadline').datepicker('setDate', deadlineDate); 
                    $('#edit_task_deadline').val(deadline); // Set input value
                } else {
                    $('#edit_task_deadline').val(''); // Kosongkan jika tidak ada deadline
                    $('#edit_task_deadline').datepicker('destroy'); // Hapus datepicker jika perlu reset
                    $('#edit_task_deadline').datepicker({
                        format: 'dd mmm yyyy', // Re-initialize datepicker for empty field
                        autoClose: true
                    });
                }


                $('#edit_task_assignee').val(assignee);
                
                // Panggil label.active untuk memastikan label naik
                M.updateTextFields(); // Memastikan label input Materialize aktif jika input terisi

                // Buka modal edit (kelas modal-trigger juga bisa melakukannya, tapi ini lebih eksplisit)
                $('#edit-task-modal').modal('open');
            });

            // Perubahan: Submit form Tambah Proyek melalui AJAX
            $('#save-add-task-btn').click(function(e) {
                e.preventDefault(); // Mencegah submit form default
                
                const form = $('#add-task-form');
                if (form[0].checkValidity()) { // Memeriksa validasi HTML5 form
                    const formData = {
                        action: 'add_project', // Aksi: tambah proyek
                        project_name: $('#task_name').val(),
                        description: $('#task_description').val(),
                        priority: $('#task_priority').val(),
                        status: $('#task_status').val(),
                        deadline: $('#task_deadline').val(),
                        assignee: $('#task_assignee').val(),
                        user_id: <?php echo $current_user_id; ?> // Kirim user_id dari sesi
                    };

                    $.ajax({
                        url: 'handle_dashboard_actions.php', // File PHP untuk menangani aksi dashboard
                        type: 'POST',
                        data: formData,
                        dataType: 'json', 
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Proyek berhasil ditambahkan!'});
                                $('#add-task-modal').modal('close'); 
                                location.reload(); // Refresh halaman untuk melihat data baru
                            } else {
                                M.toast({html: `Gagal menambahkan proyek: ${response.message}`});
                            }
                        },
                        error: function(xhr, status, error) {
                            M.toast({html: 'Terjadi kesalahan saat menambahkan proyek.'});
                            console.error("AJAX Error:", status, error);
                            console.log("Response Text:", xhr.responseText);
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'});
                }
            });

            // Perubahan: Submit form Edit Proyek melalui AJAX
            $('#save-edit-task-btn').click(function(e) {
                e.preventDefault(); // Mencegah submit form default
                
                const form = $('#edit-task-form');
                if (form[0].checkValidity()) {
                    const formData = {
                        action: 'edit_project', // Aksi: edit proyek
                        project_id: $('#edit_project_id').val(),
                        project_name: $('#edit_task_name').val(),
                        description: $('#edit_task_description').val(),
                        priority: $('#edit_task_priority').val(),
                        status: $('#edit_task_status').val(),
                        deadline: $('#edit_task_deadline').val(),
                        assignee: $('#edit_task_assignee').val(),
                        user_id: <?php echo $current_user_id; ?> // Kirim user_id dari sesi
                    };
                    
                    $.ajax({
                        url: 'handle_dashboard_actions.php', // File PHP untuk menangani aksi dashboard
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Proyek berhasil diperbarui!'});
                                $('#edit-task-modal').modal('close');
                                location.reload(); 
                            } else {
                                M.toast({html: `Gagal memperbarui proyek: ${response.message}`});
                            }
                        },
                        error: function(xhr, status, error) {
                            M.toast({html: 'Terjadi kesalahan saat memperbarui proyek.'});
                            console.error("AJAX Error:", status, error);
                            console.log("Response Text:", xhr.responseText);
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'});
                }
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