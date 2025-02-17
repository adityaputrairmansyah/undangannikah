<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

// Ambil data wedding info
try {
    $stmt = $pdo->query("SELECT * FROM wedding_info WHERE id = 1");
    $wedding_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE wedding_info SET 
            groom_name = ?, groom_parents = ?,
            bride_name = ?, bride_parents = ?,
            wedding_date = ?, akad_time = ?, reception_time = ?,
            venue_name = ?, venue_address = ?, maps_link = ?
            WHERE id = 1
        ");
        
        $stmt->execute([
            $_POST['groom_name'],
            $_POST['groom_parents'],
            $_POST['bride_name'],
            $_POST['bride_parents'],
            $_POST['wedding_date'],
            $_POST['akad_time'],
            $_POST['reception_time'],
            $_POST['venue_name'],
            $_POST['venue_address'],
            $_POST['maps_link']
        ]);
        
        // Redirect ke halaman utama undangan
        header("Location: ../");
        exit;
    } catch(PDOException $e) {
        $error = "Terjadi kesalahan saat menyimpan data";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Undangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
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
                        <a class="nav-link" href="konfirmasi.php">
                            <i class="bi bi-people"></i> Daftar Tamu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="wedding_settings.php">
                            <i class="bi bi-gear"></i> Pengaturan
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="mb-4">Pengaturan Undangan</h2>
                    
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data berhasil disimpan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Data Mempelai Pria</h5>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="groom_name" 
                                           value="<?php echo htmlspecialchars($wedding_info['groom_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Orang Tua</label>
                                    <input type="text" class="form-control" name="groom_parents" 
                                           value="<?php echo htmlspecialchars($wedding_info['groom_parents']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Data Mempelai Wanita</h5>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="bride_name" 
                                           value="<?php echo htmlspecialchars($wedding_info['bride_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Orang Tua</label>
                                    <input type="text" class="form-control" name="bride_parents" 
                                           value="<?php echo htmlspecialchars($wedding_info['bride_parents']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Waktu & Tempat</h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="wedding_date" 
                                       value="<?php echo $wedding_info['wedding_date']; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Waktu Akad</label>
                                <input type="time" class="form-control" name="akad_time" 
                                       value="<?php echo $wedding_info['akad_time']; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Waktu Resepsi</label>
                                <input type="time" class="form-control" name="reception_time" 
                                       value="<?php echo $wedding_info['reception_time']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Tempat</label>
                            <input type="text" class="form-control" name="venue_name" 
                                   value="<?php echo htmlspecialchars($wedding_info['venue_name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="venue_address" rows="3" required><?php 
                                echo htmlspecialchars($wedding_info['venue_address']); 
                            ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Link Google Maps Embed</label>
                            <input type="text" class="form-control" name="maps_link" 
                                   value="<?php echo htmlspecialchars($wedding_info['maps_link']); ?>" required>
                            <small class="form-text text-muted">
                                Cara mendapatkan link embed Google Maps:
                                <ol class="mt-1">
                                    <li>Buka Google Maps dan cari lokasi acara</li>
                                    <li>Klik tombol "Share" atau "Bagikan"</li>
                                    <li>Pilih tab "Embed a map" atau "Sematkan peta"</li>
                                    <li>Klik "COPY HTML"</li>
                                    <li>Dari kode yang disalin, salin URL yang ada di dalam tanda kutip setelah src=</li>
                                    <li>URL harus dimulai dengan "https://www.google.com/maps/embed?pb="</li>
                                    <li>Contoh lengkap: https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966...</li>
                                </ol>
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 