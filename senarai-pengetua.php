<?php
$page_title = 'Senarai Pengetua';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php'; // guna connection sedia ada

$settings = getSettings();
$pdo = getConnection(); // <-- ini missing
// Ambil semua pengetua dari database, ASC supaya dari lama ke baru
$stmt = $pdo->query("SELECT * FROM pengetua ORDER BY start_year ASC");
$pengetua_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Reverse array supaya yang baru berada di bawah
$pengetua_list = array_reverse($pengetua_list);
require_once __DIR__ . '/includes/header.php';
?>
<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Garis Masa Pengetua SMK Seremban 3</h2>
        <div class="timeline">
            <?php foreach ($pengetua_list as $index => $p) : ?>
                <div class="timeline-item <?= $index % 2 == 0 ? 'left' : 'right' ?>">
                    <div class="timeline-icon"><i class="bi bi-person-circle"></i></div>
                    <div class="timeline-content">
                        <h5 class="fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                        <p class="text-muted mb-0"><?= htmlspecialchars($p['start_year']) ?> – <?= htmlspecialchars($p['end_year']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<style>
/* Timeline container */
.timeline {
    position: relative;
    margin: 2rem 0;
    padding: 0;
}

/* Vertical line */
.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--school-primary);
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* Timeline item */
.timeline-item {
    position: relative;
    width: 50%;
    padding: 1rem 2rem;
}
.timeline-item.left {
    left: 0;
    text-align: right;
}
.timeline-item.right {
    left: 50%;
    text-align: left;
}

/* Timeline icon */
.timeline-icon {
    position: absolute;
    top: 15px;
    width: 40px;
    height: 40px;
    background: var(--school-primary);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.timeline-item.left .timeline-icon {
    right: -20px;
}
.timeline-item.right .timeline-icon {
    left: -20px;
}

/* Timeline content box */
.timeline-content {
    background: #fff;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(11,60,93,0.08);
    display: inline-block;
}

/* Responsive */
@media (max-width: 767px) {
    .timeline::before { left: 20px; }
    .timeline-item {
        width: 100%;
        padding-left: 3rem;
        padding-right: 1rem;
        margin-bottom: 2rem;
    }
    .timeline-item.left, .timeline-item.right { text-align: left; left: 0; }
    .timeline-item.left .timeline-icon, .timeline-item.right .timeline-icon { left: 0; right: auto; }
}
</style>