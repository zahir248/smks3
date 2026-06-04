<?php
$page_title = 'Peraturan Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- PERATURAN SEKOLAH -->
<section class="py-5" style="background:#d8f9ff;">
    <div class="container">

        <!-- TITLE -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">Peraturan Sekolah</h2>
            <p class="text-muted">
                Berikut merupakan garis panduan dan peraturan yang perlu dipatuhi oleh semua pelajar 
                bagi memastikan disiplin dan suasana pembelajaran yang kondusif di sekolah.
            </p>
        </div>

        <!-- GAMBAR -->
        <div class="text-center">
            <img src="images/peraturansekolah1.jpeg" 
                 alt="Peraturan Sekolah"
                 class="img-fluid rounded shadow"
                 style="width:100%; max-width:1000px; cursor:pointer;"
                 onclick="window.open(this.src, '_blank')">
        </div>
        
        <div class="text-center">
            <img src="images/peraturansekolah2.jpeg" 
                 alt="Peraturan Sekolah"
                 class="img-fluid rounded shadow"
                 style="width:100%; max-width:1000px; cursor:pointer;"
                 onclick="window.open(this.src, '_blank')">
        </div>

        <!-- MESSAGE -->
        <div class="alert alert-warning text-center mt-4">
            Maklumat lanjut akan dikemaskini dari semasa ke semasa.
        </div>

    </div>
</section>

<!-- MODAL POPUP -->
<div id="imgModal" class="modal-img" onclick="closeModal(event)">
    <span class="close-btn" onclick="closeModal(event)">&times;</span>
    <img id="modalImage">
</div>

<!-- JS FUNCTION -->
<script>
function openModal(src) {
    document.getElementById('imgModal').style.display = 'flex';
    document.getElementById('modalImage').src = src;
}

function closeModal(e) {
    document.getElementById('imgModal').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
$page_title = 'Peraturan Sekolah';
require_once __DIR__ . '/includes/functions.php';

$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM peraturan_sekolah ORDER BY id ASC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5" style="background:#d8f9ff;">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Peraturan Sekolah</h2>
            <p class="text-muted">
                Berikut merupakan garis panduan peraturan sekolah.
            </p>
        </div>

        <!-- DISPLAY IMAGES -->
        <div class="row justify-content-center">
            <?php foreach($images as $img): ?>
                <div class="col-12 mb-4 text-center">
                    <img 
                        src="uploads/peraturan/<?= htmlspecialchars($img['image']) ?>"
                        class="img-fluid rounded shadow"
                        style="max-width:1000px; width:100%; cursor:pointer;"
                        onclick="openModal(this.src)"
                    >
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- MODAL -->
<div id="imgModal" class="modal-img" onclick="closeModal()">
    <img id="modalImage">
</div>

<style>
.modal-img{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.85);
    justify-content:center;
    align-items:center;
}

.modal-img img{
    max-width:90%;
    max-height:90%;
}
</style>

<script>
function openModal(src){
    document.getElementById('imgModal').style.display='flex';
    document.getElementById('modalImage').src=src;
}

function closeModal(){
    document.getElementById('imgModal').style.display='none';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>