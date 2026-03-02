<?php
$page_title = 'Sejarah Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero text-white py-5" style="background: linear-gradient(145deg, #0B3C5D, #0d4a73, #082a42);">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Sejarah Sekolah</h1>
                <p class="lead">Menelusuri perjalanan awal penubuhan dan perkembangan SMK Seremban 3 hingga kini.</p>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-building display-1 opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Sejarah Penubuhan SMK Seremban 3</h2>
        <div class="timeline">
            <!-- 2013 Caretaker -->
            <div class="timeline-item fade-in">
                <h5 class="fw-bold">Mei 2013 – Penubuhan Sekolah</h5>
                <p>En Yazid bin Yusof dilantik sebagai caretaker dan guru pertama yang ditugaskan di Sekolah Menengah Kebangsaan Seremban 3. Pada 8 Mei 2013, Pn Noridayu binti Mohd Nordin melaporkan diri sebagai pembantu tadbir. En Jamaluddin bin Ahmad melaporkan diri sebagai Pengetua pada 28 Mei 2013.</p>
            </div>

            <hr class="divider">

            <!-- Mesyuarat guru -->
            <div class="timeline-item fade-in">
                <h5 class="fw-bold">14 Jun 2013 – Mesyuarat Guru Pertama</h5>
                <p>Mesyuarat guru kali pertama telah diadakan dan turut dihadiri oleh En Mohd Sah bin Zainal Abidin, Pegawai Pendidikan Daerah Seremban, serta beberapa pegawai PPD Seremban.</p>
            </div>

            <hr class="divider">

            <!-- Murid pertama -->
            <div class="timeline-item fade-in">
                <h5 class="fw-bold">17 Jun 2013 – Hari Pertama Persekolahan</h5>
                <p>Kumpulan murid pertama mendaftar seramai 142 orang (70 orang Tingkatan 1 & 72 orang Tingkatan 2). Kebanyakan murid berasal dari SMK Bukit Mewah dan SMK Mambau. Bilangan guru pada permulaan ialah 10 orang termasuk Pengetua.</p>
            </div>

            <hr class="divider">

            <!-- Perkembangan murid dan guru -->
            <div class="timeline-item fade-in">
                <h5 class="fw-bold">Akhir 2013 – Pertumbuhan Sekolah</h5>
                <p>Bilangan murid bertambah kepada 152 orang, manakala bilangan guru meningkat kepada 16 orang termasuk Pengetua, tiga Penolong Kanan, dua Guru Kanan Mata Pelajaran, seorang kaunselor, dan tiga staf sokongan.</p>
            </div>

            <hr class="divider">

            <!-- Sekarang -->
            <div class="timeline-item fade-in">
                <h5 class="fw-bold">Kini</h5>
                <p>SMK Seremban 3 kini mempunyai 644 murid, 43 guru serta 7 AKP. Pengetua pada masa kini ialah Pn Siamala a/p Govindan.</p>
            </div>
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
.hero .bi-building {
    font-size: 9rem; /* lebih besar dari 7rem */
    transition: transform 0.3s;
}

@media (max-width: 991px) {
    .hero .bi-building {
        font-size: 5rem; /* responsive untuk tablet & phone */
    }
}
.hero .bi-building {
    color: rgba(255,255,255,0.15);
    text-shadow: 0 4px 15px rgba(0,0,0,0.25);
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