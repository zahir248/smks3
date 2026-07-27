<?php
smks3_view_include(VIEW_PATH . '/partials/kurikulum-hub.php', [
    'is_editor' => $is_editor ?? false,
    'kurikulum_page_key' => $kurikulum_page_key ?? '',
    'kurikulum_meta' => $kurikulum_meta ?? [],
    'kurikulum_cards' => $kurikulum_cards ?? [],
    'kurikulum_by_section' => $kurikulum_by_section ?? [],
]);
