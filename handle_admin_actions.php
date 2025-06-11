<?php
session_start();
include 'db_connect.php'; // Menggunakan koneksi PDO ($pdo)

header('Content-Type: application/json'); // Atur header untuk respons JSON
$response = ['success' => false, 'message' => 'Aksi tidak valid atau tidak diotorisasi.']; // Default response

// Pastikan request adalah POST dan user adalah admin untuk keamanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $action = $_POST['action'] ?? '';

    // Gunakan try-catch untuk menangani semua kemungkinan error database
    try {
        switch ($action) {
            case 'add_user':
                $full_name = $_POST['full_name'] ?? '';
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                $role = $_POST['role'] ?? '';
                $status = $_POST['status'] ?? '';

                // Validasi sisi server (tidak berubah)
                if (empty($full_name) || empty($username) || empty($password) || empty($confirm_password) || empty($role) || empty($status)) {
                    $response['message'] = "Semua field harus diisi.";
                } elseif ($password !== $confirm_password) {
                    $response['message'] = "Password dan Konfirmasi Password tidak cocok.";
                } elseif (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
                    $response['message'] = "Password harus minimal 8 karakter dan kombinasi huruf dan angka.";
                } else {
                    // Periksa apakah username sudah ada menggunakan PDO
                    $stmt_check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
                    $stmt_check->execute([$username]);
                    
                    if ($stmt_check->fetch()) {
                        $response['message'] = "Username sudah ada. Pilih username lain.";
                    } else {
                        // Masukkan user baru menggunakan PDO
                        $stmt_insert = $pdo->prepare("INSERT INTO users (full_name, username, password, role, status) VALUES (?, ?, ?, ?, ?)");
                        $stmt_insert->execute([$full_name, $username, $password, $role, $status]);
                        
                        $response['success'] = true;
                        $response['message'] = "Akun baru berhasil ditambahkan.";
                    }
                }
                break;

            case 'edit_user':
                $user_id = $_POST['user_id'] ?? null;
                $full_name = $_POST['full_name'] ?? '';
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                $role = $_POST['role'] ?? '';
                $status = $_POST['status'] ?? '';

                // Validasi (tidak berubah)
                if (empty($user_id) || empty($full_name) || empty($username) || empty($role) || empty($status)) {
                    $response['message'] = "Semua field wajib diisi.";
                } elseif (!empty($password) && $password !== $confirm_password) {
                    $response['message'] = "Password baru dan Konfirmasi Password tidak cocok.";
                } elseif (!empty($password) && (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password))) {
                    $response['message'] = "Password baru harus minimal 8 karakter dan kombinasi huruf dan angka.";
                } else {
                    // Update user menggunakan PDO
                    if (!empty($password)) {
                        // Jika password diubah
                        $sql = "UPDATE users SET full_name = ?, username = ?, password = ?, role = ?, status = ? WHERE id = ?";
                        $params = [$full_name, $username, $password, $role, $status, $user_id];
                    } else {
                        // Jika password tidak diubah
                        $sql = "UPDATE users SET full_name = ?, username = ?, role = ?, status = ? WHERE id = ?";
                        $params = [$full_name, $username, $role, $status, $user_id];
                    }
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);

                    $response['success'] = true;
                    $response['message'] = "Akun berhasil diperbarui.";
                }
                break;

            case 'delete_user':
                $user_id = $_POST['user_id'] ?? null;

                if (empty($user_id) || !is_numeric($user_id)) {
                    $response['message'] = "ID pengguna tidak valid.";
                } else {
                    // Hapus user menggunakan PDO
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    
                    $response['success'] = true;
                    $response['message'] = "Akun berhasil dihapus.";
                }
                break;

            default:
                $response['message'] = "Aksi tidak dikenal.";
                break;
        }
    } catch (PDOException $e) {
        // Tangani semua jenis error dari database
        if ($e->getCode() == '23505') { // Kode duplikat di PostgreSQL
            $response['message'] = "Gagal: Username sudah ada.";
        } else {
            $response['message'] = "Operasi database gagal: " . $e->getMessage();
        }
    }
}

// Kirim respons dalam format JSON
echo json_encode($response);
?>