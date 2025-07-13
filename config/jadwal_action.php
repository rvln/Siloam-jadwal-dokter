<?php
session_start();
require_once 'connection.php';

$action = $_POST['action'] ?? '';
$source = $_POST['source'] ?? '';
$page = 'jadwal';
$id = $_POST['id'] ?? $_POST['jadwalId'] ?? null;

$hari = $_POST['hari'] ?? [];
if (!is_array($hari)) {
    $hari = [$hari];
}

if ($source === 'jadwal_form' && $action === 'update_status' && $id && isset($_POST['status'])) {
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE jadwal SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        $_SESSION['toast_message'] = [
            'text' => 'Status berhasil diperbarui!',
            'type' => 'success'
        ];
    } else {
        $_SESSION['toast_message'] = [
            'text' => 'Gagal memperbarui status.',
            'type' => 'error',
            'error' => $stmt->error
        ];
    }

    header("Location: ?page=" . $page);
    exit();
}

if ($source === 'jadwalForm') {
    $dokter_id = $_POST['dokterId'] ?? null;
    $status = $_POST['status'] ?? 'Aktif';

    // CREATE
    if ($action == 'create' && !empty($dokter_id) && !empty($hari)) {
        $stmt = $conn->prepare("INSERT INTO jadwal (dokter_id, hari, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, ?)");

        $success = 0;
        $err = 0;
        $last_error = '';

        foreach ($hari as $hari_spesifik) {
            $jam_mulai_spesifik = null;
            $jam_selesai_spesifik = null;

            if ($status == 'Aktif') {
                $jam_mulai_spesifik = $_POST['jam_mulai'][$hari_spesifik] ?? null;
                $jam_selesai_spesifik = $_POST['jam_selesai'][$hari_spesifik] ?? null;

                if (empty($jam_mulai_spesifik) || empty($jam_selesai_spesifik)) {
                    $err++;
                    $last_error = "Jam kosong untuk hari $hari_spesifik";
                    continue;
                }

                if ($jam_mulai_spesifik >= $jam_selesai_spesifik) {
                    $err++;
                    $last_error = "Jam selesai harus setelah jam mulai untuk hari $hari_spesifik";
                    continue;
                }
            }

            $stmt->bind_param("issss", $dokter_id, $hari_spesifik, $jam_mulai_spesifik, $jam_selesai_spesifik, $status);

            if ($stmt->execute()) {
                $success++;
            } else {
                $err++;
                $last_error = $stmt->error;
            }
        }


        if ($success > 0) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil ditambahkan!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menambahkan jadwal.',
                'type' => 'error',
                'error' => $last_error
            ];
        }

        header("Location: ?page=" . $page);
        exit();
    }


    // UPDATE
    if ($action === 'update' && !empty($id) && $dokter_id) {
        $hari_str = $_POST['hari'] ?? null;
        $jam_mulai_str = $_POST['jamMulai'] ?? null;
        $jam_selesai_str = $_POST['jamSelesai'] ?? null;

        $stmt = $conn->prepare("UPDATE jadwal SET dokter_id = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, status = ? WHERE id = ?");
        $stmt->bind_param("issssi", $dokter_id, $hari_str, $jam_mulai_str, $jam_selesai_str, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil diperbarui!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal memperbarui jadwal.',
                'type' => 'error',
                'error' => $stmt->error
            ];
        }

        header("Location: ?page=" . $page);
        exit();
    }

    // DELETE
    if ($action === 'delete' && !empty($id)) {
        $stmt = $conn->prepare("DELETE FROM jadwal WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = [
                'text' => 'Jadwal berhasil dihapus!',
                'type' => 'success'
            ];
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menghapus jadwal.',
                'type' => 'error',
                'error' => $stmt->error
            ];
        }

        header("Location: ?page=" . $page);
        exit();
    }
}

// Fallback redirect
header("Location: ../admin/index.php?page=jadwal");
exit();
