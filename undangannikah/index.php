<?php
require_once 'config/database.php';

// Ambil data wedding info
try {
    $stmt = $pdo->query("SELECT * FROM wedding_info WHERE id = 1");
    $wedding_info = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($wedding_info['groom_name']); ?> & <?php echo htmlspecialchars($wedding_info['bride_name']); ?> Wedding</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
    header {
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                    url('images/header-bg.jpg') no-repeat center center;
        background-size: cover;
        background-attachment: fixed;
        position: relative;
        overflow: hidden;
    }

    header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 150px;
        background: linear-gradient(to top, #f9f7f7 0%, rgba(249, 247, 247, 0) 100%);
    }

    .header-content {
        position: relative;
        z-index: 2;
        padding: 250px 0;
    }

    .header-ornament {
        position: absolute;
        width: 300px;
        height: 300px;
        background: url('https://raw.githubusercontent.com/your-username/your-repo/main/images/ornament.png') no-repeat center center;
        background-size: contain;
        opacity: 0.1;
    }

    .header-ornament.top-left {
        top: 0;
        left: 0;
        transform: rotate(-45deg);
    }

    .header-ornament.bottom-right {
        bottom: 0;
        right: 0;
        transform: rotate(135deg);
    }

    .couple-names {
        font-size: 5rem;
        margin-bottom: 30px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .date-badge {
        background: rgba(255,255,255,0.95);
        color: #2c3e50;
        padding: 20px 40px;
        border-radius: 50px;
        display: inline-block;
        margin-top: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .date-badge:hover {
        transform: translateY(-5px);
    }

    .wedding-intro {
        font-size: 2.5rem;
        color: rgba(255,255,255,0.9);
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    </style>
</head>
<body>
    <!-- Admin Button -->
    <div class="admin-button">
        <a href="admin/" class="btn-admin">
            <i class="fas fa-user-shield"></i>
        </a>
    </div>

    <!-- Header -->
    <header>
        <div class="header-ornament top-left"></div>
        <div class="header-ornament bottom-right"></div>
        <div class="header-content text-center">
            <p class="wedding-intro romantic-font">The Wedding of</p>
            <h1 class="couple-names romantic-font">
                <?php echo htmlspecialchars($wedding_info['groom_name']); ?> 
                &amp; 
                <?php echo htmlspecialchars($wedding_info['bride_name']); ?>
            </h1>
            
            <div class="wedding-date-section">
                <div class="date-divider"></div>
                <div class="date-content">
                    <div class="date-primary"><?php echo date('l', strtotime($wedding_info['wedding_date'])); ?></div>
                    <div class="date-secondary">
                        <?php echo date('d | m | Y', strtotime($wedding_info['wedding_date'])); ?>
                    </div>
                    <div class="ceremony-time">
                        <div class="ceremony-item">
                            <span class="ceremony-title">Akad Nikah</span>
                            <span class="ceremony-hour"><?php echo date('H:i', strtotime($wedding_info['akad_time'])); ?> WIB</span>
                        </div>
                        <div class="ceremony-divider"></div>
                        <div class="ceremony-item">
                            <span class="ceremony-title">Resepsi</span>
                            <span class="ceremony-hour"><?php echo date('H:i', strtotime($wedding_info['reception_time'])); ?> WIB</span>
                        </div>
                    </div>
                </div>
                <div class="date-divider"></div>
            </div>
        </div>
    </header>

    <!-- Countdown -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="romantic-font">Menghitung Hari</h2>
                <div class="divider"></div>
            </div>
            <div class="row countdown-container">
                <div class="col-3 countdown-box">
                    <div class="countdown-number" id="days">00</div>
                    <div class="countdown-label">Hari</div>
                </div>
                <div class="col-3 countdown-box">
                    <div class="countdown-number" id="hours">00</div>
                    <div class="countdown-label">Jam</div>
                </div>
                <div class="col-3 countdown-box">
                    <div class="countdown-number" id="minutes">00</div>
                    <div class="countdown-label">Menit</div>
                </div>
                <div class="col-3 countdown-box">
                    <div class="countdown-number" id="seconds">00</div>
                    <div class="countdown-label">Detik</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Couple -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="romantic-font">Mempelai</h2>
                <div class="divider"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="couple-card text-center">
                        <h3 class="couple-name romantic-font mb-3"><?php echo htmlspecialchars($wedding_info['groom_name']); ?></h3>
                        <p class="mb-2">Putra dari</p>
                        <p class="parents-name"><?php echo htmlspecialchars($wedding_info['groom_parents']); ?></p>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <div class="couple-separator romantic-font">&amp;</div>
                </div>
                <div class="col-md-5">
                    <div class="couple-card text-center">
                        <h3 class="couple-name romantic-font mb-3"><?php echo htmlspecialchars($wedding_info['bride_name']); ?></h3>
                        <p class="mb-2">Putri dari</p>
                        <p class="parents-name"><?php echo htmlspecialchars($wedding_info['bride_parents']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2 class="romantic-font">Galeri Foto</h2>
                <div class="divider"></div>
            </div>
            <div class="row g-4">
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC");
                    $gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($gallery_images as $image):
                ?>
                <div class="col-md-4">
                    <div class="gallery-item">
                        <div class="gallery-image-wrapper">
                            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                                 alt="Gallery Image">
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                } catch(PDOException $e) {
                    echo "<div class='alert alert-danger'>Gagal memuat galeri foto</div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2 class="romantic-font">Lokasi Acara</h2>
                <div class="divider"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card location-card">
                        <div class="card-body text-center">
                            <i class="fas fa-map-marker-alt mb-3" style="font-size: 2rem; color: #e74c3c;"></i>
                            <h3 class="h4 mb-3"><?php echo htmlspecialchars($wedding_info['venue_name']); ?></h3>
                            <p class="mb-3"><?php echo nl2br(htmlspecialchars($wedding_info['venue_address'])); ?></p>
                            <div class="schedule mb-4">
                                <p><strong>Akad Nikah:</strong> <?php echo date('H:i', strtotime($wedding_info['akad_time'])); ?> WIB</p>
                                <p><strong>Resepsi:</strong> <?php echo date('H:i', strtotime($wedding_info['reception_time'])); ?> WIB</p>
                            </div>
                            <a href="https://www.google.com/maps/search/<?php echo urlencode($wedding_info['venue_name'] . ' ' . $wedding_info['venue_address']); ?>" 
                               target="_blank" 
                               class="btn btn-submit"
                               rel="noopener noreferrer">
                                <i class="fas fa-directions me-2"></i>Petunjuk Arah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RSVP Form -->
    <section class="py-5" id="rsvp-form">
        <div class="container">
            <div class="section-title">
                <h2 class="romantic-font">Konfirmasi Kehadiran</h2>
                <div class="divider"></div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php if (isset($_GET['rsvp'])): ?>
                        <?php if ($_GET['rsvp'] == 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Terima kasih! Konfirmasi kehadiran Anda telah kami terima.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php elseif ($_GET['rsvp'] == 'error'): ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Maaf, terjadi kesalahan. Silakan coba lagi.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="rsvp-form">
                        <form action="process_rsvp.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Kehadiran</label>
                                    <select class="form-select" name="attendance_status" required>
                                        <option value="Hadir">Hadir</option>
                                        <option value="Tidak Hadir">Tidak Hadir</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jumlah Tamu</label>
                                    <input type="number" class="form-control" name="number_of_guests" min="1" value="1">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Ucapan & Doa</label>
                                <textarea class="form-control" name="message" rows="4"></textarea>
                            </div>
                            <button type="submit" class="btn btn-submit w-100">Kirim Konfirmasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <h3 class="romantic-font mb-4">
                <?php echo htmlspecialchars($wedding_info['groom_name']); ?> 
                &amp; 
                <?php echo htmlspecialchars($wedding_info['bride_name']); ?>
            </h3>
            <p>&copy; <?php echo date('Y'); ?> 
               <?php echo htmlspecialchars($wedding_info['groom_name']); ?> &amp; 
               <?php echo htmlspecialchars($wedding_info['bride_name']); ?> Wedding. 
               All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html> 