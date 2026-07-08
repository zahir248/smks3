<?php
$page_title = 'Pelan Sekolah';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();

$pdo = getConnection();

/**
 * FETCH PELAN SEKOLAH
 */
$stmt = $pdo->query("
    SELECT * 
    FROM pelan_sekolah
    WHERE id = 1
    LIMIT 1
");

$pelan = $stmt->fetch(PDO::FETCH_ASSOC);

/**
 * IMAGE PATH
 */
$image = !empty($pelan['image'])
    ? 'images/pelan-sekolah/' . $pelan['image']
    : 'images/no-image.png';

require_once __DIR__ . '/includes/header.php';
?>

<style>

.zoom-img{
    transition:0.3s;
}

.zoom-img:hover{
    transform:scale(1.01);
}

/* ================= MODAL ================= */

.modal-content{
    background:rgba(0,0,0,0.95);
}

#modal-img{
    max-width:100%;
    max-height:100vh;
    transition:transform 0.1s ease;
    user-select:none;
}

.btn-close{
    filter:invert(1);
    opacity:1;
}

/* ================= MOBILE ================= */

@media(max-width:768px){

    #modal-img{
        width:100%;
        height:auto;
    }
}

</style>

<!-- ================= PELAN SEKOLAH ================= -->

<section class="page-section">

    <div class="container">

        <h2 class="text-center fw-bold mb-5">
            Pelan Bangunan Sekolah
        </h2>

        <div class="text-center">

            <!-- IMAGE -->
            <img 
                src="<?= htmlspecialchars($image) ?>"
                alt="Pelan Sekolah Menengah Kebangsaan Seremban 3"
                class="img-fluid shadow rounded zoom-img"
                style="cursor:pointer; max-width:100%; height:auto;"
                data-bs-toggle="modal"
                data-bs-target="#zoomModal"
            >

        </div>

        <p class="text-center mt-4 text-muted">
            Klik gambar untuk lihat lebih jelas.
            Gunakan scroll atau pinch untuk zoom,
            dan drag untuk gerakkan gambar.
        </p>

    </div>

</section>

<!-- ================= MODAL ================= -->

<div 
    class="modal fade"
    id="zoomModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content border-0">

            <div class="modal-body d-flex justify-content-center align-items-center position-relative overflow-hidden p-0">

                <!-- CLOSE BUTTON -->
                <button 
                    type="button"
                    class="btn-close position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    style="z-index:999;"
                ></button>

                <!-- IMAGE -->
                <img 
                    src="<?= htmlspecialchars($image) ?>"
                    alt="Pelan Sekolah"
                    id="modal-img"
                    style="cursor:grab;"
                >

            </div>

        </div>

    </div>

</div>

<!-- ================= ZOOM SCRIPT ================= -->

<script>

const modalImg = document.getElementById('modal-img');

let scale = 1;
let originX = 0;
let originY = 0;

let startX = 0;
let startY = 0;

let isDragging = false;

/* ================= DESKTOP ZOOM ================= */

modalImg.addEventListener('wheel', function(e){

    e.preventDefault();

    if(e.deltaY < 0){

        scale += 0.1;

    } else {

        scale = Math.max(0.5, scale - 0.1);
    }

    updateTransform();

});

/* ================= DRAG ================= */

modalImg.addEventListener('mousedown', function(e){

    isDragging = true;

    startX = e.clientX - originX;
    startY = e.clientY - originY;

    modalImg.style.cursor = 'grabbing';

});

document.addEventListener('mouseup', function(){

    isDragging = false;

    modalImg.style.cursor = 'grab';

});

document.addEventListener('mousemove', function(e){

    if(!isDragging) return;

    originX = e.clientX - startX;
    originY = e.clientY - startY;

    updateTransform();

});

/* ================= MOBILE PINCH ZOOM ================= */

let initialDistance = null;

modalImg.addEventListener('touchstart', function(e){

    if(e.touches.length === 2){

        initialDistance = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
    }

}, { passive:false });

modalImg.addEventListener('touchmove', function(e){

    if(e.touches.length === 2 && initialDistance){

        e.preventDefault();

        let currentDistance = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );

        scale *= currentDistance / initialDistance;

        initialDistance = currentDistance;

        updateTransform();
    }

}, { passive:false });

/* ================= UPDATE TRANSFORM ================= */

function updateTransform(){

    modalImg.style.transform =
        `translate(${originX}px, ${originY}px) scale(${scale})`;
}

/* ================= RESET WHEN CLOSE ================= */

const zoomModal = document.getElementById('zoomModal');

zoomModal.addEventListener('hidden.bs.modal', function(){

    scale = 1;
    originX = 0;
    originY = 0;

    updateTransform();
});

</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>