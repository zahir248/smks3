# SMK Seremban 3 — MVC layout

```
smks3/
├── index.php              # Front controller
├── routes/web.php         # URL → controller map
├── app/
│   ├── bootstrap.php
│   ├── Core/              # Router, Controller, View
│   ├── Controllers/       # PageController
│   ├── Models/
│   ├── Services/          # CMS handlers
│   ├── Support/           # helpers, breadcrumbs, visits…
│   └── Views/
│       ├── layouts/       # header, footer, edit-mode
│       ├── home/
│       └── pages/
├── api/                   # login.php, save-content.php
├── config/                # database.php
├── errors/                # 401–503 pages
├── images/, uploads/
├── sql/                   # schema / migrations (reference)
└── admin/, superadmin/    # redirect stubs + logout only
```

Pretty URLs (`/smks3/profil-sekolah`) are routed by `.htaccess` → `index.php` → `App\Core\Router`.
