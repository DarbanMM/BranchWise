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
                    <span style="color: #444; font-weight: 500; display: inline-flex; align-items: center;">
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
                                    <tr>
                                        <td>1</td>
                                        <td>Senyan Baru</td>
                                        <td>Jl. Asia Afrika No.8</td>
                                        <td>Jakarta</td>
                                        <td><span class="badge active">Aktif</span></td>
                                        <td>350</td>
                                        <td>
                                            <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn delete-btn">
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
                                            <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn delete-btn">
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
                                            <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn delete-btn">
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
                                            <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn delete-btn">
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
                                            <a href="#view-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="#edit-branch-modal" class="btn-flat action-btn modal-trigger" data-gmaps-link="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="#!" class="btn-flat action-btn delete-btn">
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
                        <iframe id="view_branch_map_iframe" src="https://maps.google.com/maps?q=jakarta&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col s12 m6"> 
                    <h5 id="view_branch_name">Tunjungan Plaza</h5> <p><i class="material-icons left">location_on</i><span id="view_branch_address">Jl. Asia Afrika No.8, Jakarta</span></p> <p><i class="material-icons left">phone</i><span id="view_branch_phone">(021) 12345678</span></p> <p><i class="material-icons left">email</i><span id="view_branch_email">senayan@branchwise.com</span></p> <p><i class="material-icons left">straighten</i><span id="view_branch_size">350</span> m²</p> </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <h5>Informasi Tambahan</h5>
                    <p id="view_branch_notes">Terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi.</p> </div>
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
                    <div class="input-field col s12 m6"> 
                        <input id="branch_size" type="number" class="validate">
                        <label for="branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m6"> 
                        <select id="branch_status" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="renovation">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="branch_gmaps_link" type="url" class="validate">
                        <label for="branch_gmaps_link">Link Google Maps</label>
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
                    <div class="input-field col s12 m6"> 
                        <input id="edit_branch_size" type="number" class="validate" value="350">
                        <label for="edit_branch_size">Luas (m²)</label>
                    </div>
                    <div class="input-field col s12 m6"> 
                        <select id="edit_branch_status" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Nonaktif</option>
                            <option value="renovation">Renovasi</option>
                        </select>
                        <label>Status Cabang</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12">
                        <input id="edit_branch_gmaps_link" type="url" class="validate" value="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576887556755!2d106.8202476147696!3d-6.17511099552431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d30d1d7b1d%3A0x7d2b1f8c0a2a5b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1678912345678!5m2!1sen!2sid"> <label for="edit_branch_gmaps_link">Link Google Maps</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="edit_branch_notes" class="materialize-textarea">Terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi.</textarea>
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

            // Perubahan: Implementasi fungsi untuk tombol edit tabel
            $('.action-btn.modal-trigger[href="#edit-branch-modal"]').click(function() {
                const branchRow = $(this).closest('tr');
                
                // Ambil data dari baris tabel (sesuaikan index td jika diperlukan)
                const branchName = branchRow.find('td:nth-child(2)').text(); // Nama Alternatif
                const branchAddress = branchRow.find('td:nth-child(3)').text(); // Lokasi
                const branchCity = branchRow.find('td:nth-child(4)').text(); // Kota
                const branchStatus = branchRow.find('td:nth-child(5) .badge').text().toLowerCase().trim(); // Status
                const branchSize = branchRow.find('td:nth-child(6)').text().replace(' m²', '').trim(); // Luas (m²)
                // Perubahan: Ambil link gmaps dari data attribute tombol edit
                const branchGmapsLink = $(this).attr('data-gmaps-link'); 
                // Perubahan: Ambil informasi tambahan dari view modal atau hardcode untuk contoh
                const branchNotes = "Terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi."; 
                // Anda perlu menambahkan kolom tersembunyi di tabel atau data attribute jika ini data dinamis

                // Isi formulir di modal edit
                $('#edit_branch_name').val(branchName);
                $('#edit_branch_address').val(branchAddress);
                $('#edit_branch_city').val(branchCity);
                $('#edit_branch_size').val(branchSize);
                $('#edit_branch_status').val(branchStatus);
                $('#edit_branch_notes').val(branchNotes); // Mengisi catatan tambahan
                $('#edit_branch_gmaps_link').val(branchGmapsLink); // Mengisi link gmaps

                // Materialize specific updates untuk label dan select
                M.updateTextFields(); // Memastikan label input Materialize naik (float)
                $('select').formSelect(); // Re-initialize selects untuk menampilkan nilai yang benar

                // Optional: Untuk modal view, jika Anda ingin memuat peta berdasarkan link gmaps saat view dibuka
                // $('#view-branch-modal').modal({
                //     onOpenEnd: function(modal, trigger) {
                //         const viewGmapsLink = $(trigger).attr('data-gmaps-link');
                //         if(viewGmapsLink) {
                //             $('#view_branch_map_iframe').attr('src', viewGmapsLink);
                //         }
                //         // Mengisi data lain di modal view
                //         $('#view_branch_name').text(branchName);
                //         $('#view_branch_address').text(branchAddress + ', ' + branchCity); // Sesuaikan format
                //         $('#view_branch_size').text(branchSize);
                //         $('#view_branch_notes').text(branchNotes);
                //         // Tambahkan pengisian data lain seperti telepon, email di sini
                //     }
                // });
            });

            // Perubahan: Implementasi fungsi untuk tombol view tabel
            $('.action-btn.modal-trigger[href="#view-branch-modal"]').click(function() {
                const branchRow = $(this).closest('tr');
                const branchName = branchRow.find('td:nth-child(2)').text(); // Nama Alternatif
                const branchAddress = branchRow.find('td:nth-child(3)').text(); // Lokasi
                const branchCity = branchRow.find('td:nth-child(4)').text(); // Kota
                const branchSize = branchRow.find('td:nth-child(6)').text().replace(' m²', '').trim(); // Luas (m²)
                const branchGmapsLink = $(this).attr('data-gmaps-link'); // Ambil dari data attribute tombol view

                // Mengisi data ke modal view
                $('#view_branch_name').text(branchName);
                $('#view_branch_address').text(branchAddress + ', ' + branchCity); 
                $('#view_branch_size').text(branchSize);
                // Karena data telepon/email tidak ada di tabel, bisa hardcode atau dari sumber lain
                $('#view_branch_phone').text('(021) 12345678'); 
                $('#view_branch_email').text('contact@branchwise.com');
                $('#view_branch_notes').text('Cabang utama yang terletak di pusat perbelanjaan elit dengan traffic pengunjung tinggi. Memiliki fasilitas lengkap termasuk ruang fitting dan lounge VIP.'); 
                
                // Set src iframe untuk peta
                if(branchGmapsLink) {
                    $('#view_branch_map_iframe').attr('src', branchGmapsLink); // Mengisi iframe peta
                }
            });
            // Akhir Perubahan: Implementasi fungsi untuk tombol view dan edit tabel
        });
    </script>
</body>
</html>