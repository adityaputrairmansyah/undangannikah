<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

// Mengambil data tamu dan pesan
$query = "
    SELECT 
        g.id,
        g.name,
        g.attendance_status,
        g.number_of_guests,
        g.created_at,
        m.message
    FROM guests g
    LEFT JOIN messages m ON g.id = m.guest_id
    ORDER BY g.created_at DESC
";

try {
    $stmt = $pdo->query($query);
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Menghitung total tamu
    $totalGuests = "SELECT SUM(number_of_guests) as total FROM guests WHERE attendance_status = 'Hadir'";
    $stmt = $pdo->query($totalGuests);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konfirmasi Kehadiran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #2c3e50;
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
        .total-badge {
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 50px;
        }
        .nav-link.active {
            background-color: #e74c3c !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Wedding Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="konfirmasi.php">
                            <i class="bi bi-people"></i> Daftar Tamu
                        </a>
                    </li>
                </ul>
                <a href="logout.php" class="btn btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <!-- Header dengan tombol kembali -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Daftar Konfirmasi Kehadiran</h1>
            <div>
                <a href="dashboard.php" class="btn btn-primary me-2">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <span class="badge bg-success total-badge">
                    Total Tamu yang Hadir: <?php echo $total ?? 0; ?> orang
                </span>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header py-3">
                <h5 class="mb-0">Data Konfirmasi</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="guestTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Jumlah</th>
                                <th>Ucapan & Doa</th>
                                <th>Waktu Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($guests as $index => $guest): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $guest['attendance_status'] == 'Hadir' ? 'success' : 'danger'; ?>">
                                        <?php echo $guest['attendance_status']; ?>
                                    </span>
                                </td>
                                <td><?php echo $guest['number_of_guests']; ?> orang</td>
                                <td><?php echo htmlspecialchars($guest['message'] ?? '-'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($guest['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#guestTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                order: [[5, 'desc']],
                pageLength: 25
            });
        });
    </script>
</body>
</html> 