<?php
$page_title = 'FPK, Visi, Misi, Motto Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';

// 🔹 Ambil semua FPK/Misi/Visi
require 'config/database.php';
$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM fpk_misi_visi ORDER BY id ASC");
$fpk_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Fungsi pilih ikon ikut kategori
function getIcon($kategori) {
    $kategori = strtolower($kategori);
    return match(true) {
        str_contains($kategori, 'visi') => 'bi-eye',
        str_contains($kategori, 'misi') => 'bi-gear',
        str_contains($kategori, 'motto') => 'bi-lightbulb',
        str_contains($kategori, 'pelan') => 'bi-journal-text',
        default => 'bi-journal-text',
    };
}
?>

<!-- Falsafah Pendidikan Kebangsaan -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Falsafah Pendidikan Kebangsaan</h2>
        <div class="card card-hover border-0 shadow-sm p-5 fade-in">
            <p style="font-size: 1.1rem; line-height: 1.7;">
                Pendidikan di Malaysia adalah suatu usaha berterusan ke arah lebih memperkembangkan <strong>potensi individu secara menyeluruh</strong> dan bersepadu untuk mewujudkan insan yang seimbang dan harmonis dari segi <strong>intelek, rohani, emosi dan jasmani</strong>, berdasarkan kepercayaan dan kepatuhan kepada Tuhan.
            </p>
            <p style="font-size: 1.1rem; line-height: 1.7;">
                Usaha ini adalah bagi melahirkan warganegara Malaysia yang <strong>berilmu pengetahuan, berketerampilan, berakhlak mulia, bertanggungjawab</strong> dan berkeupayaan mencapai kesejahteraan diri, serta memberi sumbangan terhadap keharmonian dan kemakmuran keluarga, masyarakat dan negara.
            </p>
        </div>
    </div>
</section>

<!-- Visi, Misi, Motto, Pelan -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach($fpk_rows as $row): 
                $icon = getIcon($row['kategori']);
            ?>
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative">
                        <i class="bi <?= $icon ?> fs-2 mb-2"></i>
                        <h5 class="fw-bold"><?= htmlspecialchars($row['kategori']) ?></h5>
                        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                        <hr class="divider">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}

.divider {
    border-top: 1px solid #e2e8f0;
    margin: 1rem 0 0 0;
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