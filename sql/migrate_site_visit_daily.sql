USE smks3;

CREATE TABLE IF NOT EXISTS site_visit_daily (
    visit_date DATE NOT NULL PRIMARY KEY,
    visit_count INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
