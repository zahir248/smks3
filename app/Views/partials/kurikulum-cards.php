<?php
/**
 * @var bool $is_editor
 * @var string $kurikulum_page_key
 * @var array $cards
 * @var string $section_key
 * @var string $col_class
 */
$cards = is_array($cards ?? null) ? $cards : [];
$section_key = (string) ($section_key ?? 'main');
$col_class = (string) ($col_class ?? 'col-md-6 col-lg-4');
$row_class = (string) ($row_class ?? 'row g-4');
$page_key = (string) ($kurikulum_page_key ?? '');
?>
<style>
.smks3-external-locked { opacity: 0.9; cursor: pointer; }
</style>
<div class="<?= htmlspecialchars($row_class, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($cards === [] && empty($is_editor)): ?>
        <div class="col-12">
            <p class="text-center text-muted mb-0">Tiada kandungan buat masa ini.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($cards as $card):
        $id = (int) ($card['id'] ?? 0);
        $title = (string) ($card['title'] ?? '');
        $desc = (string) ($card['description'] ?? '');
        $icon = trim((string) ($card['icon'] ?? 'bi-folder2-open')) ?: 'bi-folder2-open';
        $href = trim((string) ($card['href'] ?? ''));
        $btn = trim((string) ($card['btn_label'] ?? ''));
        $external = !empty($card['is_external']);
        $links = is_array($card['links'] ?? null) ? $card['links'] : [];
        $collapseId = 'kurikulumCollapse' . $id;
        $hasLinks = $links !== [];
    ?>
        <div class="<?= htmlspecialchars($col_class, ENT_QUOTES, 'UTF-8') ?>">
            <div class="card card-hover border-0 shadow-sm h-100"
                 <?php if (!empty($is_editor)): ?>
                 data-edit-block="kurikulum_card"
                 data-edit-label="Sunting kad kurikulum"
                 data-id="<?= $id ?>"
                 data-page-key="<?= htmlspecialchars($page_key, ENT_QUOTES, 'UTF-8') ?>"
                 data-section-key="<?= htmlspecialchars((string) ($card['section_key'] ?? $section_key), ENT_QUOTES, 'UTF-8') ?>"
                 data-title="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                 data-description="<?= htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') ?>"
                 data-icon="<?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>"
                 data-href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                 data-btn-label="<?= htmlspecialchars($btn, ENT_QUOTES, 'UTF-8') ?>"
                 data-external="<?= $external ? '1' : '0' ?>"
                 data-links-json="<?= htmlspecialchars(json_encode($links, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <div class="card-body text-center p-4">
                    <i class="bi <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> text-primary display-5 mb-3" data-bind="kurikulum_icon"></i>
                    <h5 class="mb-3" data-bind="kurikulum_title"><?= htmlspecialchars($title) ?></h5>
                    <?php if ($desc !== ''): ?>
                        <p class="text-muted small mb-3" data-bind="kurikulum_description"><?= nl2br(htmlspecialchars($desc)) ?></p>
                    <?php else: ?>
                        <p class="text-muted small mb-3 d-none" data-bind="kurikulum_description"></p>
                    <?php endif; ?>

                    <?php if ($hasLinks): ?>
                        <button class="btn btn-outline-primary"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>">
                            Lihat Bahagian
                        </button>
                        <div class="collapse mt-4" id="<?= htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="list-group text-start" data-bind="kurikulum_links">
                                <?php foreach ($links as $link):
                                    $lt = (string) ($link['title'] ?? '');
                                    $lh = trim((string) ($link['href'] ?? '#')) ?: '#';
                                    $linkMeta = smks3_staff_external_link_meta($lh);
                                    $linkClass = trim('list-group-item list-group-item-action ' . ($linkMeta['class'] ?? ''));
                                ?>
                                    <a href="<?= htmlspecialchars($linkMeta['href'], ENT_QUOTES, 'UTF-8') ?>"
                                       class="<?= htmlspecialchars($linkClass, ENT_QUOTES, 'UTF-8') ?>"
                                       <?= $linkMeta['attrs'] ?>>
                                        <?php if (!empty($linkMeta['locked'])): ?>
                                            <i class="bi bi-lock-fill me-1 opacity-75" aria-hidden="true"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($lt) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php elseif ($href !== ''):
                        $btnMeta = smks3_staff_external_link_meta($href);
                        $isExtBtn = smks3_is_external_kurikulum_url($href) || $external;
                        $btnClass = 'btn ' . ($isExtBtn && empty($btnMeta['locked']) ? 'btn-primary' : 'btn-outline-primary') . ' btn-sm';
                        if (!empty($btnMeta['class'])) {
                            $btnClass .= ' ' . $btnMeta['class'];
                        }
                    ?>
                        <a href="<?= htmlspecialchars($btnMeta['href'], ENT_QUOTES, 'UTF-8') ?>"
                           class="<?= htmlspecialchars($btnClass, ENT_QUOTES, 'UTF-8') ?>"
                           data-bind="kurikulum_btn"
                           <?= $btnMeta['attrs'] ?>>
                            <?php if (!empty($btnMeta['locked'])): ?>
                                <i class="bi bi-lock-fill me-1" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($btn !== '' ? $btn : 'Lihat Maklumat') ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (!empty($is_editor)): ?>
<div class="text-center mt-4">
    <button type="button" class="btn btn-outline-primary"
            data-edit-block="kurikulum_card_add"
            data-edit-label="Tambah kad kurikulum"
            data-edit-hint="Tambah kad baharu untuk halaman ini."
            data-page-key="<?= htmlspecialchars($page_key, ENT_QUOTES, 'UTF-8') ?>"
            data-section-key="<?= htmlspecialchars($section_key, ENT_QUOTES, 'UTF-8') ?>">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kad
    </button>
</div>
<?php endif; ?>
