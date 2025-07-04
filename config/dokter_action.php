<?php
session_start();

require 'connection.php';

$action = $_POST['action'] ?? '';
$id = $_POST['dokterId'] ?? null;
$nama = $_POST['namaDokter'] ?? '';
$poli = $_POST['poliDokter'] ?? '';
$page = 'dokter';

if (isset($_POST['source']) && $_POST['source'] == 'dokterForm') {

    // CREATE action
    if ($action == 'create') {
        if (empty($nama) || empty($poli)) {
            $_SESSION['toast_message'] = [
                'text' => 'Nama dokter dan poli harus diisi.',
                'type' => 'error'
            ];
            header("Location: ?page=" . $page);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO dokter (nama, poli) VALUES (?, ?)");
        if (!$stmt) {
            $_SESSION['toast_message'] = [
                'text' => 'Error preparing statement: ' . $conn->error,
                'type' => 'error'
            ];
            header("Location: ?page=" . $page);
            exit();
        }

        $stmt->bind_param("ss", $nama, $poli);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['toast_message'] = [
                    'text' => 'Data dokter berhasil ditambahkan.',
                    'type' => 'success'
                ];
            } else {
                $_SESSION['toast_message'] = [
                    'text' => 'Data tidak tersimpan ke database.',
                    'type' => 'error'
                ];
            }
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal menambahkan data dokter: ' . $stmt->error,
                'type' => 'error'
            ];
        }

        $stmt->close();
        header("Location: ?page=" . $page);
        exit();
    }

    // UPDATE action
    if ($action == 'update' && !empty($id) && !empty($nama)) {
        $stmt = $conn->prepare("UPDATE dokter SET nama = ?, poli = ? WHERE id = ?");
        if (!$stmt) {
            $_SESSION['toast_message'] = [
                'text' => 'Error preparing statement: ' . $conn->error,
                'type' => 'error'
            ];
            header("Location: ?page=" . $page);
            exit();
        }

        if (!$stmt->bind_param("ssi", $nama, $poli, $id)) {
            $_SESSION['toast_message'] = [
                'text' => 'Error binding parameters: ' . $stmt->error,
                'type' => 'error'
            ];
            $stmt->close();
            header("Location: ?page=" . $page);
            exit();
        }

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['toast_message'] = [
                    'text' => 'Data dokter berhasil diubah.',
                    'type' => 'success'
                ];
            } else {
                $_SESSION['toast_message'] = [
                    'text' => 'Tidak ada perubahan data.',
                    'type' => 'info'
                ];
            }
        } else {
            $_SESSION['toast_message'] = [
                'text' => 'Gagal mengubah data dokter: ' . $stmt->error,
                'type' => 'error'
            ];
        }
        $stmt->close();
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
