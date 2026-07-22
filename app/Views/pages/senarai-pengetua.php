<style>
.pengetua-img {
    width: 150px;
    height: 200px;
    object-fit: contain;
    border-radius: 6px;
    border: 2px solid #fff;
    display: block;
    margin: 0 auto;
}
.pengetua-details {
    width: 100%;
    max-width: 150px;
    overflow-wrap: anywhere;
    word-break: break-word;
    line-height: 1.35;
}
.pengetua-details h5,
.pengetua-details p {
    margin-left: 0;
    margin-right: 0;
}
.timeline-item.left .pengetua-details {
    margin-left: auto;
    margin-right: 0;
    text-align: right;
}
.timeline-item.right .pengetua-details {
    margin-left: 0;
    margin-right: auto;
    text-align: left;
}
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
    .timeline-item.left .pengetua-details,
    .timeline-item.right .pengetua-details {
        margin-left: 0;
        margin-right: auto;
        text-align: left;
    }
}
</style>

<!-- Timeline Section -->
<section class="page-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Garis Masa Pengetua SMK Seremban 3</h2>
        <div class="timeline">
            <?php foreach ($pengetua_list as $index => $p) : ?>
                <div class="timeline-item <?= $index % 2 == 0 ? 'left' : 'right' ?>">
                    <div class="timeline-icon"><i class="bi bi-person-circle"></i></div>
                    <div class="timeline-content"
                         <?php if ($is_editor): ?>
                         data-edit-block="pengetua_item"
                         data-edit-label="Sunting pengetua"
                         data-id="<?= (int) $p['id'] ?>"
                         data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>"
                         data-start-year="<?= htmlspecialchars((string) $p['start_year'], ENT_QUOTES, 'UTF-8') ?>"
                         data-end-year="<?= htmlspecialchars((string) ($p['end_year'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                         <?php endif; ?>>
                    
                        <?php if (!empty($p['photo'])): ?>
                            <div class="mb-2 text-center">
                                <img src="<?= htmlspecialchars($p['photo']) ?>"
                                     alt="<?= htmlspecialchars($p['name']) ?>"
                                     class="pengetua-img">
                            </div>
                        <?php endif; ?>
                    
                        <div class="pengetua-details">
                            <h5 class="fw-bold" data-bind="pengetua_name">
                                <?= htmlspecialchars($p['name']) ?>
                            </h5>

                            <p class="text-muted mb-0" data-bind="pengetua_years">
                                <?= htmlspecialchars($p['start_year']) ?> –
                                <?= !empty($p['end_year']) ? htmlspecialchars($p['end_year']) : 'Kini' ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($is_editor): ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="pengetua_add"
                    data-edit-label="Tambah pengetua"
                    data-edit-hint="Tambah pengetua baharu. Gambar adalah pilihan.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengetua
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>
