<?php
session_start();
require_once 'connection.php';

$action = $_POST['action'] ?? '';
$source = $_POST['source'] ?? '';
$id = $_POST['jadwalId'] ?? null;
$dokter_id = $_POST['dokterId'] ?? null;
$hari = $_POST['hari'] ?? '';
$jam_mulai = $_POST['jamMulai'] ?? null;
$jam_selesai = $_POST['jamSelesai'] ?? null;
$status = $_POST['status'] ?? 'Aktif';
$page = 'jadwal';

if (isset($_POST['source']) && $_POST['source'] == 'jadwalForm') {

    //  CREATE action
    if ($action == 'create' && !empty($dokter_id) && !empty($hari)) {
        // Jika status 'Cuti', set jam menjadi NULL
        if ($status == 'Cuti') {
            $jam_mulai = null;
            $jam_selesai = null;
        }

        $stmt = $conn->prepare("INSERT INTO jadwal (dokter_id, hari, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $dokter_id, $hari, $jam_mulai, $jam_selesai, $status);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil ditambahkan!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menambahkan jadwal.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }

    //  UPDATE action
    if ($action == 'update' && !empty($id)) {
        if ($status == 'Cuti') {
            $jam_mulai = null;
            $jam_selesai = null;
        }

        $stmt = $conn->prepare("UPDATE jadwal SET dokter_id=?, hari=?, jam_mulai=?, jam_selesai=?, status=? WHERE id=?");
        $stmt->bind_param("issssi", $dokter_id, $hari, $jam_mulai, $jam_selesai, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil diperbarui!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal memperbarui jadwal.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }

    //  DELETE action
    if ($action == 'delete' && !empty($id)) {
        $stmt = $conn->prepare("DELETE FROM jadwal WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil dihapus!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menghapus jadwal.',
                'type' => 'error'
            ];
        }
        header("Location: ?page=" . $page);
        exit();
    }
}

header("Location: ../admin/index.php?page=jadwal");
exit();
