<?php
$fpk_falsafah = is_array($fpk_falsafah ?? null) ? $fpk_falsafah : smks3_get_fpk_falsafah();
$fpk_falsafah_title = (string) ($fpk_falsafah['title'] ?? 'Falsafah Pendidikan Kebangsaan');
$fpk_falsafah_content = (string) ($fpk_falsafah['content'] ?? '');
$fpk_falsafah_paras = preg_split("/\n\s*\n/", trim($fpk_falsafah_content)) ?: [];
if ($fpk_falsafah_paras === ['']) {
    $fpk_falsafah_paras = [];
}
?>
<section class="page-section">
    <div class="container">
        <div class="position-relative"
             <?php if ($is_editor): ?>
             data-edit-block="fpk_falsafah"
             data-edit-label="Sunting Falsafah Pendidikan Kebangsaan"
             data-edit-hint="Ubah tajuk dan teks falsafah, kemudian simpan."
             data-title="<?= htmlspecialchars($fpk_falsafah_title, ENT_QUOTES, 'UTF-8') ?>"
             data-content="<?= htmlspecialchars($fpk_falsafah_content, ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="text-center fw-bold mb-4" data-bind="fpk_falsafah_title"><?= htmlspecialchars($fpk_falsafah_title) ?></h2>
            <div class="card card-hover border-0 shadow-sm p-5 fade-in" data-bind="fpk_falsafah_content">
                <?php foreach ($fpk_falsafah_paras as $para): ?>
                    <p style="font-size: 1.1rem; line-height: 1.7;"><?= nl2br(htmlspecialchars(trim((string) $para))) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php foreach ($fpk_rows as $row):
                $icon = smks3_fpk_icon($row['kategori']);
            ?>
                <div class="col-md-6 col-lg-3 fade-in">
                    <div class="card card-hover h-100 p-4 border-1 shadow-sm position-relative"
                         <?php if ($is_editor): ?>
                         data-edit-block="fpk_item"
                         data-edit-label="Sunting <?= htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8') ?>"
                         data-id="<?= (int) $row['id'] ?>"
                         data-kategori="<?= htmlspecialchars($row['kategori'], ENT_QUOTES, 'UTF-8') ?>"
                         data-content="<?= htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') ?>"
                         <?php endif; ?>>
                        <i class="bi <?= $icon ?> fs-2 mb-2"></i>
                        <h5 class="fw-bold" data-bind="fpk_kategori"><?= htmlspecialchars($row['kategori']) ?></h5>
                        <p data-bind="fpk_content"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                        <hr class="divider">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($is_editor): ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="fpk_add"
                    data-edit-label="Tambah FPK / visi / misi"
                    data-edit-hint="Tambah kad kategori baharu.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Item
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.card { background: #ffffff; border-radius: 16px; }
.card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card-hover:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,0.15); }
.divider { border-top: 1px solid #e2e8f0; margin: 1rem 0 0 0; }
.fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fade-in.show { opacity: 1; transform: translateY(0); }
</style>

<script>
const faders = document.querySelectorAll('.fade-in');
const appearOnScroll = new IntersectionObserver(function(entries, observer) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('show');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.2 });
faders.forEach(fader => appearOnScroll.observe(fader));
</script>
