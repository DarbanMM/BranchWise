<?php
session_start(); // Perubahan: [1] Memulai sesi PHP, penting untuk memeriksa otentikasi admin

// Perubahan: [2] Sertakan file koneksi database
include 'db_connect.php'; 

// Perubahan: [3] Atur header untuk memberitahu browser bahwa respons adalah JSON
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Aksi tidak valid atau tidak diotorisasi.']; // Default response

// Perubahan: [4] Pastikan request adalah POST dan user adalah admin untuk keamanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $action = $_POST['action'] ?? ''; // Ambil aksi dari data POST

    switch ($action) {
        case 'add_user':
            // Perubahan: [5] Logika untuk menambah user baru
            $full_name = $_POST['full_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? '';
            $status = $_POST['status'] ?? '';

            // Validasi di sisi server (harus sama dengan validasi di JS admin.php)
            if (empty($full_name) || empty($username) || empty($password) || empty($confirm_password) || empty($role) || empty($status)) {
                $response['message'] = "Semua field harus diisi.";
            } else if ($password !== $confirm_password) {
                $response['message'] = "Password dan Konfirmasi Password tidak cocok.";
            } else if (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
                $response['message'] = "Password harus minimal 8 karakter dan kombinasi huruf dan angka.";
            } else {
                // Periksa apakah username sudah ada
                $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt_check->bind_param("s", $username);
                $stmt_check->execute();
                $stmt_check->store_result();
                
                if ($stmt_check->num_rows > 0) {
                    $response['message'] = "Username sudah ada. Pilih username lain.";
                } else {
                    // Masukkan user baru (password tidak di-hash sesuai permintaan aplikasi)
                    $stmt_insert = $conn->prepare("INSERT INTO users (full_name, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
                    $stmt_insert->bind_param("sssss", $full_name, $username, $password, $role, $status);

                    if ($stmt_insert->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Akun baru berhasil ditambahkan.";
                    } else {
                        $response['message'] = "Gagal menambahkan akun: " . $stmt_insert->error;
                    }
                    $stmt_insert->close();
                }
                $stmt_check->close();
            }
            break;

        case 'edit_user':
            // Perubahan: [6] Logika untuk mengedit user
            $user_id = $_POST['user_id'] ?? null;
            $full_name = $_POST['full_name'] ?? '';
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? ''; // Opsional, jika diisi berarti ingin diubah
            $confirm_password = $_POST['confirm_password'] ?? ''; // Opsional
            $role = $_POST['role'] ?? '';
            $status = $_POST['status'] ?? '';

            // Validasi di sisi server
            if (empty($user_id) || empty($full_name) || empty($username) || empty($role) || empty($status)) {
                $response['message'] = "Semua field wajib diisi.";
            } else if (!empty($password) && $password !== $confirm_password) {
                $response['message'] = "Password baru dan Konfirmasi Password tidak cocok.";
            } else if (!empty($password) && (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password))) {
                 $response['message'] = "Password baru harus minimal 8 karakter dan kombinasi huruf dan angka.";
            }
            else {
                $sql_update = "UPDATE users SET full_name = ?, username = ?, role = ?, status = ? WHERE id = ?";
                $params = [$full_name, $username, $role, $status, $user_id];
                $types = "ssssi"; // ssss untuk string, i untuk integer ID

                // Jika password diisi, update juga passwordnya
                if (!empty($password)) {
                    $sql_update = "UPDATE users SET full_name = ?, username = ?, password = ?, role = ?, status = ? WHERE id = ?";
                    // Memasukkan password di posisi yang benar dalam array parameter
                    array_splice($params, 2, 0, $password); 
                    $types = "sssssi"; // Tipe data baru dengan tambahan 's' untuk password
                }

                $stmt_update = $conn->prepare($sql_update);
                // Gunakan call_user_func_array untuk bind_param dengan array dinamis
                // Parameter bind_param harus berupa referensi, sehingga kita perlu sedikit trik
                $bind_params = [];
                $bind_params[] = &$types; // Parameter pertama adalah string tipe data
                for ($i = 0; $i < count($params); $i++) {
                    $bind_params[] = &$params[$i]; // Kemudian parameter data sebagai referensi
                }
                call_user_func_array([$stmt_update, 'bind_param'], $bind_params);

                if ($stmt_update->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Akun berhasil diperbarui.";
                } else {
                    $response['message'] = "Gagal memperbarui akun: " . $stmt_update->error;
                }
                $stmt_update->close();
            }
            break;

        case 'delete_user':
            // Perubahan: [7] Logika untuk menghapus user
            $user_id = $_POST['user_id'] ?? null;

            if (empty($user_id) || !is_numeric($user_id)) {
                $response['message'] = "ID pengguna tidak valid.";
            } else {
                $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt_delete->bind_param("i", $user_id);

                if ($stmt_delete->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Akun berhasil dihapus.";
                } else {
                    $response['message'] = "Gagal menghapus akun: " . $stmt_delete->error;
                }
                $stmt_delete->close();
            }
            break;

        default:
            $response['message'] = "Aksi tidak dikenal.";
            break;
    }
} else {
    // Perubahan: [8] Pesan jika permintaan tidak valid atau tidak diotorisasi
    $response['message'] = "Permintaan tidak valid atau tidak diotorisasi.";
}

$conn->close(); // Tutup koneksi database
echo json_encode($response); // Kirim respons dalam format JSON
?>