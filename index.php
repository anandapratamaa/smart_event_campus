<?php
require_once 'config.php';

$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC, event_time ASC";
$result = $conn->query($sql);
$total_all = $conn->query("SELECT COUNT(*) as t FROM events")->fetch_assoc()['t'];
$total_upcoming = $result->num_rows;

// Fetch Announcements
$announcements = $conn->query("SELECT * FROM announcements WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3");
// Fetch News
$news = $conn->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Event Campus - Premium Edition</title>
    <meta name="description" content="Pusat informasi kegiatan kampus - Seminar, Workshop, Lomba, dan Pelatihan terkini.">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .search-section { margin-bottom: 2.5rem; }
        .search-bar { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        .search-input-wrap { flex: 1; position: relative; min-width: 200px; }
        .search-input-wrap i { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .search-input-wrap input { width: 100%; padding: 0.85rem 1rem 0.85rem 3rem; background: rgba(30,41,59,0.7); border: 1px solid var(--border-color); border-radius: 0.75rem; color: #fff; font-size: 1rem; font-family: 'Outfit', sans-serif; transition: all 0.3s; }
        .search-input-wrap input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(16,185,129,0.12); }
        .search-input-wrap input::placeholder { color: var(--text-muted); }
        .filter-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .filter-btn { padding: 0.6rem 1.2rem; border-radius: 2rem; border: 1px solid var(--border-color); background: transparent; color: var(--text-muted); cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 0.9rem; font-weight: 500; transition: all 0.3s; }
        .filter-btn:hover, .filter-btn.active { background: var(--primary); border-color: var(--primary); color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.4); }
        .hero-stats { display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap; }
        .hero-stat { text-align: center; }
        .hero-stat-value { font-size: 2rem; font-weight: 800; color: #fff; }
        .hero-stat-label { font-size: 0.8rem; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.05em; }
        #loading-indicator { text-align: center; padding: 3rem; grid-column: 1/-1; display: none; }
        #loading-indicator .spinner { width: 40px; height: 40px; border: 3px solid rgba(255,255,255,0.1); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 1rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            <svg class="logo-img" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 32px; height: 32px;">
                <rect width="100" height="100" rx="20" fill="url(#paint0_linear)"/>
                <path d="M50 25L20 40L50 55L80 40L50 25Z" fill="white"/>
                <path d="M20 55V70L50 85L80 70V55L50 70L20 55Z" fill="white" fill-opacity="0.85"/>
                <defs>
                    <linearGradient id="paint0_linear" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#10b981"/>
                        <stop offset="0.5" stop-color="#059669"/>
                        <stop offset="1" stop-color="#f59e0b"/>
                    </linearGradient>
                </defs>
            </svg>
            Smart Event Campus
        </a>
        <button class="menu-toggle" onclick="document.getElementById('navLinks').classList.toggle('active')">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="jadwal_sholat.php" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);"><i class="fa-solid fa-mosque"></i> Jadwal Sholat</a>
            <a href="login.php" class="btn btn-primary"><i class="fa-solid fa-user-shield"></i> Admin Login</a>
        </div>
    </nav>

    <div class="container">
        <div class="hero glass">
            <h1>Eksplorasi Kegiatan Kampus</h1>
            <p>Pusat informasi kegiatan mahasiswa terkini, Seminar, Workshop, Lomba, dan Pelatihan eksklusif di kampus.</p>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-value"><?= $total_all ?></div>
                    <div class="hero-stat-label">Total Kegiatan</div>
                </div>
                <div class="hero-stat" style="border-left: 1px solid rgba(255,255,255,0.2); padding-left: 2rem;">
                    <div class="hero-stat-value"><?= $total_upcoming ?></div>
                    <div class="hero-stat-label">Akan Datang</div>
                </div>
            </div>
        </div>

        <!-- CS BANNER -->
        <div style="background:linear-gradient(135deg,rgba(16,185,129,0.15),rgba(245,158,11,0.12));border:1px solid rgba(16,185,129,0.35);border-radius:1rem;padding:1.25rem 2rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#10b981,#f59e0b);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;box-shadow:0 4px 15px rgba(16,185,129,0.4);">
                    <i class="fa-solid fa-headset" style="color:#fff;-webkit-text-fill-color:#fff;"></i>
                </div>
                <div>
                    <p style="color:#ecfdf5;font-weight:700;font-size:1rem;margin:0;-webkit-text-fill-color:#ecfdf5;">Customer Service 24/7</p>
                    <p style="color:#6ee7b7;font-size:0.85rem;margin:0;-webkit-text-fill-color:#6ee7b7;">Ada pertanyaan tentang pendaftaran? Gunakan Ticket ID untuk chat dengan kami.</p>
                </div>
            </div>
            <a href="bantuan_support.php" style="display:inline-flex;align-items:center;gap:0.6rem;padding:0.8rem 1.5rem;background:linear-gradient(135deg,#10b981,#059669);color:#fff;-webkit-text-fill-color:#fff;border-radius:0.75rem;text-decoration:none;font-weight:700;font-size:0.95rem;white-space:nowrap;box-shadow:0 4px 20px rgba(16,185,129,0.45);transition:all 0.3s;">
                <i class="fa-solid fa-comments" style="-webkit-text-fill-color:#fff;"></i> Chat CS Sekarang
            </a>
        </div>

        <?php if($announcements->num_rows > 0): ?>
        <div style="background: rgba(99,102,241,0.1); border: 1px solid var(--primary); border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem;">
            <h3 style="color: #fff; margin-bottom: 1rem; display:flex; align-items:center; gap:0.5rem;"><i class="fa-solid fa-bullhorn" style="color:var(--primary);"></i> Pengumuman Penting</h3>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <?php while($ann = $announcements->fetch_assoc()): ?>
                    <div style="background:rgba(255,255,255,0.05); padding:1rem; border-radius:0.75rem;">
                        <div style="font-weight:bold; color:var(--primary); margin-bottom:0.25rem;"><?php echo htmlspecialchars($ann['title']); ?></div>
                        <div style="color:#cbd5e1; font-size:0.9rem;"><?php echo htmlspecialchars($ann['content']); ?></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($news->num_rows > 0): ?>
        <h2 style="margin-bottom: 1.5rem; color: #fff; font-size: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fa-regular fa-newspaper" style="color: var(--secondary);"></i>
            <span>Berita Kampus Terkini</span>
        </h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.5rem; margin-bottom:2.5rem;">
            <?php while($n = $news->fetch_assoc()): ?>
                <div class="glass" style="border-radius:1rem; overflow:hidden;">
                    <?php if($n['image']): ?>
                        <img src="uploads/news/<?php echo $n['image']; ?>" style="width:100%; height:160px; object-fit:cover;">
                    <?php else: ?>
                        <div style="width:100%; height:160px; background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fa-regular fa-image" style="font-size:2rem;"></i></div>
                    <?php endif; ?>
                    <div style="padding:1.25rem;">
                        <h4 style="color:#fff; margin-bottom:0.5rem;"><?php echo htmlspecialchars($n['title']); ?></h4>
                        <p style="color:var(--text-muted); font-size:0.9rem;"><?php echo substr(htmlspecialchars($n['content']), 0, 80); ?>...</p>
                        <div style="color:var(--text-muted); font-size:0.8rem; margin-top:1rem;"><i class="fa-regular fa-clock"></i> <?php echo date("d M Y", strtotime($n['created_at'])); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <!-- LIVE SEARCH & FILTER -->
        <div class="search-section">
            <div class="search-bar">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Cari kegiatan... (nama, deskripsi, lokasi)" autocomplete="off">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="">Semua</button>
                    <button class="filter-btn" data-filter="Seminar"><i class="fa-solid fa-chalkboard-teacher"></i> Seminar</button>
                    <button class="filter-btn" data-filter="Workshop"><i class="fa-solid fa-screwdriver-wrench"></i> Workshop</button>
                    <button class="filter-btn" data-filter="Lomba"><i class="fa-solid fa-trophy"></i> Lomba</button>
                    <button class="filter-btn" data-filter="Pelatihan"><i class="fa-solid fa-dumbbell"></i> Pelatihan</button>
                </div>
            </div>
        </div>



        <h2 style="margin-bottom: 2rem; color: #fff; font-size: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fa-solid fa-calendar-check" style="color: var(--primary);"></i>
            <span id="section-title">Kegiatan Mendatang</span>
        </h2>

        <div class="events-grid" id="events-container">
            <div id="loading-indicator">
                <div class="spinner"></div>
                <p style="color: var(--text-muted);">Mencari kegiatan...</p>
            </div>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $date = date("d F Y", strtotime($row['event_date']));
                    $time = date("H:i", strtotime($row['event_time']));

                    // Google Calendar URL
                    $cal_start = date("Ymd\THis\Z", strtotime($row['event_date'] . ' ' . $row['event_time']));
                    $cal_end   = date("Ymd\THis\Z", strtotime($row['event_date'] . ' ' . $row['event_time']) + 7200);
                    $cal_url   = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($row['title']) . "&dates={$cal_start}/{$cal_end}&details=" . urlencode($row['description']) . "&location=" . urlencode($row['location']);

                    echo '<div class="event-card glass" style="display: flex; flex-direction: column;">';
                    if (!empty($row['poster'])) {
                        echo '<img src="uploads/posters/' . htmlspecialchars($row['poster']) . '" alt="Poster" style="width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid var(--border-color);">';
                    }
                    echo '<div class="event-content" style="flex: 1; display: flex; flex-direction: column;">';
                    echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">';
                    echo '<span class="event-badge">' . htmlspecialchars($row['event_type']) . '</span>';
                    if($row['is_paid']) {
                        echo '<span style="color:#10b981; font-weight:bold; font-size:1.1rem;">Rp ' . number_format($row['price'],0,',','.') . '</span>';
                    } else {
                        echo '<span style="color:#10b981; font-weight:bold; font-size:1.1rem;">GRATIS</span>';
                    }
                    echo '</div>';
                    
                    echo '<h3 class="event-title">' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<div class="event-meta">';
                    echo '<span><i class="fa-regular fa-calendar"></i> ' . $date . '</span>';
                    echo '<span><i class="fa-regular fa-clock"></i> ' . $time . ' WIB</span>';
                    echo '<span><i class="fa-solid fa-location-dot"></i> ' . htmlspecialchars($row['location']) . '</span>';
                    if($row['max_participants'] > 0) {
                        echo '<span><i class="fa-solid fa-users"></i> Kuota: ' . $row['max_participants'] . '</span>';
                    }
                    echo '</div>';
                    echo '<p class="event-desc" style="flex: 1;">' . htmlspecialchars($row['description']) . '</p>';
                    
                    echo '<div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">';
                    echo '<a href="event_detail.php?id=' . $row['id'] . '" class="btn btn-primary" style="flex: 100%; justify-content: center;"><i class="fa-solid fa-file-lines"></i> Lihat Detail Acara</a>';
                    if (!empty($row['maps_url'])) {
                        echo '<a href="' . htmlspecialchars($row['maps_url']) . '" target="_blank" class="btn btn-outline" style="flex: 1; justify-content: center; border-color: var(--secondary); color: var(--secondary);"><i class="fa-solid fa-map-location-dot"></i> Maps</a>';
                    }
                    echo '<a href="' . $cal_url . '" target="_blank" class="btn btn-outline" style="flex: 1; justify-content: center; border-color: #06b6d4; color: #06b6d4;"><i class="fa-solid fa-calendar-plus"></i> Kalender</a>';
                    if (!empty($row['material_file'])) {
                        echo '<a href="uploads/materials/' . htmlspecialchars($row['material_file']) . '" target="_blank" class="btn btn-outline" style="flex: 100%; justify-content: center; border-color: var(--success); color: var(--success); margin-top: 0.5rem;"><i class="fa-solid fa-download"></i> Unduh Materi</a>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="glass" style="grid-column: 1 / -1; text-align: center; padding: 4rem; border-radius: 1rem;"><i class="fa-solid fa-box-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem; display: block;"></i><p style="font-size: 1.25rem; color: var(--text-muted);">Belum ada kegiatan yang dijadwalkan.</p></div>';
            }
            ?>
        </div>
    </div>

    <footer>
        <p>Created by: SukaCoding</p>
        <p>&copy; <?php echo date("Y"); ?> Smart Event Campus - All rights reserved</p>
    </footer>

    <!-- Floating CS Button -->
    <a href="bantuan_support.php" title="Hubungi Customer Service" style="position:fixed;bottom:2rem;right:2rem;background:linear-gradient(135deg,#10b981,#f59e0b);color:#fff;width:65px;height:65px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 6px 25px rgba(16,185,129,0.6);cursor:pointer;z-index:9999;text-decoration:none;animation:csFloat 2s ease-in-out infinite;">
        <i class="fa-solid fa-headset"></i>
    </a>
    <style>
    @keyframes csFloat {
        0%,100%{transform:translateY(0);box-shadow:0 6px 25px rgba(16,185,129,0.6);}
        50%{transform:translateY(-8px);box-shadow:0 14px 30px rgba(99,102,241,0.8);}
    }
    </style>

    <script>
    let searchTimeout = null;
    let activeFilter = '';

    const searchInput = document.getElementById('searchInput');
    const container = document.getElementById('events-container');
    const loading = document.getElementById('loading-indicator');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sectionTitle = document.getElementById('section-title');

    function fetchEvents() {
        const search = searchInput.value.trim();
        const params = new URLSearchParams({ search, filter: activeFilter });

        loading.style.display = 'block';
        Array.from(container.children).forEach(c => { if (c !== loading) c.style.display = 'none'; });

        fetch('search_events.php?' + params.toString())
            .then(res => res.text())
            .then(html => {
                Array.from(container.children).forEach(c => { if (c !== loading) c.remove(); });
                loading.style.display = 'none';
                container.insertAdjacentHTML('beforeend', html);
                sectionTitle.textContent = (search || activeFilter) ? 'Hasil Pencarian' : 'Kegiatan Mendatang';
            })
            .catch(() => { loading.style.display = 'none'; });
    }

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchEvents, 400);
    });

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            fetchEvents();
        });
    });


    </script>
</body>
</html>
