<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

// Mengambil statistik
try {
    // Total tamu yang hadir
    $stmt = $pdo->query("SELECT SUM(number_of_guests) as total_hadir FROM guests WHERE attendance_status = 'Hadir'");
    $total_hadir = $stmt->fetch(PDO::FETCH_ASSOC)['total_hadir'] ?? 0;

    // Total tamu yang tidak hadir
    $stmt = $pdo->query("SELECT COUNT(*) as total_tidak_hadir FROM guests WHERE attendance_status = 'Tidak Hadir'");
    $total_tidak_hadir = $stmt->fetch(PDO::FETCH_ASSOC)['total_tidak_hadir'];

    // Total pesan/ucapan
    $stmt = $pdo->query("SELECT COUNT(*) as total_pesan FROM messages");
    $total_pesan = $stmt->fetch(PDO::FETCH_ASSOC)['total_pesan'];

    // Mengambil data tamu dan pesan terbaru
    $recent_guests = $pdo->query("
        SELECT * FROM guests 
        ORDER BY created_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

    $recent_messages = $pdo->query("
        SELECT m.*, g.name as guest_name 
        FROM messages m 
        JOIN guests g ON m.guest_id = g.id 
        ORDER BY m.created_at DESC 
        LIMIT 10
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .nav-link.active {
            background-color: #e74c3c !important;
            color: white !important;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Wedding Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konfirmasi.php">
                            <i class="bi bi-people"></i> Daftar Tamu
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="../" class="btn btn-outline-light me-3">
                        <i class="bi bi-house-door"></i> Halaman Utama
                    </a>
                    <a href="logout.php" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Dashboard</h2>
        
        <!-- Statistik -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-success text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Hadir</h6>
                            <h2 class="mb-0"><?php echo $total_hadir; ?></h2>
                            <small>orang</small>
                        </div>
                        <i class="bi bi-people-fill stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-danger text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Tidak Hadir</h6>
                            <h2 class="mb-0"><?php echo $total_tidak_hadir; ?></h2>
                            <small>orang</small>
                        </div>
                        <i class="bi bi-x-circle-fill stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card bg-primary text-white p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Ucapan</h6>
                            <h2 class="mb-0"><?php echo $total_pesan; ?></h2>
                            <small>pesan</small>
                        </div>
                        <i class="bi bi-chat-fill stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Cepat -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3">
                            <a href="wedding_settings.php" class="btn btn-primary">
                                <i class="bi bi-gear-fill me-2"></i>
                                Pengaturan Undangan
                            </a>
                            <a href="gallery.php" class="btn btn-info text-white">
                                <i class="bi bi-images me-2"></i>
                                Kelola Galeri
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tamu Terbaru -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="table-container">
                    <h4 class="mb-4">Tamu Terbaru</h4>
                    <?php if (isset($_GET['status'])): ?>
                        <?php if ($_GET['status'] == 'deleted'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Data tamu berhasil dihapus
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($_GET['status'] == 'error'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Terjadi kesalahan saat menghapus data
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_guests as $guest): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $guest['attendance_status'] == 'Hadir' ? 'success' : 'danger'; ?>">
                                            <?php echo $guest['attendance_status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $guest['number_of_guests']; ?> orang</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($guest['created_at'])); ?></td>
                                    <td>
                                        <form action="delete_guest.php" method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tamu ini?');">
                                            <input type="hidden" name="guest_id" value="<?php echo $guest['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ucapan Terbaru -->
            <div class="col-md-6 mb-4">
                <div class="table-container">
                    <h4 class="mb-4">Ucapan Terbaru</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Ucapan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_messages as $message): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($message['guest_name']); ?></td>
                                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 