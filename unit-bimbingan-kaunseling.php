<?php
$page_title = 'Unit Bimbingan & Kaunseling';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Pengenalan -->
<section class="py-5" id="pengenalan">
    <div class="container">
        <p class="text-center text-muted lead mb-3">Perkhidmatan bimbingan menyeluruh untuk pembangunan akademik, kerjaya, psikologi, kepimpinan, dan kesihatan mental pelajar.</p>
        <div class="text-center mb-4">
            <a href="#visi-misi" class="btn btn-outline-primary btn-sm me-2">Visi &amp; Misi</a>
            <a href="contact.php" class="btn btn-outline-primary btn-sm">Hubungi Kami</a>
        </div>
        <div class="text-center mb-5">
            <h2 class="fw-bold">Pengenalan Perkhidmatan Bimbingan & Kaunseling</h2>
        </div>
        <p>Perkhidmatan Bimbingan dan Kaunseling di sekolah bersifat menyeluruh dan meliputi program perkembangan, pencegahan serta pemulihan. Program perkembangan dan pencegahan lebih banyak dijalankan berbanding program pemulihan. Antara perkhidmatan yang disediakan ialah bidang akademik, kerjaya, psikologi dan kesihatan mental, kepimpinan, dan kaunseling individu / kelompok.</p>
    </div>
</section>

<!-- Visi & Misi -->
<section class="py-5 bg-light" id="visi-misi">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <h3 class="fw-bold">Visi</h3>
                <p>Mewujudkan iklim pendidikan yang kondusif, teraputik dan efektif dari aspek fizikal, perhubungan dan pengurusan berteraskan konsep perkhidmatan bimbingan dan kaunseling berkualiti secara menyeluruh dan berkesan demi kecemerlangan pelajar dan sekolah.</p>
            </div>
            <div class="col-md-6">
                <h3 class="fw-bold">Misi</h3>
                <p>Dengan penuh rasa tanggungjawab dan bersungguh-sungguh menyediakan perkhidmatan menolong bantu pelajar dari aspek pengayaan, perkembangan, pencegahan dan pemulihan untuk mempertingkatkan pengetahuan, ketrampilan dan konsep kendiri positif yang perlu dalam menghasilkan masyarakat ‘MADANI’ melalui perkhidmatan bimbingan dan kaunseling yang berkesan beramanah berteraskan falsafah pendidikan kebangsaan.</p>
            </div>
        </div>
    </div>
</section>

<!-- Falsafah -->
<section class="py-5" id="falsafah">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Falsafah</h3>
        <p>Bahawasanya setiap pelajar mempunyai potensi yang boleh digembleng secara optimum menerusi pengurusan menyeluruh perkhidmatan bimbingan dan kaunseling yang cekap, berkesan dan beramanah berteraskan sumber dalaman dan luaran bagi melahirkan pelajar yang seimbang dari aspek intelektual, jasmani, emosi dan rohani serta beriman dan beramal soleh.</p>
    </div>
</section>

<!-- Objektif / Tujuan -->
<section class="py-5 bg-light" id="objektif">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Objektif / Tujuan</h3>
        <ul>
            <li>Merujuk pada pamplet rasmi Bimbingan & Kaunseling (boleh ditambah secara dinamik nanti)</li>
        </ul>
    </div>
</section>

<!-- Fungsi Unit -->
<section class="py-5" id="fungsi">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Fungsi Unit Bimbingan & Kaunseling</h3>
        <ul>
            <?php 
            $fungsi_ubk = [
                "Menyedia rancangan tahunan program dan aktiviti perkhidmatan Bimbingan & Kaunseling.",
                "Mengenalpasti keperluan perkhidmatan Bimbingan & Kaunseling sekolah melalui kajian keperluan, soal selidik, temu bual dan perbincangan dengan pelajar, guru, pentadbiran, kakitangan sekolah, ibu bapa dan bekas pelajar.",
                "Merancang, mengawal selia dan mengemaskini rekod dan inventori.",
                "Mengelola dan melaksanakan aktiviti Unit Bimbingan Kaunseling Kelompok dan tunjuk ajar yang meransang perkembangan pelajar secara optimum.",
                "Mengumpul, menyediakan, dan menyebar maklumat UBK kepada semua pelajar.",
                "Merancang, melaksana dan mengawal selia perkhidmatan kaunseling individu secara profesional dan beretika.",
                "Merancang, melaksana dan mengawal selia aktiviti kemahiran belajar untuk semua pelajar.",
                "Merancang, melaksana dan menilai program pencegahan dadah, inhalan, rokok dan alkohol.",
                "Merancang, melaksana program peluang melanjutkan pelajaran di IPT dalam & luar negara.",
                "Memberi khidmat kaunseling krisis kepada pelajar, guru, kakitangan dan ibu bapa.",
                "Menjadi personel perhubungan dengan agensi luar berkaitan.",
                "Menjadi ahli jawatankuasa dalam Majlis Perancang, Disiplin, Lembaga/Badan Pengawas sekolah.",
                "Menjadi penyelaras dalam program mentor mentee."
            ];
            foreach($fungsi_ubk as $fungsi){
                echo "<li>$fungsi</li>";
            }
            ?>
        </ul>
    </div>
</section>

<!-- Carta Organisasi -->
<section class="py-5 bg-light" id="carta">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Carta Organisasi</h3>
        <div class="text-center">
            <a href="images/carta organisasi ubk 2026.jpg" target="_blank">
                <img src="images/carta organisasi ubk 2026.jpg" alt="Carta Organisasi UBK" class="img-fluid rounded shadow">
            </a>
        </div>
    </div>
</section>

<!-- Proses Perkhidmatan -->
<section class="py-5" id="proses">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Proses Perkhidmatan Bimbingan & Kaunseling/Komponen Perkhidmatan</h3>
        <div class="text-center">
            <a href="images/pamplet1.jpg" target="_blank">
                <img src="images/pamplet1.jpg" alt="Pamplet 1" class="img-fluid rounded shadow">
            </a>
        </div>
        <div class="text-center">
            <a href="images/pamplet2.jpg" target="_blank">
                <img src="images/pamplet2.jpg" alt="Pamplet 2" class="img-fluid rounded shadow">
            </a>
        </div>
    </div>
</section>

<!-- Aktiviti Unit -->
<section class="py-5" id="aktiviti">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Aktiviti Unit Bimbingan & Kaunseling</h3>
        <p class="text-center">[Rujuk Google Drive / dokumen aktiviti]</p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>