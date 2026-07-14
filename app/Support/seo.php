<?php

declare(strict_types=1);

/**
 * SEO helpers: absolute URLs, meta defaults, Open Graph, JSON-LD.
 */

function smks3_base_url(): string
{
    $configured = trim((string) (smks3_env('APP_URL') ?? ''));
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
        || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    return ($https ? 'https' : 'http') . '://' . $host . $base;
}

function smks3_absolute_url(string $path = ''): string
{
    $base = smks3_base_url();
    $path = trim($path);
    if ($path === '' || $path === '/') {
        return $base . '/';
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return $base . '/' . ltrim($path, '/');
}

function smks3_brand_short(): string
{
    return 'SMK Seremban 3';
}

function smks3_brand_aliases(): string
{
    return 'SMK Seremban 3 (SMKS3)';
}

/** Default OG / social share image. */
function smks3_default_og_image(): string
{
    foreach (['images/smk-seremban-3-hero.jpg', 'images/hero-logo.png', 'images/favicon-smks3.ico'] as $candidate) {
        if (is_file(BASE_PATH . '/' . $candidate)) {
            return smks3_absolute_url($candidate);
        }
    }
    return smks3_absolute_url('images/hero-logo.png');
}

/**
 * Per-route meta descriptions tuned for school name / SMKS3 searches.
 *
 * @return array<string, string>
 */
function smks3_seo_page_descriptions(): array
{
    $brand = smks3_brand_short();
    return [
        'index' => "Portal rasmi {$brand} (SMKS3), Seremban, Negeri Sembilan. Berita sekolah, akademik, kokurikulum, profil dan maklumat rasmi SMKS3.",
        'about' => "Perihal {$brand} (SMKS3) — sejarah ringkas, maklumat sekolah dan komitmen pendidikan di Seremban 3.",
        'contact' => "Hubungi {$brand} (SMKS3). Alamat, telefon, e-mel dan lokasi sekolah di Seremban, Negeri Sembilan.",
        'profil-sekolah' => "Profil rasmi {$brand} (SMKS3) — maklumat sekolah, identiti dan latar belakang SMK Seremban 3.",
        'sejarah-sekolah' => "Sejarah {$brand} (SMKS3) — perjalanan dan perkembangan sekolah menengah di Seremban 3.",
        'misi-visi-sekolah' => "Falsafah Pendidikan Kebangsaan, visi, misi dan motto {$brand} (SMKS3).",
        'pengurusan-tertinggi' => "Barisan pengurusan tertinggi {$brand} (SMKS3).",
        'senarai-pengetua' => "Senarai pengetua {$brand} (SMKS3) dari dahulu hingga kini.",
        'staff' => "Direktori staf dan warga pendidik {$brand} (SMKS3).",
        'news' => "Berita dan acara terkini dari {$brand} (SMKS3).",
        'courses' => "Program dan kursus akademik di {$brand} (SMKS3).",
        'kalendar-akademik' => "Kalendar akademik {$brand} (SMKS3) — tarikh penting sesi persekolahan.",
        'peraturan-sekolah' => "Peraturan sekolah {$brand} (SMKS3) untuk murid dan warga sekolah.",
        'lencana-lagu-sekolah' => "Lencana dan lagu sekolah {$brand} (SMKS3).",
        'pelan-sekolah' => "Pelan dan denah kawasan {$brand} (SMKS3).",
        'pra-sekolah' => "Maklumat pra-sekolah berkaitan {$brand} (SMKS3).",
        'jawatankuasa-pibg' => "Jawatankuasa PIBG {$brand} (SMKS3).",
        'kelab-persatuan' => "Kelab dan persatuan murid di {$brand} (SMKS3).",
        'unit-badan-beruniform' => "Unit badan beruniform di {$brand} (SMKS3).",
        'unit-bimbingan-kaunseling' => "Unit Bimbingan dan Kaunseling {$brand} (SMKS3).",
        'pemimpin-murid' => "Pemimpin murid dan organisasi pelajar {$brand} (SMKS3).",
        'pusat-sumber' => "Pusat sumber {$brand} (SMKS3).",
        'buletin-sekolah' => "Buletin rasmi {$brand} (SMKS3).",
        'enrolmen-murid' => "Maklumat enrolmen murid {$brand} (SMKS3).",
        'cuti-perayaan' => "Jadual cuti dan perayaan {$brand} (SMKS3).",
        'guru-apk' => "Guru APK di {$brand} (SMKS3).",
        'bil-kelas-gambar' => "Bilangan kelas dan gambar kelas {$brand} (SMKS3).",
        'pilihan-mata-pelajaran' => "Pilihan mata pelajaran di {$brand} (SMKS3).",
        'pentaksiran-peperiksaan' => "Pentaksiran dan peperiksaan di {$brand} (SMKS3).",
        'keputusan' => "Keputusan peperiksaan {$brand} (SMKS3).",
        'kecemerlangan-program-akademik' => "Kecemerlangan dan program akademik {$brand} (SMKS3).",
        'analisis-ppt' => "Analisis PPT {$brand} (SMKS3).",
        'analisis-pat-t4-uasa-t1,2,3' => "Analisis PAT T4 dan UASA {$brand} (SMKS3).",
        'bank-soalan-uasa-ppt-pat-selaras' => "Bank soalan UASA, PPT dan PAT {$brand} (SMKS3).",
        'penggubal-soalan-upsa-uasa' => "Penggubal soalan UPSA dan UASA {$brand} (SMKS3).",
    ];
}

/**
 * Public paths for sitemap (relative to site base).
 *
 * @return list<string>
 */
function smks3_seo_public_paths(): array
{
    return [
        '/',
        '/about',
        '/contact',
        '/profil-sekolah',
        '/sejarah-sekolah',
        '/misi-visi-sekolah',
        '/pengurusan-tertinggi',
        '/senarai-pengetua',
        '/staff',
        '/news',
        '/courses',
        '/kalendar-akademik',
        '/peraturan-sekolah',
        '/lencana-lagu-sekolah',
        '/pelan-sekolah',
        '/pra-sekolah',
        '/jawatankuasa-pibg',
        '/kelab-persatuan',
        '/unit-badan-beruniform',
        '/unit-bimbingan-kaunseling',
        '/pemimpin-murid',
        '/pusat-sumber',
        '/buletin-sekolah',
        '/enrolmen-murid',
        '/cuti-perayaan',
        '/guru-apk',
        '/bil-kelas-gambar',
        '/pilihan-mata-pelajaran',
        '/pentaksiran-peperiksaan',
        '/keputusan',
        '/kecemerlangan-program-akademik',
        '/analisis-ppt',
        '/analisis-pat-t4-uasa-t1,2,3',
        '/bank-soalan-uasa-ppt-pat-selaras',
        '/penggubal-soalan-upsa-uasa',
    ];
}

function smks3_seo_plain_text(string $html, int $maxLen = 160): string
{
    $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
    $text = trim($text);
    if (mb_strlen($text) <= $maxLen) {
        return $text;
    }
    $cut = mb_substr($text, 0, $maxLen - 1);
    $space = mb_strrpos($cut, ' ');
    if ($space !== false && $space > 40) {
        $cut = mb_substr($cut, 0, $space);
    }
    return rtrim($cut, '.,;:') . '…';
}

/**
 * Build SEO bag for the current page. Controllers may pre-set keys to override.
 *
 * @param array<string, mixed> $vars Extracted view variables (page_title, settings, news_item, …)
 * @return array{
 *   title: string,
 *   description: string,
 *   canonical: string,
 *   og_type: string,
 *   og_image: string,
 *   robots: string,
 *   keywords: string,
 *   json_ld: list<array<string, mixed>>
 * }
 */
function smks3_resolve_seo(array $vars = []): array
{
    $settings = is_array($vars['settings'] ?? null) ? $vars['settings'] : getSettings();
    $layout = is_array($vars['layout'] ?? null) ? $vars['layout'] : smks3_get_layout_content();
    $route = (string) ($vars['current_page'] ?? smks3_current_route());
    $pageTitle = trim((string) ($vars['page_title'] ?? 'Laman Utama'));
    $schoolName = trim((string) ($settings['school_name'] ?? '')) ?: 'Sekolah Menengah Kebangsaan Seremban 3';
    $brand = smks3_brand_short();
    $aliases = smks3_brand_aliases();

    $descriptions = smks3_seo_page_descriptions();
    $defaultDesc = $descriptions[$route]
        ?? trim((string) ($settings['about_summary'] ?? ''))
        ?: "Portal rasmi {$brand} (SMKS3) — maklumat sekolah, berita dan akademik.";

    if (!empty($vars['page_lead']) && is_string($vars['page_lead'])) {
        $defaultDesc = smks3_seo_plain_text((string) $vars['page_lead']);
    }

    $metaDescription = trim((string) ($vars['meta_description'] ?? ''));
    if ($metaDescription === '') {
        $metaDescription = $defaultDesc;
    }
    $metaDescription = smks3_seo_plain_text($metaDescription, 160);

    // Document title: homepage prioritises brand keywords for search.
    if (!empty($vars['meta_title']) && is_string($vars['meta_title'])) {
        $docTitle = trim((string) $vars['meta_title']);
    } elseif ($route === 'index') {
        $docTitle = "{$aliases} | Portal Rasmi Sekolah Menengah Kebangsaan Seremban 3";
    } else {
        $docTitle = "{$pageTitle} | {$aliases}";
    }

    $path = $route === 'index' ? '/' : '/' . $route;
    $query = '';
    if ($route === 'news-details' && !empty($vars['news_item']) && is_array($vars['news_item'])) {
        $newsUrl = smks3_news_article_url($vars['news_item']);
        $canonical = smks3_absolute_url($newsUrl);
    } else {
        $canonical = trim((string) ($vars['canonical_url'] ?? ''));
        if ($canonical === '') {
            $canonical = smks3_absolute_url($path . $query);
        } elseif (!preg_match('#^https?://#i', $canonical)) {
            $canonical = smks3_absolute_url($canonical);
        }
    }

    $ogType = trim((string) ($vars['og_type'] ?? 'website')) ?: 'website';
    $ogImage = trim((string) ($vars['og_image'] ?? ''));
    if ($ogImage === '') {
        $ogImage = smks3_default_og_image();
    } elseif (!preg_match('#^https?://#i', $ogImage)) {
        $ogImage = smks3_absolute_url($ogImage);
    }

    $newsItem = ($route === 'news-details' && !empty($vars['news_item']) && is_array($vars['news_item']))
        ? $vars['news_item']
        : null;
    if ($newsItem !== null) {
        $ogType = 'article';
        if (!isset($vars['meta_description']) || trim((string) ($vars['meta_description'] ?? '')) === '') {
            $excerpt = (string) ($newsItem['excerpt'] ?? $newsItem['content'] ?? '');
            if ($excerpt !== '') {
                $metaDescription = smks3_seo_plain_text($excerpt, 160);
            }
        }
        if (empty($vars['og_image'])) {
            $rawImage = smks3_news_primary_image($newsItem['image'] ?? $newsItem['image_url'] ?? null);
            if ($rawImage !== '') {
                if (preg_match('#^https?://#i', $rawImage)) {
                    $ogImage = $rawImage;
                } else {
                    $ogImage = smks3_absolute_url(smks3_news_image_src($rawImage));
                }
            }
        }
    }

    $robots = trim((string) ($vars['meta_robots'] ?? 'index, follow'));

    $keywords = trim((string) ($vars['meta_keywords'] ?? ''));
    if ($keywords === '') {
        $keywords = implode(', ', [
            'SMK Seremban 3',
            'SMKS3',
            'smks3',
            'Sekolah Menengah Kebangsaan Seremban 3',
            'SMK Seremban',
            'sekolah Seremban 3',
            'Negeri Sembilan',
            $schoolName,
        ]);
    }

    $sameAs = [];
    foreach (($layout['social'] ?? []) as $link) {
        if (!is_array($link)) {
            continue;
        }
        $href = trim((string) ($link['href'] ?? ''));
        if ($href !== '' && preg_match('#^https?://#i', $href)) {
            $sameAs[] = $href;
        }
    }

    $orgId = smks3_absolute_url('/#organization');
    $websiteId = smks3_absolute_url('/#website');

    $organization = [
        '@type' => 'School',
        '@id' => $orgId,
        'name' => $schoolName,
        'alternateName' => ['SMK Seremban 3', 'SMKS3', 'smks3', 'SMK S3'],
        'url' => smks3_absolute_url('/'),
        'logo' => smks3_absolute_url((string) ($layout['navbar_logo'] ?? 'images/hero-logo.png')),
        'image' => $ogImage,
        'description' => smks3_seo_plain_text((string) ($settings['about_summary'] ?? $metaDescription), 300),
        'email' => (string) ($settings['email'] ?? ''),
        'telephone' => (string) ($settings['phone'] ?? ''),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => (string) ($settings['address'] ?? ''),
            'addressLocality' => 'Seremban',
            'addressRegion' => 'Negeri Sembilan',
            'postalCode' => '70300',
            'addressCountry' => 'MY',
        ],
    ];
    if ($sameAs !== []) {
        $organization['sameAs'] = array_values(array_unique($sameAs));
    }

    $website = [
        '@type' => 'WebSite',
        '@id' => $websiteId,
        'name' => $aliases,
        'alternateName' => ['SMKS3', 'smks3', 'SMK Seremban 3'],
        'url' => smks3_absolute_url('/'),
        'inLanguage' => 'ms-MY',
        'publisher' => ['@id' => $orgId],
    ];

    $webpage = [
        '@type' => 'WebPage',
        '@id' => $canonical . '#webpage',
        'url' => $canonical,
        'name' => $docTitle,
        'description' => $metaDescription,
        'isPartOf' => ['@id' => $websiteId],
        'about' => ['@id' => $orgId],
        'inLanguage' => 'ms-MY',
    ];

    $jsonLd = [
        [
            '@context' => 'https://schema.org',
            '@graph' => [$organization, $website, $webpage],
        ],
    ];

    // News article schema
    if ($newsItem !== null) {
        $published = (string) ($newsItem['published_at'] ?? $newsItem['created_at'] ?? '');
        $modified = (string) ($newsItem['updated_at'] ?? $published);
        $article = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => (string) ($newsItem['title'] ?? $pageTitle),
            'description' => $metaDescription,
            'image' => [$ogImage],
            'datePublished' => $published !== '' ? date('c', strtotime($published)) : null,
            'dateModified' => $modified !== '' ? date('c', strtotime($modified)) : null,
            'author' => [
                '@type' => 'Organization',
                'name' => $schoolName,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $schoolName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => smks3_absolute_url((string) ($layout['navbar_logo'] ?? 'images/hero-logo.png')),
                ],
            ],
            'mainEntityOfPage' => $canonical,
            'inLanguage' => 'ms-MY',
        ];
        $jsonLd[] = array_filter($article, static fn ($v) => $v !== null);
    }

    // BreadcrumbList when available
    if (!empty($vars['breadcrumb_items']) && is_array($vars['breadcrumb_items'])) {
        $elements = [];
        $pos = 1;
        foreach ($vars['breadcrumb_items'] as $crumb) {
            if (!is_array($crumb)) {
                continue;
            }
            $label = trim((string) ($crumb['label'] ?? $crumb['title'] ?? ''));
            $href = trim((string) ($crumb['url'] ?? $crumb['href'] ?? ''));
            if ($label === '') {
                continue;
            }
            $entry = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $label,
            ];
            if ($href !== '' && $href !== '#' && $href !== './') {
                $entry['item'] = preg_match('#^https?://#i', $href) ? $href : smks3_absolute_url(ltrim($href, './'));
            } elseif ($href === './' || $href === '/') {
                $entry['item'] = smks3_absolute_url('/');
            }
            $elements[] = $entry;
        }
        if (count($elements) >= 1) {
            $jsonLd[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $elements,
            ];
        }
    }

    if (!empty($vars['json_ld']) && is_array($vars['json_ld'])) {
        foreach ($vars['json_ld'] as $extra) {
            if (is_array($extra)) {
                $jsonLd[] = $extra;
            }
        }
    }

    return [
        'title' => $docTitle,
        'description' => $metaDescription,
        'canonical' => $canonical,
        'og_type' => $ogType,
        'og_image' => $ogImage,
        'robots' => $robots,
        'keywords' => $keywords,
        'json_ld' => $jsonLd,
        'school_name' => $schoolName,
        'brand' => $brand,
    ];
}

/**
 * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
 */
function smks3_seo_sitemap_entries(): array
{
    $entries = [];
    foreach (smks3_seo_public_paths() as $path) {
        $priority = $path === '/' ? '1.0' : '0.7';
        $changefreq = $path === '/' ? 'daily' : ($path === '/news' ? 'daily' : 'weekly');
        $entries[] = [
            'loc' => smks3_absolute_url($path === '/' ? '/' : ltrim($path, '/')),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    try {
        $pdo = getConnection();
        $stmt = $pdo->query(
            'SELECT slug, updated_at, created_at FROM news
             WHERE slug IS NOT NULL AND slug <> \'\'
             ORDER BY COALESCE(updated_at, created_at) DESC
             LIMIT 500'
        );
        if ($stmt) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $slug = trim((string) ($row['slug'] ?? ''));
                if ($slug === '' || !preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
                    continue;
                }
                $last = (string) ($row['updated_at'] ?? $row['created_at'] ?? '');
                $entry = [
                    'loc' => smks3_absolute_url('news-details?' . http_build_query(['slug' => $slug])),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
                if ($last !== '') {
                    $ts = strtotime($last);
                    if ($ts !== false) {
                        $entry['lastmod'] = date('Y-m-d', $ts);
                    }
                }
                $entries[] = $entry;
            }
        }
    } catch (Throwable $e) {
        // pages only
    }

    return $entries;
}

function smks3_seo_render_sitemap_xml(): void
{
    $entries = smks3_seo_sitemap_entries();
    header('Content-Type: application/xml; charset=UTF-8');
    header('X-Robots-Tag: noindex');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($entries as $entry) {
        echo "  <url>\n";
        echo '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
        if (!empty($entry['lastmod'])) {
            echo '    <lastmod>' . htmlspecialchars((string) $entry['lastmod'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
        }
        if (!empty($entry['changefreq'])) {
            echo '    <changefreq>' . htmlspecialchars((string) $entry['changefreq'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</changefreq>\n";
        }
        if (!empty($entry['priority'])) {
            echo '    <priority>' . htmlspecialchars((string) $entry['priority'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</priority>\n";
        }
        echo "  </url>\n";
    }
    echo '</urlset>';
}

function smks3_seo_render_robots_txt(): void
{
    $sitemap = smks3_absolute_url('sitemap.xml');
    header('Content-Type: text/plain; charset=UTF-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /admin/\n";
    echo "Disallow: /superadmin/\n";
    echo "Disallow: /api/\n";
    echo "Disallow: /errors/\n";
    echo "Disallow: /pengurusan-akses\n";
    echo "Disallow: /config/\n";
    echo "Disallow: /app/\n";
    echo "Disallow: /sql/\n";
    echo "Disallow: /storage/\n";
    echo "\n";
    echo 'Sitemap: ' . $sitemap . "\n";
}
