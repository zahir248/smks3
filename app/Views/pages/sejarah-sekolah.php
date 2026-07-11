<section class="page-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Sejarah SMK Seremban 3</h2>
        <div class="timeline">
            <?php if (!empty($sejarahList)): ?>
                <?php foreach ($sejarahList as $row): ?>
                    <div class="timeline-item fade-in"
                         <?php if ($is_editor): ?>
                         data-edit-block="sejarah_item"
                         data-edit-label="Sunting sejarah"
                         data-id="<?= (int) $row['id'] ?>"
                         data-tajuk="<?= htmlspecialchars($row['tajuk'], ENT_QUOTES, 'UTF-8') ?>"
                         data-content="<?= htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') ?>"
                         <?php if (array_key_exists('tarikh', $row)): ?>
                         data-tarikh="<?= htmlspecialchars((string) ($row['tarikh'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                         <?php endif; ?>
                         <?php endif; ?>>
                        <h5 class="fw-bold" data-bind="sejarah_tajuk">
                            <?= htmlspecialchars($row['tajuk']) ?>
                        </h5><br>
                        <p data-bind="sejarah_content"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    </div>
                    <hr class="divider">
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Tiada sejarah lagi.</p>
            <?php endif; ?>
        </div>
        <?php if ($is_editor): ?>
        <div class="text-center mt-3">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="sejarah_add"
                    data-edit-label="Tambah sejarah"
                    data-edit-hint="Tambah entri sejarah baharu.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Sejarah
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
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
.divider { border-top: 1px solid #e2e8f0; margin: 1rem 0; }
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
