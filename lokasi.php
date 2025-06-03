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
            font-size: 1.25rem; /* Diubah dari 1.3rem menjadi 1.25rem */
            line-height: 1.2;   /* Ditambahkan untuk mengontrol tinggi baris */
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
        .table-responsive { /* Ini adalah div wrapper baru untuk tabel */
            overflow-x: auto;
            border-radius: 0 0 8px 8px; /* Sudut bawah yang melengkung */
            border: 1px solid rgba(0,0,0,0.1);
            border-top: none; /* Menghapus border atas agar menyatu dengan card-header */
            overflow: hidden;
            margin-bottom: 24px; /* Memberi jarak ke elemen di bawahnya (pagination) */
        }

        /* Table Styles (hanya untuk <table> itu sendiri) */
        .responsive-table {
            width: 100%;
            border-collapse: collapse; /* Pastikan sel tabel tidak memiliki spasi */
            /* Properti seperti border-radius, overflow, border dipindahkan ke .table-responsive */
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
            border-bottom: 1px solid #eee; /* Border bawah untuk setiap sel */
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
            padding: 0 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        
        .action-btn:hover {
            background-color: rgba(0,0,0,0.05);
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
            padding: 16px 24px;
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
        <li><a href="dashboard.php"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a class="active" href="lokasi.php"><i class="material-icons">location_on</i>Lokasi Cabang</a></li>
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
                    <h1 class="page-title">Lokasi Cabang</h1>
                </div>
                <div class="col s6 right-align">
                    <span class="btn-flat dropdown-trigger" data-target="profile-dropdown">
                        <i class="material-icons left">account_circle</i>
                        Username
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
                        
                        <div class="table-responsive"> <table class="responsive-table highlight">
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
                                    <tr>
                                        <td>1</td>
                                        <td>Senyan Baru</td>
                                        <td>Jl. Asia Afrika No.8</td>
                                        <td>Jakarta</td>
                                        <td><span class="badge active">Aktif</span></td>
                                        <td>350</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn red-text">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Grand Hotel</td>
                                        <td>Jl. M.H. Thamrin No.1</td>
                                        <td>Jakarta</td>
                                        <td><span class="badge active">Aktif</span></td>
                                        <td>420</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn red-text">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Tunjungan Plaza</td>
                                        <td>Jl. Basuki Rahmat No.8-12</td>
                                        <td>Surabaya</td>
                                        <td><span class="badge active">Aktif</span></td>
                                        <td>380</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn red-text">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Bandung Timur</td>
                                        <td>Jl. Sukajadi No.131-139</td>
                                        <td>Bandung</td>
                                        <td><span class="badge active">Aktif</span></td>
                                        <td>310</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn red-text">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Mall Bali Galeria</td>
                                        <td>Jl. Bypass Ngurah Rai No.35</td>
                                        <td>Denpasar</td>
                                        <td><span class="badge inactive">Renovasi</span></td>
                                        <td>290</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn red-text">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </td>
                                    </tr>
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
                        <iframe src="https://maps.google.com/maps?q=jakarta&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col s12 m6">
                    <h5>Tunjungan Plaza</h5>
                    <p><i class="material-icons left">location_on</i>Jl. Asia Afrika No.8, Jakarta</p>
                    <p><i class="material-icons left">phone</i>(021) 12345678</p>
                    <p><i class="material-icons left">email</i>senayan@branchwise.com</p>
                    <p><i class="material-icons left">access_time</i>10:00 - 22:00</p>
                    <p><i class="material-icons left">straighten</i>350 m²</p>
                    <p><i class="material-icons left">people</i>15 Karyawan</p>
                    <p><i class="material-icons left">date_range</i>Berdiri sejak: 15 Jan 2018</p>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <h5>Informasi Tambahan</h5>
                    <p>Cabang utama yang terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi. Memiliki fasilitas lengkap termasuk ruang fitting dan lounge VIP.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Tutup</a>
        </div>
    </div>

    <div id="add-branch-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Tambah Cabang Baru</h4>
            <form>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="branch_name" type="text" class="validate" required>
                        <label for="branch_name">Nama Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="branch_address" type="text" class="validate" required>
                        <label for="branch_address">Alamat</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="branch_city" type="text" class="validate" required>
                        <label for="branch_city">Kota</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="branch_phone" type="tel" class="validate">
                        <label for="branch_phone">Telepon</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="branch_email" type="email" class="validate">
                        <label for="branch_email">Email</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input id="branch_size" type="number" class="validate">
                        <label for="branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input id="branch_employees" type="number" class="validate">
                        <label for="branch_employees">Jumlah Karyawan</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input id="branch_opening" type="text" class="datepicker">
                        <label for="branch_opening">Tanggal Berdiri</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="branch_status" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="renovation">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="branch_hours" type="text" class="validate">
                        <label for="branch_hours">Jam Operasional</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="branch_notes" class="materialize-textarea"></textarea>
                        <label for="branch_notes">Catatan Tambahan</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan Cabang</a>
        </div>
    </div>

    <div id="edit-branch-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Edit Data Cabang</h4>
            <form>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_branch_name" type="text" class="validate" value="BranchWise Plaza Senayan" required>
                        <label for="edit_branch_name">Nama Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_address" type="text" class="validate" value="Jl. Asia Afrika No.8" required>
                        <label for="edit_branch_address">Alamat</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_city" type="text" class="validate" value="Jakarta" required>
                        <label for="edit_branch_city">Kota</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_phone" type="tel" class="validate" value="(021) 12345678">
                        <label for="edit_branch_phone">Telepon</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_email" type="email" class="validate" value="senayan@branchwise.com">
                        <label for="edit_branch_email">Email</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input id="edit_branch_size" type="number" class="validate" value="350">
                        <label for="edit_branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input id="edit_branch_employees" type="number" class="validate" value="15">
                        <label for="edit_branch_employees">Jumlah Karyawan</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input id="edit_branch_opening" type="text" class="datepicker" value="15 Jan 2018">
                        <label for="edit_branch_opening">Tanggal Berdiri</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="edit_branch_status" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="renovation">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_branch_hours" type="text" class="validate" value="10:00 - 22:00">
                        <label for="edit_branch_hours">Jam Operasional</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit_branch_notes" class="materialize-textarea">Cabang utama yang terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi. Memiliki fasilitas lengkap termasuk ruang fitting dan lounge VIP.</textarea>
                        <label for="edit_branch_notes">Catatan Tambahan</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan Perubahan</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
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
            
            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'dd mmm yyyy',
                yearRange: [2000, new Date().getFullYear()],
                autoClose: true
            });
        });
    </script>
</body>
</html>