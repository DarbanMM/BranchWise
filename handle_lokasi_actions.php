<?php
session_start(); // Memulai sesi
include 'db_connect.php'; // Sertakan file koneksi database

header('Content-Type: application/json'); // Atur header untuk respons JSON

$response = ['success' => false, 'message' => '']; // Inisialisasi array respons

// Perubahan: Periksa otentikasi dan otorisasi
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    $response['message'] = "Autentikasi gagal. Mohon login kembali.";
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$project_id = $_POST['project_id'] ?? null; // Pastikan project_id diterima dari AJAX

// Perubahan: Verifikasi project_id dan kepemilikan
// Admin memiliki akses penuh, user biasa hanya bisa proyek miliknya
$stmt_check_project = $conn->prepare("SELECT user_id FROM projects WHERE id = ?");
$stmt_check_project->bind_param("i", $project_id);
$stmt_check_project->execute();
$result_check_project = $stmt_check_project->get_result();
$project_info = $result_check_project->fetch_assoc();
$stmt_check_project->close();

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


switch ($action) {
    case 'add_location':
        // Perubahan: [6] Logika untuk menambah lokasi baru
        $branch_name = $_POST['branch_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $phone = $_POST['phone'] ?? null;
        $email = $_POST['email'] ?? null;
        $size_sqm = $_POST['size_sqm'] ?? null;
        $status = $_POST['status'] ?? '';
        $gmaps_link = $_POST['gmaps_link'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (empty($branch_name) || empty($address) || empty($city) || empty($status)) {
            $response['message'] = "Nama Cabang, Alamat, Kota, dan Status harus diisi.";
            break;
        }

        $stmt = $conn->prepare("INSERT INTO locations (project_id, branch_name, address, city, phone, email, size_sqm, status, gmaps_link, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssdsss", $project_id, $branch_name, $address, $city, $phone, $email, $size_sqm, $status, $gmaps_link, $notes);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Cabang baru berhasil ditambahkan.";
        } else {
            $response['message'] = "Gagal menambahkan cabang: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'edit_location':
        // Perubahan: [7] Logika untuk mengedit lokasi
        $location_id = $_POST['location_id'] ?? null;
        $branch_name = $_POST['branch_name'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $phone = $_POST['phone'] ?? null;
        $email = $_POST['email'] ?? null;
        $size_sqm = $_POST['size_sqm'] ?? null;
        $status = $_POST['status'] ?? '';
        $gmaps_link = $_POST['gmaps_link'] ?? null;
        $notes = $_POST['notes'] ?? null;

        if (empty($location_id) || empty($branch_name) || empty($address) || empty($city) || empty($status)) {
            $response['message'] = "ID Lokasi, Nama Cabang, Alamat, Kota, dan Status harus diisi.";
            break;
        }

        $stmt = $conn->prepare("UPDATE locations SET branch_name = ?, address = ?, city = ?, phone = ?, email = ?, size_sqm = ?, status = ?, gmaps_link = ?, notes = ? WHERE id = ? AND project_id = ?");
        $stmt->bind_param("sssssdssii", $branch_name, $address, $city, $phone, $email, $size_sqm, $status, $gmaps_link, $notes, $location_id, $project_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Cabang berhasil diperbarui.";
        } else {
            $response['message'] = "Gagal memperbarui cabang: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'delete_location':
        // Perubahan: [8] Logika untuk menghapus lokasi
        $location_id = $_POST['location_id'] ?? null;

        if (empty($location_id)) {
            $response['message'] = "ID Lokasi tidak valid.";
            break;
        }

        $stmt = $conn->prepare("DELETE FROM locations WHERE id = ? AND project_id = ?");
        $stmt->bind_param("ii", $location_id, $project_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Lokasi berhasil dihapus.";
        } else {
            $response['message'] = "Gagal menghapus lokasi: " . $stmt->error;
        }
        $stmt->close();
        break;

    default:
        $response['message'] = "Aksi tidak dikenal.";
        break;
}

$conn->close(); // Tutup koneksi database
echo json_encode($response); // Kirim respons JSON
?>