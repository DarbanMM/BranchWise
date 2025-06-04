<?php
session_start(); // Perubahan: [1] Memulai sesi PHP

// Perubahan: [2] Sertakan file koneksi database
include 'db_connect.php';

// Perubahan: [3] Autentikasi dan Otorisasi
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect jika belum login
    exit();
}
if ($_SESSION['role'] === 'admin') {
    header("Location: admin.php"); // Redirect admin ke halaman admin
    exit();
}

// Perubahan: [4] Pastikan project_id diterima dari URL (setelah user memilih proyek di dashboard)
$project_id = $_GET['project_id'] ?? null;

if (!$project_id || !is_numeric($project_id)) {
    // Jika project_id tidak ada atau tidak valid, redirect kembali ke dashboard
    header("Location: dashboard.php");
    exit();
}

// Perubahan: [5] Periksa apakah project_id ini milik user yang sedang login
// Atau jika admin, mereka punya akses ke semua project
$current_user_id = $_SESSION['user_id'];
$project_name_display = "Proyek Tidak Ditemukan"; // Default

$stmt_check_project = $conn->prepare("SELECT project_name, user_id FROM projects WHERE id = ?");
$stmt_check_project->bind_param("i", $project_id);
$stmt_check_project->execute();
$result_check_project = $stmt_check_project->get_result();

if ($result_check_project->num_rows == 0) {
    header("Location: dashboard.php"); // Proyek tidak ditemukan
    exit();
}

$project_info = $result_check_project->fetch_assoc();
$project_owner_id = $project_info['user_id'];
$project_name_display = htmlspecialchars($project_info['project_name']);
$stmt_check_project->close();

if ($_SESSION['role'] !== 'admin' && $project_owner_id !== $current_user_id) {
    header("Location: dashboard.php"); // Redirect jika bukan pemilik dan bukan admin
    exit();
}


$current_username = $_SESSION['username'];

// Perubahan: [6] Ambil data lokasi untuk project_id ini
$locations_data = [];
$stmt_locations = $conn->prepare("SELECT id, branch_name, address, city, phone, email, status, size_sqm, gmaps_link, notes FROM locations WHERE project_id = ? ORDER BY id ASC");
$stmt_locations->bind_param("i", $project_id);
$stmt_locations->execute();
$result_locations = $stmt_locations->get_result();

if ($result_locations->num_rows > 0) {
    while($row = $result_locations->fetch_assoc()) {
        $locations_data[] = $row;
    }
}
$stmt_locations->close();

$conn->close(); // Tutup koneksi setelah ambil data
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Cabang - BranchWise</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
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
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }
        
        .card .card-content {
            padding: 24px;
        }
        
        .card .card-title { 
            font-weight: 600;
            font-size: 1.25rem; 
            line-height: 1.2;   
            margin: 0; 
            display: block;
        }

        .card-header { 
            padding: 20px 24px; 
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Table Wrapper Styles */
        .table-responsive { 
            overflow-x: auto;
            border-radius: 0 0 8px 8px; 
            border: 1px solid rgba(0,0,0,0.1);
            border-top: none; 
            overflow: hidden;
            margin-bottom: 24px; 
        }

        /* Table Styles (hanya untuk <table> itu sendiri) */
        .responsive-table {
            width: 100%;
            border-collapse: collapse; 
        }
        
        .responsive-table th {
            font-weight: 600;
            background-color: var(--light-gray);
            font-size: 0.9rem;
            padding: 14px 16px;
            text-align: left;
        }
        
        .responsive-table td, .responsive-table th {
            padding: 14px 16px;
            font-size: 0.9rem;
        }

        .responsive-table td {
            border-bottom: 1px solid #eee; 
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            text-transform: none;
            border-radius: 4px;
            height: 42px;
            line-height: 42px;
            padding: 0 20px;
        }
        
        .btn i.material-icons {
            height: inherit;
            line-height: inherit;
        }
        
        .btn-flat {
            font-weight: 500;
            padding: 0 12px;
        }
        
        /* Form Styles */
        .input-field {
            margin-bottom: 24px;
        }
        
        .input-field label {
            font-size: 0.9rem;
        }
        
        /* Badge Styles */
        .badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
        }
        
        .badge.active {
            background-color: var(--secondary-color);
        }
        
        .badge.inactive {
            background-color: #f44336;
        }
        
        /* Map Styles */
        .map-container {
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }
        
        /* Action Buttons */
        .action-btn {
            background: none; 
            border: none; 
            cursor: pointer;
            color: #7f8c8d; 
            font-size: 16px; 
            transition: color 0.2s, background-color 0.2s; 
            padding: 5px; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
        }
        
        .action-btn:hover {
            background-color: rgba(0,0,0,0.05); 
            color: var(--primary-color); 
        }
        
        .delete-btn:hover { 
            color: #e74c3c; 
        }

        /* Modal Styles */
        .modal {
            border-radius: 8px;
            overflow: hidden;
            max-width: 600px;
            width: 90%;
        }
        
        .modal .modal-content {
            padding: 24px;
        }
        
        .modal .modal-footer {
            padding: 2px 24px; /* Perubahan: Padding footer modal disamakan */
            background-color: var(--light-gray);
        }
        
        /* Responsive Adjustments */
        @media only screen and (max-width: 992px) {
            main {
                margin-left: 0;
            }
            
            .sidenav {
                transform: translateX(-105%);
            }
            
            .content-wrapper {
                padding: 16px;
            }
            
            .card .card-content {
                padding: 16px;
            }
        }

        .sets{
           margin: 13px 10px 0px 0px; 
        }

        /* Pagination */
        .pagination li a {
            color: var(--primary-color); /* Warna teks link paginasi */
        }
        
        .pagination li.active {
            background-color: #1565c0; /* Perubahan: Mengatur warna latar belakang menjadi biru gelap (sesuai primary-color) */
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
        <li><a class="active" href="lokasi.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
        <li><a href="kriteria.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">assessment</i>Kriteria & Bobot</a></li>
        <li><a href="matriks.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">grid_on</i>Matriks</a></li>
        <li><a href="hasil_perhitungan.php?project_id=<?php echo htmlspecialchars($project_id); ?>"><i class="material-icons">calculate</i>Hasil Perhitungan</a></li>
        <li><div class="divider"></div></li>
        <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>

    <main>
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Lokasi Cabang: <?php echo $project_name_display; ?></h1>
                </div>
                <div class="col s6 right-align">
                    <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
                        <i class="material-icons left">account_circle</i>
                        <?php echo htmlspecialchars($current_username); ?>
                    </span>
                </div>
                </div>
        </div>

        <div class="content-wrapper">
            <div class="row">
                <div class="col s12">
                    <div class="card white">
                        <div class="card-header"> 
                            <div class="row" style="margin-bottom: 0; width: 100%;">
                                <div class="col s12 m6">
                                    <h2 class="card-title">Daftar Cabang Retail</h2> 
                                </div>
                                <div class="col s12 m6 right-align">
                                    <a href="#add-branch-modal" class="btn waves-effect waves-light blue darken-2 modal-trigger">
                                        <i class="material-icons left">add_location</i>Tambah Cabang
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive"> 
                            <table class="responsive-table highlight">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Alternatif</th>
                                        <th>Lokasi</th>
                                        <th>Kota</th>
                                        <th>Status</th>
                                        <th>Luas (m²)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($locations_data)): ?>
                                        <?php $no = 1; foreach ($locations_data as $location): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($location['branch_name']); ?></td>
                                            <td><?php echo htmlspecialchars($location['address']); ?></td>
                                            <td><?php echo htmlspecialchars($location['city']); ?></td>
                                            <td><span class="badge <?php echo ($location['status'] == 'aktif') ? 'active' : 'inactive'; ?>"><?php echo htmlspecialchars(ucfirst($location['status'])); ?></span></td>
                                            <td><?php echo htmlspecialchars($location['size_sqm']); ?></td>
                                            <td>
                                                <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" 
                                                   data-location-id="<?php echo $location['id']; ?>"
                                                   data-branch-name="<?php echo htmlspecialchars($location['branch_name']); ?>"
                                                   data-address="<?php echo htmlspecialchars($location['address']); ?>"
                                                   data-city="<?php echo htmlspecialchars($location['city']); ?>"
                                                   data-phone="<?php echo htmlspecialchars($location['phone']); ?>"
                                                   data-email="<?php echo htmlspecialchars($location['email']); ?>"
                                                   data-size="<?php echo htmlspecialchars($location['size_sqm']); ?>"
                                                   data-status="<?php echo htmlspecialchars($location['status']); ?>"
                                                   data-gmaps-link="<?php echo htmlspecialchars($location['gmaps_link']); ?>"
                                                   data-notes="<?php echo htmlspecialchars($location['notes']); ?>">
                                                    <i class="material-icons">visibility</i>
                                                </a>
                                                <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" 
                                                   data-location-id="<?php echo $location['id']; ?>"
                                                   data-branch-name="<?php echo htmlspecialchars($location['branch_name']); ?>"
                                                   data-address="<?php echo htmlspecialchars($location['address']); ?>"
                                                   data-city="<?php echo htmlspecialchars($location['city']); ?>"
                                                   data-phone="<?php echo htmlspecialchars($location['phone']); ?>"
                                                   data-email="<?php echo htmlspecialchars($location['email']); ?>"
                                                   data-size="<?php echo htmlspecialchars($location['size_sqm']); ?>"
                                                   data-status="<?php echo htmlspecialchars($location['status']); ?>"
                                                   data-gmaps-link="<?php echo htmlspecialchars($location['gmaps_link']); ?>"
                                                   data-notes="<?php echo htmlspecialchars($location['notes']); ?>">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <a href="#!" class="btn-flat action-btn delete-btn"
                                                   data-location-id="<?php echo $location['id']; ?>"
                                                   data-branch-name="<?php echo htmlspecialchars($location['branch_name']); ?>">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="center-align">Belum ada lokasi cabang ditambahkan untuk proyek ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row" style="padding: 24px; margin-bottom: 0;"> 
                            <div class="col s12 center-align">
                                <ul class="pagination">
                                    <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
                                    <li class="active"><a href="#!">1</a></li>
                                    <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="view-branch-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Detail Cabang</h4>
            <div class="row">
                <div class="col s12 m6">
                    <div class="map-container">
                        <iframe id="view_branch_map_iframe" src="" allowfullscreen></iframe> </div>
                </div>
                <div class="col s12 m6"> 
                    <h5 id="view_branch_name"></h5> <p><i class="material-icons left">location_on</i><span id="view_branch_address_city"></span></p> <p><i class="material-icons left">phone</i><span id="view_branch_phone"></span></p> <p><i class="material-icons left">email</i><span id="view_branch_email"></span></p> <p><i class="material-icons left">straighten</i><span id="view_branch_size"></span> m²</p> </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <h5>Informasi Tambahan</h5>
                    <p id="view_branch_notes"></p> </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Tutup</a>
        </div>
    </div>

    <div id="add-branch-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Tambah Cabang Baru</h4>
            <form id="add-branch-form"> <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>"> <div class="row">
                    <div class="input-field col s12">
                        <input id="branch_name" name="branch_name" type="text" class="validate" required>
                        <label for="branch_name">Nama Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="branch_address" name="address" type="text" class="validate" required>
                        <label for="branch_address">Alamat</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="branch_city" name="city" type="text" class="validate" required>
                        <label for="branch_city">Kota</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="branch_phone" name="phone" type="tel" class="validate">
                        <label for="branch_phone">Telepon</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="branch_email" name="email" type="email" class="validate">
                        <label for="branch_email">Email</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6"> 
                        <input id="branch_size" name="size_sqm" type="number" class="validate">
                        <label for="branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m6"> 
                        <select id="branch_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="renovasi">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="branch_gmaps_link" name="gmaps_link" type="url" class="validate">
                        <label for="branch_gmaps_link">Link Google Maps</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="branch_notes" name="notes" class="materialize-textarea"></textarea>
                        <label for="branch_notes">Informasi Tambahan</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button class="waves-effect waves-green btn blue" id="save-add-branch-btn">Simpan Cabang</button>
        </div>
    </div>

    <div id="edit-branch-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Edit Data Cabang</h4>
            <form id="edit-branch-form"> <input type="hidden" id="edit_location_id" name="location_id"> <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>"> <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_branch_name" name="branch_name" type="text" class="validate" required>
                        <label for="edit_branch_name">Nama Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_address" name="address" type="text" class="validate" required>
                        <label for="edit_branch_address">Alamat</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_city" name="city" type="text" class="validate" required>
                        <label for="edit_branch_city">Kota</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_phone" name="phone" type="tel" class="validate">
                        <label for="edit_branch_phone">Telepon</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_email" name="email" type="email" class="validate">
                        <label for="edit_branch_email">Email</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6"> 
                        <input id="edit_branch_size" name="size_sqm" type="number" class="validate">
                        <label for="edit_branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m6"> 
                        <select id="edit_branch_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="renovasi">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_branch_gmaps_link" name="gmaps_link" type="url" class="validate"> 
                        <label for="edit_branch_gmaps_link">Link Google Maps</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit_branch_notes" name="notes" class="materialize-textarea"></textarea>
                        <label for="edit_branch_notes">Informasi Tambahan</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button class="waves-effect waves-green btn blue" id="save-edit-branch-btn">Simpan Perubahan</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Initialize modal
            $('.modal').modal();
            
            // Initialize select
            $('select').formSelect();
            
            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'dd mmmYYYY',
                yearRange: [2000, new Date().getFullYear()],
                autoClose: true
            });

            // Perubahan: Implementasi fungsi untuk tombol view tabel
            $('.action-btn.modal-trigger[href="#view-branch-modal"]').click(function() {
                const locationId = $(this).data('location-id');
                const branchName = $(this).data('branch-name');
                const address = $(this).data('address');
                const city = $(this).data('city');
                const phone = $(this).data('phone');
                const email = $(this).data('email');
                const size = $(this).data('size');
                const status = $(this).data('status');
                const gmapsLink = $(this).data('gmaps-link');
                const notes = $(this).data('notes');

                // Isi data ke modal view
                $('#view_branch_name').text(branchName);
                $('#view_branch_address_city').text(address + ', ' + city); 
                $('#view_branch_phone').text(phone); 
                $('#view_branch_email').text(email);
                $('#view_branch_size').text(size);
                $('#view_branch_notes').text(notes); 
                
                // Set src iframe untuk peta
                if(gmapsLink) {
                    $('#view_branch_map_iframe').attr('src', gmapsLink);
                } else {
                    $('#view_branch_map_iframe').attr('src', 'about:blank'); // Kosongkan jika tidak ada link
                }
            });

            // Perubahan: Implementasi fungsi untuk tombol edit tabel (mengisi modal edit)
            $('.action-btn.modal-trigger[href="#edit-branch-modal"]').click(function() {
                const locationId = $(this).data('location-id');
                const branchName = $(this).data('branch-name');
                const address = $(this).data('address');
                const city = $(this).data('city');
                const phone = $(this).data('phone');
                const email = $(this).data('email');
                const size = $(this).data('size');
                const status = $(this).data('status');
                const gmapsLink = $(this).data('gmaps-link');
                const notes = $(this).data('notes');

                // Isi formulir di modal edit
                $('#edit_location_id').val(locationId); // Set hidden input ID
                $('#edit_branch_name').val(branchName);
                $('#edit_branch_address').val(address);
                $('#edit_branch_city').val(city);
                $('#edit_branch_phone').val(phone);
                $('#edit_branch_email').val(email);
                $('#edit_branch_size').val(size);
                $('#edit_branch_status').val(status);
                $('#edit_branch_gmaps_link').val(gmapsLink); 
                $('#edit_branch_notes').val(notes);

                // Materialize specific updates untuk label dan select
                M.updateTextFields(); // Memastikan label input Materialize naik (float)
                $('select').formSelect(); // Re-initialize selects untuk menampilkan nilai yang benar
            });

            // Perubahan: Implementasi fungsi untuk tombol delete tabel (dengan konfirmasi dan AJAX)
            $('.action-btn.delete-btn').click(function(e) {
                e.preventDefault(); 
                const locationId = $(this).data('location-id');
                const branchName = $(this).data('branch-name');
                
                if (confirm(`Apakah Anda yakin ingin menghapus lokasi "${branchName}"?`)) {
                    $.ajax({
                        url: 'handle_lokasi_actions.php', // File PHP untuk menangani hapus
                        type: 'POST',
                        data: {
                            action: 'delete_location',
                            location_id: locationId,
                            project_id: <?php echo json_encode($project_id); ?> // Kirim project_id juga untuk keamanan
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: `Lokasi "${branchName}" berhasil dihapus!`});
                                location.reload(); 
                            } else {
                                M.toast({html: `Gagal menghapus lokasi: ${response.message}`});
                            }
                        },
                        error: function(xhr, status, error) {
                            M.toast({html: 'Terjadi kesalahan saat menghapus lokasi.'});
                            console.error("AJAX Error:", status, error);
                            console.log("Response Text:", xhr.responseText);
                        }
                    });
                }
            });

            // Perubahan: Submit form Tambah Cabang melalui AJAX
            $('#save-add-branch-btn').click(function(e) {
                e.preventDefault(); 
                
                const form = $('#add-branch-form');
                if (form[0].checkValidity()) { 
                    const formData = {
                        action: 'add_location',
                        project_id: <?php echo json_encode($project_id); ?>, // Kirim project_id dari PHP
                        branch_name: $('#branch_name').val(),
                        address: $('#branch_address').val(),
                        city: $('#branch_city').val(),
                        phone: $('#branch_phone').val(),
                        email: $('#branch_email').val(),
                        size_sqm: $('#branch_size').val(),
                        status: $('#branch_status').val(),
                        gmaps_link: $('#branch_gmaps_link').val(),
                        notes: $('#branch_notes').val()
                    };

                    $.ajax({
                        url: 'handle_lokasi_actions.php', // File PHP untuk menangani aksi lokasi
                        type: 'POST',
                        data: formData,
                        dataType: 'json', 
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Cabang berhasil ditambahkan!'});
                                $('#add-branch-modal').modal('close'); 
                                location.reload(); 
                            } else {
                                M.toast({html: `Gagal menambahkan cabang: ${response.message}`});
                            }
                        },
                        error: function(xhr, status, error) {
                            M.toast({html: 'Terjadi kesalahan saat menambahkan cabang.'});
                            console.error("AJAX Error:", status, error);
                            console.log("Response Text:", xhr.responseText);
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'});
                }
            });

            // Perubahan: Submit form Edit Cabang melalui AJAX
            $('#save-edit-branch-btn').click(function(e) {
                e.preventDefault(); 
                
                const form = $('#edit-branch-form');
                if (form[0].checkValidity()) {
                    const formData = {
                        action: 'edit_location',
                        location_id: $('#edit_location_id').val(),
                        project_id: <?php echo json_encode($project_id); ?>, // Kirim project_id dari PHP
                        branch_name: $('#edit_branch_name').val(),
                        address: $('#edit_branch_address').val(),
                        city: $('#edit_branch_city').val(),
                        phone: $('#edit_branch_phone').val(),
                        email: $('#edit_branch_email').val(),
                        size_sqm: $('#edit_branch_size').val(),
                        status: $('#edit_branch_status').val(),
                        gmaps_link: $('#edit_branch_gmaps_link').val(),
                        notes: $('#edit_branch_notes').val()
                    };
                    
                    $.ajax({
                        url: 'handle_lokasi_actions.php', // File PHP untuk menangani aksi lokasi
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Cabang berhasil diperbarui!'});
                                $('#edit-branch-modal').modal('close');
                                location.reload(); 
                            } else {
                                M.toast({html: `Gagal memperbarui cabang: ${response.message}`});
                            }
                        },
                        error: function(xhr, status, error) {
                            M.toast({html: 'Terjadi kesalahan saat memperbarui cabang.'});
                            console.error("AJAX Error:", status, error);
                            console.log("Response Text:", xhr.responseText);
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'});
                }
            });
        });
    </script>
</body>
</html>