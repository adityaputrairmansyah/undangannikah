<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

// Proses upload foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    try {
        $upload_dir = "../images/gallery/";
        
        // Buat direktori jika belum ada
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        // Validasi tipe file
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception("Tipe file tidak diizinkan. Gunakan JPG, JPEG, atau PNG.");
        }
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Simpan info file ke database
            $stmt = $pdo->prepare("INSERT INTO gallery (image_path, caption, sort_order) VALUES (?, ?, ?)");
            $stmt->execute([
                'images/gallery/' . $new_filename,
                $_POST['caption'] ?? '',
                $_POST['sort_order'] ?? 0
            ]);
            header("Location: gallery.php?status=success");
            exit;
        }
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

// Hapus foto
if (isset($_POST['delete_image'])) {
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->execute([$_POST['delete_image']]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($image && file_exists('../' . $image['image_path'])) {
            unlink('../' . $image['image_path']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$_POST['delete_image']]);
        
        header("Location: gallery.php?status=deleted");
        exit;
    } catch(PDOException $e) {
        $error = "Gagal menghapus foto";
    }
}

// Ambil data galeri
try {
    $stmt = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC");
    $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Gagal mengambil data galeri";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .gallery-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .gallery-item {
            position: relative;
            margin-bottom: 30px;
        }
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
        }
        .gallery-item:hover .gallery-actions {
            display: block;
        }
        .gallery-caption {
            margin-top: 10px;
            font-size: 0.9rem;
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
                        <a class="nav-link" href="wedding_settings.php">
                            <i class="bi bi-gear"></i> Pengaturan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gallery.php">
                            <i class="bi bi-images"></i> Galeri
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
        <div class="gallery-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Galeri Foto</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-plus-lg"></i> Tambah Foto
                </button>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'success'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Foto berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($_GET['status'] == 'deleted'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Foto berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <?php foreach($gallery as $image): ?>
                <div class="col-md-4">
                    <div class="gallery-item">
                        <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" alt="Gallery Image">
                        <div class="gallery-actions">
                            <form action="" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                <input type="hidden" name="delete_image" value="<?php echo $image['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        <?php if ($image['caption']): ?>
                        <div class="gallery-caption">
                            <?php echo htmlspecialchars($image['caption']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Foto</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan (opsional)</label>
                            <input type="text" class="form-control" name="caption">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 