<?php
session_start();
include 'db_connect.php'; // Menggunakan koneksi PDO ($pdo)

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Aksi tidak valid atau tidak diotorisasi.'];

// Periksa otentikasi dan otorisasi
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    $response['message'] = "Autentikasi gagal. Mohon login kembali.";
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$project_id = $_POST['project_id'] ?? null;

// Jika tidak ada project_id, hentikan proses
if (!$project_id) {
    $response['message'] = "ID Proyek tidak disertakan.";
    echo json_encode($response);
    exit();
}

try {
    // Verifikasi project_id dan kepemilikan
    $stmt_check_project = $pdo->prepare("SELECT user_id FROM projects WHERE id = ?");
    $stmt_check_project->execute([$project_id]);
    $project_info = $stmt_check_project->fetch();

    if (!$project_info) {
        $response['message'] = "Proyek tidak ditemukan.";
        echo json_encode($response);
        exit();
    }

    $project_owner_id = $project_info['user_id'];
    if ($_SESSION['role'] !== 'admin' && $project_owner_id !== $current_user_id) {
        $response['message'] = "Anda tidak memiliki izin untuk melakukan aksi pada proyek ini.";
        echo json_encode($response);
        exit();
    }

    // Lanjutkan ke aksi yang diminta
    switch ($action) {
        case 'add_location':
            $branch_name = $_POST['branch_name'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $phone = $_POST['phone'] ?? null;
            $email = $_POST['email'] ?? null;
            $size_sqm = !empty($_POST['size_sqm']) ? $_POST['size_sqm'] : null;
            $status = $_POST['status'] ?? '';
            $gmaps_link = $_POST['gmaps_link'] ?? null;
            $notes = $_POST['notes'] ?? null;

            if (empty($branch_name) || empty($address) || empty($city) || empty($status)) {
                $response['message'] = "Nama Cabang, Alamat, Kota, dan Status harus diisi.";
                break;
            }

            $sql = "INSERT INTO locations (project_id, branch_name, address, city, phone, email, size_sqm, status, gmaps_link, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$project_id, $branch_name, $address, $city, $phone, $email, $size_sqm, $status, $gmaps_link, $notes]);
            
            $response['success'] = true;
            $response['message'] = "Cabang baru berhasil ditambahkan.";
            break;

        case 'edit_location':
            $location_id = $_POST['location_id'] ?? null;
            $branch_name = $_POST['branch_name'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $phone = $_POST['phone'] ?? null;
            $email = $_POST['email'] ?? null;
            $size_sqm = !empty($_POST['size_sqm']) ? $_POST['size_sqm'] : null;
            $status = $_POST['status'] ?? '';
            $gmaps_link = $_POST['gmaps_link'] ?? null;
            $notes = $_POST['notes'] ?? null;

            if (empty($location_id) || empty($branch_name) || empty($address) || empty($city) || empty($status)) {
                $response['message'] = "ID Lokasi, Nama Cabang, Alamat, Kota, dan Status harus diisi.";
                break;
            }

            $sql = "UPDATE locations SET branch_name = ?, address = ?, city = ?, phone = ?, email = ?, size_sqm = ?, status = ?, gmaps_link = ?, notes = ? WHERE id = ? AND project_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$branch_name, $address, $city, $phone, $email, $size_sqm, $status, $gmaps_link, $notes, $location_id, $project_id]);
            
            $response['success'] = true;
            $response['message'] = "Cabang berhasil diperbarui.";
            break;

        case 'delete_location':
            $location_id = $_POST['location_id'] ?? null;

            if (empty($location_id)) {
                $response['message'] = "ID Lokasi tidak valid.";
                break;
            }

            $sql = "DELETE FROM locations WHERE id = ? AND project_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$location_id, $project_id]);

            $response['success'] = true;
            $response['message'] = "Lokasi berhasil dihapus.";
            break;

        default:
            $response['message'] = "Aksi tidak dikenal.";
            break;
    }
} catch (PDOException $e) {
    $response['message'] = "Operasi database gagal: " . $e->getMessage();
}

echo json_encode($response);
?>