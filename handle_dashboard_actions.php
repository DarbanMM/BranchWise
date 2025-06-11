<?php
session_start();
include 'db_connect.php'; // Menggunakan koneksi PDO ($pdo)

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Aksi tidak valid atau tidak diotorisasi.'];

// Pastikan user login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    $response['message'] = "Autentikasi gagal. Mohon login kembali.";
    echo json_encode($response);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// Membungkus semua aksi dalam try-catch untuk penanganan error yang lebih baik
try {
    switch ($action) {
        case 'add_project':
            $project_name = $_POST['project_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $priority = $_POST['priority'] ?? '';
            $status = $_POST['status'] ?? '';
            $deadline = $_POST['deadline'] ?? null;
            $assignee = $_POST['assignee'] ?? '';

            if (empty($project_name) || empty($priority) || empty($status)) {
                $response['message'] = "Nama Proyek, Prioritas, dan Status harus diisi.";
                break;
            }

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

            // INSERT menggunakan PDO
            $sql = "INSERT INTO projects (user_id, project_name, description, priority, status, deadline, assignee) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$current_user_id, $project_name, $description, $priority, $status, $formatted_deadline, $assignee]);
            
            $response['success'] = true;
            $response['message'] = "Proyek berhasil ditambahkan.";
            break;

        case 'edit_project':
            $project_id = $_POST['project_id'] ?? null;
            $project_name = $_POST['project_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $priority = $_POST['priority'] ?? '';
            $status = $_POST['status'] ?? '';
            $deadline = $_POST['deadline'] ?? null;
            $assignee = $_POST['assignee'] ?? '';

            if (empty($project_id) || empty($project_name) || empty($priority) || empty($status)) {
                $response['message'] = "ID Proyek, Nama Proyek, Prioritas, dan Status harus diisi.";
                break;
            }

            $formatted_deadline = null;
            if (!empty($deadline) && $deadline !== 'N/A') {
                $date_obj = DateTime::createFromFormat('d M Y', $deadline);
                if ($date_obj) {
                    $formatted_deadline = $date_obj->format('Y-m-d');
                } else {
                    $response['message'] = "Format Deadline tidak valid.";
                    echo json_encode($response);
                    exit();
                }
            }
            
            // Cek kepemilikan proyek menggunakan PDO
            $stmt_check = $pdo->prepare("SELECT user_id FROM projects WHERE id = ?");
            $stmt_check->execute([$project_id]);
            $project_owner = $stmt_check->fetch();

            if (!$project_owner || ($project_owner['user_id'] != $current_user_id && $_SESSION['role'] !== 'admin')) {
                $response['message'] = "Anda tidak memiliki izin untuk mengedit proyek ini.";
                break;
            }

            // UPDATE menggunakan PDO
            $sql = "UPDATE projects SET project_name = ?, description = ?, priority = ?, status = ?, deadline = ?, assignee = ? WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$project_name, $description, $priority, $status, $formatted_deadline, $assignee, $project_id, $current_user_id]);
            
            $response['success'] = true;
            $response['message'] = "Proyek berhasil diperbarui.";
            break;

        case 'delete_project':
            $project_id = $_POST['project_id'] ?? null;

            if (empty($project_id)) {
                $response['message'] = "ID Proyek tidak valid.";
                break;
            }
            
            // Cek kepemilikan proyek menggunakan PDO
            $stmt_check = $pdo->prepare("SELECT user_id FROM projects WHERE id = ?");
            $stmt_check->execute([$project_id]);
            $project_owner = $stmt_check->fetch();

            if (!$project_owner || ($project_owner['user_id'] != $current_user_id && $_SESSION['role'] !== 'admin')) {
                $response['message'] = "Anda tidak memiliki izin untuk menghapus proyek ini.";
                break;
            }
            
            // DELETE menggunakan PDO
            $sql = "DELETE FROM projects WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$project_id, $current_user_id]);
            
            $response['success'] = true;
            $response['message'] = "Proyek berhasil dihapus.";
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