<?php
$page_title = 'Sejarah Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>
<?php
require_once __DIR__ . '/config/database.php';
$pdo = getConnection();

$stmt = $pdo->query("SELECT * FROM sejarah_sekolah ORDER BY id DESC");
$sejarahList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Sejarah SMK Seremban 3</h2>
        <div class="timeline">
            <?php if(!empty($sejarahList)): ?>
                <?php foreach($sejarahList as $row): ?>
                    <div class="timeline-item fade-in">
                        <h5 class="fw-bold">
                            <?= htmlspecialchars($row['tajuk']) ?>
                        </h5><br>
                        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    </div>
                    <hr class="divider">
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Tiada sejarah lagi.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Timeline Cards */
.timeline-item {
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 2rem;
    transition: transform 0.3s, box-shadow 0.3s;
}
.timeline-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Divider line */
.divider {
    border-top: 1px solid #e2e8f0;
    margin: 1rem 0;
}

/* Fade-in Animation */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
const faders = document.querySelectorAll('.fade-in');
const options = { threshold: 0.2 };
const appearOnScroll = new IntersectionObserver(function(entries, observer) {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add('show');
            observer.unobserve(entry.target);
        }
    });
}, options);

faders.forEach(fader => { appearOnScroll.observe(fader); });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

