<?php
session_start();

require 'connection.php';

$action = $_POST['action'] ?? '';
$id = $_POST['dokterId'] ?? null;
$nama = $_POST['namaDokter'] ?? '';
$page = 'dokter';

if (isset($_POST['source']) && $_POST['source'] == 'dokterForm') {

    // CREATE action
    if ($action == 'create' && !empty($nama)) {
        $stmt = $conn->prepare("INSERT INTO dokter (nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Data dokter berhasil ditambahkan.',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menambahkan data dokter.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }

    // UPDATE action
    if ($action == 'update' && !empty($id) && !empty($nama)) {
        $stmt = $conn->prepare("UPDATE dokter SET nama = ? WHERE id = ?");
        $stmt->bind_param("si", $nama, $id);
        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Data dokter berhasil diubah.',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal mengubah data dokter.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }

    // DELETE action
    if ($action == 'delete' && !empty($id)) {
        $stmt_delete_jadwal = $conn->prepare("DELETE FROM jadwal WHERE dokter_id = ?");
        $stmt_delete_jadwal->bind_param("i", $id);
        $stmt_delete_jadwal->execute();

        $stmt_delete_dokter = $conn->prepare("DELETE FROM dokter WHERE id = ?");
        $stmt_delete_dokter->bind_param("i", $id);
        if ($stmt_delete_dokter->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Data dokter berhasil dihapus.',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menghapus data dokter.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }
}

header("Location: ../admin/index.php?page=dokter");
exit();
