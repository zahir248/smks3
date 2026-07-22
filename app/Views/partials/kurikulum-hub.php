<?php
$meta = is_array($kurikulum_meta ?? null) ? $kurikulum_meta : ['intro' => '', 'sections' => []];
$sections = is_array($meta['sections'] ?? null) ? $meta['sections'] : [];
$sec = is_array($sections['main'] ?? null) ? $sections['main'] : ['title' => '', 'subtitle' => ''];
$pageKey = (string) ($kurikulum_page_key ?? '');
$cards = is_array($kurikulum_by_section['main'] ?? null)
    ? $kurikulum_by_section['main']
    : (is_array($kurikulum_cards ?? null) ? $kurikulum_cards : []);
?>
<section class="page-section">
    <div class="container"
         <?php if (!empty($is_editor)): ?>
         data-edit-block="kurikulum_meta"
         data-edit-label="Sunting tajuk halaman"
         data-edit-hint="Ubah pengenalan, tajuk dan subtajuk halaman."
         data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
         data-intro="<?= htmlspecialchars((string) ($meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         data-sections="<?= htmlspecialchars(json_encode($sections, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
        <?php if (trim((string) ($meta['intro'] ?? '')) !== ''): ?>
            <p class="text-center text-muted lead mb-4" data-bind="kurikulum_intro"><?= htmlspecialchars((string) $meta['intro']) ?></p>
        <?php endif; ?>

        <div class="text-center mb-5">
            <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($sec['title'] ?? '')) ?></h2>
            <?php if (trim((string) ($sec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $sec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php
        $section_key = 'main';
        $col_class = 'col-md-6 col-lg-4 mb-4';
        $row_class = 'row g-4 justify-content-center';
        smks3_view_include(VIEW_PATH . '/partials/kurikulum-cards.php', compact(
            'is_editor',
            'kurikulum_page_key',
            'cards',
            'section_key',
            'col_class',
            'row_class'
        ));
        ?>
    </div>
</section>
