<?php
session_start(); // Memulai sesi
include 'db_connect.php'; // Sertakan file koneksi database

header('Content-Type: application/json'); // Atur header untuk respons JSON

$response = ['success' => false, 'message' => '']; // Inisialisasi array respons

// Perubahan: Pastikan user login dan memiliki role yang sesuai (user atau admin yang membuat proyek)
// Untuk dashboard, kita asumsikan user yang login adalah pemilik proyek atau admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    $response['message'] = "Autentikasi gagal. Mohon login kembali.";
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'add_project':
        $project_name = $_POST['project_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $priority = $_POST['priority'] ?? '';
        $status = $_POST['status'] ?? '';
        $deadline = $_POST['deadline'] ?? null;
        $assignee = $_POST['assignee'] ?? '';

        // Validasi dasar
        if (empty($project_name) || empty($priority) || empty($status)) {
            $response['message'] = "Nama Proyek, Prioritas, dan Status harus diisi.";
            break;
        }

        // Konversi format deadline jika ada
        $formatted_deadline = null;
        if (!empty($deadline)) {
            $date_obj = DateTime::createFromFormat('d M Y', $deadline);
            if ($date_obj) {
                $formatted_deadline = $date_obj->format('Y-m-d');
            } else {
                $response['message'] = "Format Deadline tidak valid.";
                echo json_encode($response);
                exit();
            }
        }

        $stmt = $conn->prepare("INSERT INTO projects (user_id, project_name, description, priority, status, deadline, assignee) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $current_user_id, $project_name, $description, $priority, $status, $formatted_deadline, $assignee);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Proyek berhasil ditambahkan.";
        } else {
            $response['message'] = "Gagal menambahkan proyek: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'edit_project':
        $project_id = $_POST['project_id'] ?? null;
        $project_name = $_POST['project_name'] ?? '';
        $description = $_POST['description'] ?? '';
        $priority = $_POST['priority'] ?? '';
        $status = $_POST['status'] ?? '';
        $deadline = $_POST['deadline'] ?? null;
        $assignee = $_POST['assignee'] ?? '';

        // Validasi dasar
        if (empty($project_id) || empty($project_name) || empty($priority) || empty($status)) {
            $response['message'] = "ID Proyek, Nama Proyek, Prioritas, dan Status harus diisi.";
            echo json_encode($response);
            exit();
        }

        // Konversi format deadline jika ada
        $formatted_deadline = null;
        if (!empty($deadline) && $deadline !== 'N/A') { // 'N/A' from JS if deadline is empty
            $date_obj = DateTime::createFromFormat('d M Y', $deadline);
            if ($date_obj) {
                $formatted_deadline = $date_obj->format('Y-m-d');
            } else {
                $response['message'] = "Format Deadline tidak valid.";
                echo json_encode($response);
                exit();
            }
        }
        
        // Pastikan hanya pemilik proyek yang bisa mengedit
        $check_owner_stmt = $conn->prepare("SELECT user_id FROM projects WHERE id = ?");
        $check_owner_stmt->bind_param("i", $project_id);
        $check_owner_stmt->execute();
        $owner_result = $check_owner_stmt->get_result();
        $project_owner_id = $owner_result->fetch_assoc()['user_id'] ?? null;
        $check_owner_stmt->close();

        if ($project_owner_id != $current_user_id && $_SESSION['role'] !== 'admin') { // Admin bisa edit semua
            $response['message'] = "Anda tidak memiliki izin untuk mengedit proyek ini.";
            echo json_encode($response);
            exit();
        }

        $stmt = $conn->prepare("UPDATE projects SET project_name = ?, description = ?, priority = ?, status = ?, deadline = ?, assignee = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssssssii", $project_name, $description, $priority, $status, $formatted_deadline, $assignee, $project_id, $current_user_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Proyek berhasil diperbarui.";
        } else {
            $response['message'] = "Gagal memperbarui proyek: " . $stmt->error;
        }
        $stmt->close();
        break;

    case 'delete_project':
        $project_id = $_POST['project_id'] ?? null;

        if (empty($project_id)) {
            $response['message'] = "ID Proyek tidak valid.";
            echo json_encode($response);
            exit();
        }
        
        // Pastikan hanya pemilik proyek yang bisa menghapus
        $check_owner_stmt = $conn->prepare("SELECT user_id FROM projects WHERE id = ?");
        $check_owner_stmt->bind_param("i", $project_id);
        $check_owner_stmt->execute();
        $owner_result = $check_owner_stmt->get_result();
        $project_owner_id = $owner_result->fetch_assoc()['user_id'] ?? null;
        $check_owner_stmt->close();

        if ($project_owner_id != $current_user_id && $_SESSION['role'] !== 'admin') { // Admin bisa hapus semua
            $response['message'] = "Anda tidak memiliki izin untuk menghapus proyek ini.";
            echo json_encode($response);
            exit();
        }

        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $project_id, $current_user_id); // Hapus hanya jika user_id cocok

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Proyek berhasil dihapus.";
        } else {
            $response['message'] = "Gagal menghapus proyek: " . $stmt->error;
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