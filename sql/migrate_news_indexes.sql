-- Optional: speed up news list / homepage queries
-- Safe to run multiple times if indexes already exist (will error — ignore or use ensure helper)

ALTER TABLE news ADD INDEX idx_news_published_at (published_at);
-- Only if `year` column exists:
-- ALTER TABLE news ADD INDEX idx_news_year (year);
