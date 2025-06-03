<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manajemen Tugas</title>
    
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
    </style>
</head>
<body>
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo">
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;">BranchWise</span>
                </div>
            </div>
        </li>
        <li><a class="active" href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a href="lokasi.php"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
        <li><a href="kriteria.php"><i class="material-icons">assessment</i>Kriteria & Bobot</a></li>
        <li><a href="matriks.php"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a href="hasil_perhitungan.php"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="index.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <main>
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Dashboard Proyek</h1>
                </div>
                <div class="col s6 right-align">
                    <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
                        <i class="material-icons left">account_circle</i>
                        USERNAME
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
            <div class="task-card">
                <div class="task-header">
                    <h2 class="task-title" data-task-title="Cabang Baru Mertoyudan">Cabang Baru Mertoyudan</h2>
                    <div class="task-actions">
                        <button class="action-btn edit-btn modal-trigger" href="#edit-task-modal" title="Edit">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="action-btn delete-btn" title="Hapus">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
                
                <div class="task-info">
                    <div class="info-row">
                        <span class="info-label">Prioritas:</span>
                        <span class="info-value priority-high" data-task-priority="high">Tinggi</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Deadline:</span>
                        <span class="info-value" data-task-deadline="15 Jun 2023">15 Juni 2023</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Penanggung Jawab:</span>
                        <span class="info-value" data-task-assignee="Andi">Andi</span>
                    </div>
                </div>
                
                <span class="status-badge status-progress" data-task-status="progress">Dalam Pengerjaan</span>
                
                <div class="task-description" data-task-description="Di pilihan cabang Mertoyudan ada beberapa kendala, terutama perizinan karena berada di daerah dekat wisata.">
                    Di pilihan cabang Mertoyudan ada beberapa kendala, terutama perizinan karena berada di daerah dekat wisata.
                </div>
            </div>
            
            <div class="task-card">
                <div class="task-header">
                    <h2 class="task-title" data-task-title="Cabang Baru di Blondo">Cabang Baru di Blondo</h2>
                    <div class="task-actions">
                        <button class="action-btn edit-btn modal-trigger" href="#edit-task-modal" title="Edit">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="action-btn delete-btn" title="Hapus">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
                
                <div class="task-info">
                    <div class="info-row">
                        <span class="info-label">Prioritas:</span>
                        <span class="info-value priority-medium" data-task-priority="medium">Sedang</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Deadline:</span>
                        <span class="info-value" data-task-deadline="20 Jun 2023">20 Juni 2023</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Penanggung Jawab:</span>
                        <span class="info-value" data-task-assignee="Budi">Budi</span>
                    </div>
                </div>
                
                <span class="status-badge status-todo" data-task-status="todo">Belum Dimulai</span>
                
                <div class="task-description" data-task-description="Pastikan bahwa spot belum dibeli oleh pihak lain karena sangat strategis.">
                    Pastikan bahwa spot belum dibeli oleh pihak lain karena sangat strategis.
                </div>
            </div>
            
            <div class="task-card">
                <div class="task-header">
                    <h2 class="task-title" data-task-title="Perkiraan Cabang Potensial di Muntilan">Perkiraan Cabang Potensial di Muntilan</h2>
                    <div class="task-actions">
                        <button class="action-btn edit-btn modal-trigger" href="#edit-task-modal" title="Edit">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="action-btn delete-btn" title="Hapus">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
                
                <div class="task-info">
                    <div class="info-row">
                        <span class="info-label">Prioritas:</span>
                        <span class="info-value priority-high" data-task-priority="high">Tinggi</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Deadline:</span>
                        <span class="info-value" data-task-deadline="10 Jun 2023">10 Juni 2023</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Penanggung Jawab:</span>
                        <span class="info-value" data-task-assignee="Citra">Citra</span>
                    </div>
                </div>
                
                <span class="status-badge status-completed" data-task-status="completed">Selesai</span>
                
                <div class="task-description" data-task-description="Hasil dari pendirian cabang di Muntilan berdasarkan rekomendasi cukup memuaskan.">
                    Hasil dari pendirian cabang di Muntilan berdasarkan rekomendasi cukup memuaskan.
                </div>
            </div>
            
            <div class="task-card">
                <div class="task-header">
                    <h2 class="task-title" data-task-title="Cabang Tambahan di Secang">Cabang Tambahan di Secang</h2>
                    <div class="task-actions">
                        <button class="action-btn edit-btn modal-trigger" href="#edit-task-modal" title="Edit">
                            <i class="material-icons">edit</i>
                        </button>
                        <button class="action-btn delete-btn" title="Hapus">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>
                
                <div class="task-info">
                    <div class="info-row">
                        <span class="info-label">Prioritas:</span>
                        <span class="info-value priority-low" data-task-priority="low">Rendah</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Deadline:</span>
                        <span class="info-value" data-task-deadline="25 Jun 2023">25 Juni 2023</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Penanggung Jawab:</span>
                        <span class="info-value" data-task-assignee="Doni">Doni</span>
                    </div>
                </div>
                
                <span class="status-badge status-progress" data-task-status="progress">Dalam Pengerjaan</span>
                
                <div class="task-description" data-task-description="MEndapatkan potongan untuk biaya bahan baku.">
                    MEndapatkan potongan untuk biaya bahan baku.
                </div>
            </div>
        </div>
    </main>

    <div id="add-task-modal" class="modal">
        <div class="modal-content">
            <h4>Tambah Proyek Baru</h4> <form>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="task_name" type="text" class="validate" required>
                        <label for="task_name">Nama Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="task_description" class="materialize-textarea"></textarea>
                        <label for="task_description">Deskripsi Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="task_priority" required>
                            <option value="" disabled selected>Pilih prioritas</option>
                            <option value="high">Tinggi</option>
                            <option value="medium">Sedang</option>
                            <option value="low">Rendah</option>
                        </select>
                        <label>Prioritas</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="task_status" required>
                            <option value="todo" selected>Belum Dimulai</option>
                            <option value="progress">Dalam Pengerjaan</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="task_deadline" type="text" class="datepicker">
                        <label for="task_deadline">Deadline</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="task_assignee" type="text">
                        <label for="task_assignee">Penanggung Jawab</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan</a>
        </div>
    </div>

    <div id="edit-task-modal" class="modal">
        <div class="modal-content">
            <h4>Edit Proyek</h4> <form>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_task_name" type="text" class="validate" required>
                        <label for="edit_task_name">Nama Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit_task_description" class="materialize-textarea"></textarea>
                        <label for="edit_task_description">Deskripsi Proyek</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="edit_task_priority" required>
                            <option value="" disabled selected>Pilih prioritas</option>
                            <option value="high">Tinggi</option>
                            <option value="medium">Sedang</option>
                            <option value="low">Rendah</option>
                        </select>
                        <label>Prioritas</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="edit_task_status" required>
                            <option value="todo" selected>Belum Dimulai</option>
                            <option value="progress">Dalam Pengerjaan</option>
                            <option value="completed">Selesai</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_task_deadline" type="text" class="datepicker">
                        <label for="edit_task_deadline">Deadline</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_task_assignee" type="text">
                        <label for="edit_task_assignee">Penanggung Jawab</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan Perubahan</a> </div>
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
            $('#add-task-modal').modal(); // Inisialisasi modal Tambah Proyek
            $('#edit-task-modal').modal(); // Inisialisasi modal Edit Proyek yang baru
            
            // Initialize select (penting untuk modal)
            $('select').formSelect();
            
            // Initialize datepicker (penting untuk modal)
            $('.datepicker').datepicker({
                format: 'dd mmm yyyy', // Format tanggal
                autoClose: true
            });
            
            // Delete button functionality
            $('.delete-btn').click(function() {
                const taskCard = $(this).closest('.task-card');
                const taskTitle = taskCard.find('.task-title').text();
                
                if (confirm(`Apakah Anda yakin ingin menghapus tugas "${taskTitle}"?`)) {
                    taskCard.remove();
                    M.toast({html: `Tugas "${taskTitle}" telah dihapus`});
                }
            });
            
            // Perubahan: Edit button functionality
            $('.edit-btn').click(function() {
                const taskCard = $(this).closest('.task-card');
                const taskTitle = taskCard.find('.task-title').attr('data-task-title'); // Ambil dari data attribute
                const taskDescription = taskCard.find('.task-description').attr('data-task-description');
                const taskPriority = taskCard.find('.priority-high, .priority-medium, .priority-low').attr('data-task-priority');
                const taskDeadline = taskCard.find('.info-row:contains("Deadline:") .info-value').attr('data-task-deadline'); // Ambil dari data attribute
                const taskAssignee = taskCard.find('.info-row:contains("Penanggung Jawab:") .info-value').attr('data-task-assignee');
                const taskStatus = taskCard.find('.status-badge').attr('data-task-status');

                // Isi formulir di modal edit
                $('#edit-task-modal #edit_task_name').val(taskTitle);
                $('#edit-task-modal #edit_task_description').val(taskDescription);
                M.textareaAutoResize($('#edit-task-modal #edit_task_description')); // Penting untuk textarea materialize

                // Set nilai select dan update display Materialize
                $('#edit-task-modal #edit_task_priority').val(taskPriority);
                $('#edit-task-modal #edit_task_status').val(taskStatus);
                $('select').formSelect(); // Re-initialize selects after setting value

                // Set deadline dan update datepicker
                const deadlineDate = new Date(taskDeadline); // Convert string to Date object
                $('#edit-task-modal #edit_task_deadline').datepicker('setDate', deadlineDate); // Set datepicker value
                $('#edit-task-modal #edit_task_deadline').val(taskDeadline); // Set input value

                $('#edit-task-modal #edit_task_assignee').val(taskAssignee);
                
                // Panggil label.active untuk memastikan label naik
                M.updateTextFields(); // Memastikan label input Materialize aktif jika input terisi

                // Buka modal edit
                $('#edit-task-modal').modal('open');
            });
            // Akhir Perubahan: Edit button functionality
        });
    </script>
</body>
</html>