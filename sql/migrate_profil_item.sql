<?php
/**
 * Flexible profile cards (title, value, icon) for Profil Sekolah page.
 */
CREATE TABLE IF NOT EXISTS profil_item (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    value_text TEXT NOT NULL,
    icon VARCHAR(64) NOT NULL DEFAULT 'bi-info-circle',
    sort_order INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_profil_item_sort (sort_order, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
