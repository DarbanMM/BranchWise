<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BranchWise</title>
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    
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
            margin-left: 250px; /* Match sidebar width */
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
            font-weight: 500;
            font-size: 1.3rem;
            margin-bottom: 24px;
            display: block;
        }
        
        /* Table Styles */
        .responsive-table {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1);
        }
        
        .responsive-table th {
            font-weight: 600;
            background-color: var(--light-gray);
            font-size: 0.9rem;
        }
        
        .responsive-table td, .responsive-table th {
            padding: 14px 16px;
            font-size: 0.9rem;
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
        
        /* Pagination */
        .pagination li a {
            color: var(--primary-color);
        }
        
        .pagination li.active {
            background-color: var(--primary-color);
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
    <!-- Side Navigation -->
    <ul id="slide-out" class="sidenav sidenav-fixed">
        <li>
            <div class="user-view">
                <div class="logo">
                    <span class="blue-text text-darken-2" style="font-size: 1.5rem; font-weight: 600;">BranchWise</span>
                </div>
            </div>
        </li>
        <li><a class="active" href="accounts.html"><i class="material-icons">people</i>Manajemen Akun</a></li>
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
                    <h1 class="page-title">Manajemen Akun</h1>
                </div>
                <div class="col s6 right-align">
                    <span class="btn-flat dropdown-trigger" data-target="profile-dropdown">
                        <i class="material-icons left">account_circle</i>
                        Admin
                        </span>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- List Akun Section (Default View) -->
            <div id="account-list">
                <div class="row">
                    <div class="col s12">
                        <div class="card white">
                            <div class="card-content">
                                <div class="row" style="margin-bottom: 0;">
                                    <div class="col s12 m6">
                                        <span class="card-title">Daftar Akun</span>
                                    </div>
                                    <div class="col s12 m6 right-align">
                                        <a id="add-account-btn" class="btn waves-effect waves-light blue darken-2">
                                            <i class="material-icons left">add</i>Tambah Akun
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col s12">
                                        <table class="responsive-table highlight">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Username</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Admin Utama</td>
                                                    <td>AdminUtama</td>
                                                    <td>Admin</td>
                                                    <td><span class="badge active">Aktif</span></td>
                                                    <td>
                                                        <a href="#edit-account-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                        <a href="#!" class="btn-flat action-btn red-text">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Manager Cabang</td>
                                                    <td>ManagerCabang</td>
                                                    <td>User</td>
                                                    <td><span class="badge active">Aktif</span></td>
                                                    <td>
                                                        <a href="#edit-account-modal" class="btn-flat action-btn blue-text modal-trigger">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                        <a href="#!" class="btn-flat action-btn red-text">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Analis Data</td>
                                                    <td>AnalisisData</td>
                                                    <td>User</td>
                                                    <td><span class="badge inactive">Nonaktif</span></td>
                                                    <td>
                                                        <a href="#edit-account-modal" class="btn-flat action-btn blue-text modal-trigger">
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
                                </div>
                                
                                <div class="row">
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
            </div>

            <!-- Tambah Akun Section (Hidden by default) -->
            <div id="add-account" style="display: none;">
                <div class="row">
                    <div class="col s12">
                        <div class="card white">
                            <div class="card-content">
                                <div class="row" style="margin-bottom: 0;">
                                    <div class="col s12 m6">
                                        <span class="card-title">Tambah Akun Baru</span>
                                    </div>
                                    <div class="col s12 m6 right-align">
                                        <a id="back-to-list" class="btn-flat waves-effect">
                                            <i class="material-icons left">arrow_back</i>Kembali
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col s12">
                                        <form>
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <input id="full_name" type="text" class="validate" required>
                                                    <label for="full_name">Nama Lengkap</label>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <input id="username" type="text" class="validate" required>
                                                    <label for="username">username</label>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <input id="password" type="password" class="validate" required>
                                                    <label for="password">Password</label>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <input id="confirm_password" type="password" class="validate" required>
                                                    <label for="confirm_password">Konfirmasi Password</label>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="input-field col s12 m6">
                                                    <select id="role" required>
                                                        <option value="" disabled selected>Pilih Role</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="User">User</option>
                                                    </select>
                                                    <label>Role</label>
                                                </div>
                                                <div class="input-field col s12 m6">
                                                    <select id="status" required>
                                                        <option value="active" selected>Aktif</option>
                                                        <option value="inactive">Nonaktif</option>
                                                    </select>
                                                    <label>Status</label>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col s12 right-align">
                                                    <button class="btn waves-effect waves-light blue darken-2" type="submit">
                                                        <i class="material-icons left">save</i>Simpan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Account Modal -->
    <div id="edit-account-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Edit Akun</h4>
            <form>
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_full_name" type="text" class="validate" value="Manager Cabang" required>
                        <label for="edit_full_name">Nama Lengkap</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_username" type="text" class="validate" value="ManagerCabang" required>
                        <label for="edit_username">Username</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_password" type="password" class="validate">
                        <label for="edit_password">Password Baru (kosongkan jika tidak diubah)</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_confirm_password" type="password" class="validate">
                        <label for="edit_confirm_password">Konfirmasi Password Baru</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="edit_role" required>
                            <option value="admin">Admin</option>
                            <option value="User" selected>User</option>
                        </select>
                        <label>Role</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="edit_status" required>
                            <option value="active" selected>Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <a href="#!" class="modal-close waves-effect waves-green btn blue">Simpan Perubahan</a>
        </div>
    </div>

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
            
            // Toggle between list and add account views
            $('#add-account-btn').click(function() {
                $('#account-list').hide();
                $('#add-account').show();
            });
            
            $('#back-to-list').click(function() {
                $('#add-account').hide();
                $('#account-list').show();
            });
        });
    </script>
</body>
</html>