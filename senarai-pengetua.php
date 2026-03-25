<?php
$page_title = 'Senarai Pengetua';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <p class="text-center text-muted lead mb-4">Sejak penubuhan, SMK Seremban 3 telah dipimpin oleh beberapa pengetua berwibawa.</p>
        <h2 class="text-center fw-bold mb-5">Timeline Pengetua SMK Seremban 3</h2>
        <div class="timeline">
            <?php
            $pengetua_list = [
                ['name' => 'Jamaluddin bin Ahmad', 'start' => '2013', 'end' => '2015'],
                ['name' => 'Amnah binti Alias', 'start' => '2015', 'end' => '2017'],
                ['name' => 'Shafie bin Mohd Noor', 'start' => '2017', 'end' => '2020'],
                ['name' => 'Roslan bin Mohd Zainal', 'start' => 'JAN 2020', 'end' => 'APRIL 2020'],
                ['name' => 'Mad Pazir bin Md Ahair', 'start' => '2020', 'end' => '2023'],
                ['name' => 'Siamala a/p Govindan', 'start' => '2023', 'end' => 'KINI'],
            ];
            foreach ($pengetua_list as $index => $p) :
            ?>
            <div class="timeline-item <?= $index % 2 == 0 ? 'left' : 'right' ?>">
                <div class="timeline-icon"><i class="bi bi-person-circle"></i></div>
                <div class="timeline-content">
                    <h5 class="fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                    <p class="text-muted mb-0"><?= htmlspecialchars($p['start']) ?> – <?= htmlspecialchars($p['end']) ?></p>
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