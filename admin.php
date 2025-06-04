<?php
session_start(); // Perubahan: [1] Memulai sesi PHP

// Perubahan: [2] Sertakan file koneksi database
include 'db_connect.php';

// Perubahan: [3] Autentikasi dan Otorisasi
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika tidak login atau bukan admin, redirect ke halaman login/dashboard
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
        header("Location: dashboard.php"); // Redirect user biasa ke dashboard
    } else {
        header("Location: login.php"); // Redirect non-login ke halaman login
    }
    exit();
}

$current_username = $_SESSION['username'];
$current_full_name = $_SESSION['full_name'];

// Perubahan: [4] Ambil data user dari database untuk ditampilkan di tabel
$users_data = [];
// Perhatian: Tidak mengambil password di sini untuk keamanan.
// Password hanya akan ditangani saat tambah/edit.
$sql = "SELECT id, full_name, username, role, status FROM users ORDER BY id ASC";
$result = $conn->query($sql);

if ($result) { // Pastikan query berhasil
    while($row = $result->fetch_assoc()) {
        $users_data[] = $row;
    }
}
// Koneksi ditutup setelah semua data diambil, sebelum HTML dimulai
$conn->close(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BranchWise</title>
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
        
        /* Perubahan: Gaya card-header baru */
        .card-header { 
            padding: 20px 24px; /* Padding sama dengan card-content */
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center; /* Memastikan vertikal center */
        }

        /* Perubahan: Gaya card-title disesuaikan agar tidak ada margin-bottom */
        .card .card-title {
            font-weight: 600; /* Lebih tebal agar menonjol */
            font-size: 1.25rem; /* Ukuran font konsisten dengan card-title lain */
            margin: 0; /* Sangat penting: Menghilangkan margin-bottom default */
            line-height: 1.2; /* Kontrol tinggi baris untuk perataan */
            display: block;
        }
        /* Akhir Perubahan: Gaya card-header dan card-title */

        /* Perubahan: Gaya Table Wrapper baru */
        .table-responsive { /* Ini adalah div wrapper baru untuk tabel */
            overflow-x: auto;
            border-radius: 0 0 8px 8px; /* Sudut bawah yang melengkung */
            border: 1px solid rgba(0,0,0,0.1);
            border-top: none; /* Menghapus border atas agar menyatu dengan card-header */
            overflow: hidden;
            margin-bottom: 24px; /* Memberi jarak ke elemen di bawahnya (pagination) */
        }
        /* Akhir Perubahan: Gaya Table Wrapper */

        /* Table Styles (hanya untuk <table> itu sendiri) */
        .responsive-table {
            width: 100%;
            border-collapse: collapse; 
            /* Properti border-radius, overflow, border dipindahkan ke .table-responsive */
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
        
        /* Action Buttons - Perubahan untuk menyamakan dengan dashboard.php */
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
        
        .edit-btn:hover { 
            color: var(--primary-color); 
        }

        .delete-btn:hover { 
            color: #e74c3c; 
        }
        /* Akhir Perubahan Action Buttons */
        
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
            padding: 2px 24px; /* Perubahan: padding-top dari 0px menjadi 16px untuk konsistensi */
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
        <li><a class="active" href="accounts.html"><i class="material-icons">people</i>Manajemen Akun</a></li>
        <li><div class="divider"></div></li>
        <li><a href="logout.php"><i class="material-icons">exit_to_app</i>Keluar</a></li>
    </ul>
    
    <main>
        <div class="header">
            <div class="row valign-wrapper" style="margin-bottom: 0; width: 100%;">
                <div class="col s6">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
                    <h1 class="page-title">Manajemen Akun</h1>
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
            <div id="account-list">
                <div class="row">
                    <div class="col s12">
                        <div class="card white">
                            <div class="card-header">
                                <div class="row" style="margin-bottom: 0; width: 100%;">
                                    <div class="col s12 m6">
                                        <h2 class="card-title">Daftar Akun</h2>
                                    </div>
                                    <div class="col s12 m6 right-align">
                                        <a href="#add-account-modal" class="btn waves-effect waves-light blue darken-2 modal-trigger">
                                            <i class="material-icons left">add</i>Tambah Akun
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
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
                                        <?php 
                                        if (!empty($users_data)): 
                                            $no = 1;
                                            foreach ($users_data as $user): 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td><span class="badge <?php echo ($user['status'] == 'active') ? 'active' : 'inactive'; ?>"><?php echo htmlspecialchars(ucfirst($user['status'])); ?></span></td>
                                            <td>
                                                <a href="#edit-account-modal" class="btn-flat action-btn edit-btn modal-trigger"
                                                   data-user-id="<?php echo $user['id']; ?>"
                                                   data-full_name="<?php echo htmlspecialchars($user['full_name']); ?>"
                                                   data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                   data-role="<?php echo htmlspecialchars($user['role']); ?>"
                                                   data-status="<?php echo htmlspecialchars($user['status']); ?>">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <a href="#!" class="btn-flat action-btn delete-btn" 
                                                   data-user-id="<?php echo $user['id']; ?>" 
                                                   data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                            endforeach;
                                        else: 
                                        ?>
                                        <tr>
                                            <td colspan="6" class="center-align">Tidak ada akun ditemukan.</td>
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
            </div>
    </main>

    <div id="edit-account-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Edit Akun</h4>
            <form id="edit-account-form"> <input type="hidden" id="edit_user_id" name="user_id"> <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_full_name" name="full_name" type="text" class="validate" required>
                        <label for="edit_full_name">Nama Lengkap</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_username" name="username" type="text" class="validate" required>
                        <label for="edit_username">Username</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="edit_password" name="password" type="password" class="validate">
                        <label for="edit_password">Password Baru (kosongkan jika tidak diubah)</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="edit_confirm_password" name="confirm_password" type="password" class="validate">
                        <label for="edit_confirm_password">Konfirmasi Password Baru</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option> 
                        </select>
                        <label>Role</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="edit_status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Batal</a>
            <button class="waves-effect waves-green btn blue" id="save-edit-btn">Simpan Perubahan</button>
        </div>
    </div>

    <div id="add-account-modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>Tambah Akun Baru</h4>
            <form id="add-account-form"> <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="full_name" name="full_name" type="text" class="validate" required>
                        <label for="full_name">Nama Lengkap</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="username" name="username" type="text" class="validate" required>
                        <label for="username">Username</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="password" name="password" type="password" class="validate" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="confirm_password" name="confirm_password" type="password" class="validate" required>
                        <label for="confirm_password">Konfirmasi Password</label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="input-field col s12 m6">
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option> 
                            <option value="user">User</option> 
                        </select>
                        <label>Role</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select id="status" name="status" required>
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
            <button class="waves-effect waves-green btn blue" id="save-add-btn">Simpan</button>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    
    <script>
        $(document).ready(function(){
            // Initialize sidenav
            $('.sidenav').sidenav();
            
            // Initialize modal
            $('#add-account-modal').modal(); 
            $('#edit-account-modal').modal(); 
            
            // Initialize select
            $('select').formSelect();

            // Perubahan: Implementasi Edit button functionality (untuk mengisi modal edit)
            $('.action-btn.edit-btn').on('click', function() {
                const userId = $(this).data('user-id'); // Ambil ID user dari data attribute
                
                // Ambil data dari baris tabel
                const userRow = $(this).closest('tr');
                const fullName = userRow.find('td:nth-child(2)').text();
                const username = userRow.find('td:nth-child(3)').text();
                const role = userRow.find('td:nth-child(4)').text().toLowerCase();
                const status = userRow.find('td:nth-child(5) span').text().toLowerCase();

                // Isi form modal edit
                $('#edit_user_id').val(userId); // Isi hidden input ID
                $('#edit_full_name').val(fullName);
                $('#edit_username').val(username);
                $('#edit_role').val(role);
                $('#edit_status').val(status);

                // Re-initialize select dan update text fields untuk label mengambang
                $('select').formSelect();
                M.updateTextFields(); 

                // Buka modal edit (kelas modal-trigger juga bisa melakukannya, tapi ini lebih eksplisit)
                $('#edit-account-modal').modal('open');
            });

            // Perubahan: Implementasi Delete button functionality (dengan konfirmasi dan AJAX)
            $('.action-btn.delete-btn').on('click', function(e) {
                e.preventDefault(); // Mencegah link default beraksi
                const userId = $(this).data('user-id'); // Ambil ID pengguna
                const username = $(this).data('username'); // Ambil username untuk konfirmasi
                
                if (confirm(`Apakah Anda yakin ingin menghapus akun "${username}"?`)) {
                    // Mengirim request AJAX untuk menghapus akun
                    $.ajax({
                        url: 'handle_admin_actions.php', // File PHP untuk menangani hapus
                        type: 'POST',
                        data: {
                            action: 'delete_user', // Tentukan aksi delete
                            user_id: userId
                        },
                        dataType: 'json', // Harap respons dalam format JSON
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: `Akun "${username}" berhasil dihapus!`});
                                location.reload(); // Refresh halaman setelah hapus
                            } else {
                                M.toast({html: `Gagal menghapus akun: ${response.message}`});
                            }
                        },
                        error: function() {
                            M.toast({html: 'Terjadi kesalahan saat menghapus akun.'});
                        }
                    });
                }
            });

            // Perubahan: Submit form Tambah Akun melalui AJAX
            $('#save-add-btn').click(function(e) {
                e.preventDefault(); // Mencegah submit form default
                
                const form = $('#add-account-form');
                // Lakukan validasi HTML5 form
                if (form[0].checkValidity()) { 
                    const formData = {
                        action: 'add_user', // Tentukan aksi tambah
                        full_name: $('#full_name').val(),
                        username: $('#username').val(),
                        password: $('#password').val(),
                        confirm_password: $('#confirm_password').val(),
                        role: $('#role').val(),
                        status: $('#status').val()
                    };

                    // Validasi password tambahan (min 8 karakter, kombinasi huruf & angka)
                    const password = formData.password;
                    const confirmPassword = formData.confirm_password;
                    if (password.length < 8 || !/[a-zA-Z]/.test(password) || !/\d/.test(password)) {
                        M.toast({html: 'Password harus minimal 8 karakter dan kombinasi huruf dan angka.'});
                        return;
                    }
                    if (password !== confirmPassword) {
                        M.toast({html: 'Password dan konfirmasi password tidak cocok.'});
                        return;
                    }

                    $.ajax({
                        url: 'handle_admin_actions.php', // File PHP untuk menangani tambah
                        type: 'POST',
                        data: formData,
                        dataType: 'json', 
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Akun berhasil ditambahkan!'});
                                $('#add-account-modal').modal('close'); // Tutup modal
                                location.reload(); // Refresh halaman untuk melihat data baru
                            } else {
                                M.toast({html: `Gagal menambahkan akun: ${response.message}`});
                            }
                        },
                        error: function() {
                            M.toast({html: 'Terjadi kesalahan saat menambahkan akun.'});
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'}); // Pesan jika validasi HTML5 gagal
                }
            });

            // Perubahan: Submit form Edit Akun melalui AJAX
            $('#save-edit-btn').click(function(e) {
                e.preventDefault(); // Mencegah submit form default
                
                const form = $('#edit-account-form');
                if (form[0].checkValidity()) {
                    const formData = {
                        action: 'edit_user', // Tentukan aksi edit
                        user_id: $('#edit_user_id').val(),
                        full_name: $('#edit_full_name').val(),
                        username: $('#edit_username').val(),
                        password: $('#edit_password').val(), // Password baru (bisa kosong)
                        confirm_password: $('#edit_confirm_password').val(),
                        role: $('#edit_role').val(),
                        status: $('#edit_status').val()
                    };
                    
                    const newPassword = formData.password;
                    const confirmNewPassword = formData.confirm_password;

                    if (newPassword) { // Jika password diisi (berarti ingin diubah)
                        if (newPassword.length < 8 || !/[a-zA-Z]/.test(newPassword) || !/\d/.test(newPassword)) {
                            M.toast({html: 'Password baru harus minimal 8 karakter dan kombinasi huruf dan angka.'});
                            return;
                        }
                        if (newPassword !== confirmNewPassword) {
                            M.toast({html: 'Password baru dan konfirmasi password baru tidak cocok.'});
                            return;
                        }
                    } else if (confirmNewPassword) { // Jika konfirmasi diisi tapi password kosong
                         M.toast({html: 'Mohon isi password baru juga jika ingin mengkonfirmasi.'});
                         return;
                    }

                    $.ajax({
                        url: 'handle_admin_actions.php', // File PHP untuk menangani edit
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                M.toast({html: 'Akun berhasil diperbarui!'});
                                $('#edit-account-modal').modal('close');
                                location.reload(); // Refresh halaman
                            } else {
                                M.toast({html: `Gagal memperbarui akun: ${response.message}`});
                            }
                        },
                        error: function() {
                            M.toast({html: 'Terjadi kesalahan saat memperbarui akun.'});
                        }
                    });
                } else {
                    M.toast({html: 'Mohon isi semua field yang wajib.'});
                }
            });
            // Akhir Perubahan: Submit form Tambah/Edit Akun melalui AJAX
        });
    </script>
</body>
</html>