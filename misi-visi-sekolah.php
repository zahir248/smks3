<?php
$page_title = 'FPK, Visi, Misi, Motto Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero text-white py-5" style="background: linear-gradient(145deg, #0B3C5D, #0d4a73, #082a42); position: relative; overflow: hidden;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Falsafah, Visi & Misi Sekolah</h1>
                <p class="lead">Panduan pendidikan dan aspirasi SMK Seremban 3 untuk melahirkan insan seimbang, bertanggungjawab dan berilmu.</p>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-book-half text-white opacity-50" style="font-size: 8rem;"></i>
            </div>
        </div>
    </div>
</section>

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
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative">
                    <i class="bi bi-eye fs-2 mb-2"></i>
                    <h5 class="fw-bold">Visi Sekolah</h5>
                    <p>“<strong>PENDIDIKAN BERKUALITI – INSAN TERDIDIK, NEGARA SEJAHTERA</strong>”</p>
                    <hr class="divider">
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative">
                    <i class="bi bi-gear fs-2 mb-2"></i>
                    <h5 class="fw-bold">Misi Sekolah</h5>
                    <p>“<strong>MELESTARIKAN SISTEM PENDIDIKAN BERKUALITI</strong> untuk membangunkan potensi individu bagi memenuhi aspirasi negara”</p>
                    <hr class="divider">
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative">
                    <i class="bi bi-lightbulb fs-2 mb-2"></i>
                    <h5 class="fw-bold">Motto Sekolah</h5>
                    <p>“<strong>WHEN VISION IS CLEAR, ACTION BECOME EASIER</strong>”</p>
                    <hr class="divider">
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-in">
                <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative">
                    <i class="bi bi-journal-text fs-2 mb-2"></i>
                    <h5 class="fw-bold">Pelan Pembangunan Pendidikan Malaysia</h5>
                    <p>Menekankan pembentukan pelajar <strong>seimbang dari segi intelek, rohani, emosi dan jasmani</strong> untuk memenuhi aspirasi pendidikan negara.</p>
                    <hr class="divider">
                </div>
            </div>
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

/* Divider line */
.divider {
    border-top: 1px solid #e2e8f0;
    margin: 1rem 0 0 0;
}

/* Scroll animation JS */
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