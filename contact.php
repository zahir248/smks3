<?php
$page_title = 'Hubungi';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $msg = trim($_POST['message'] ?? '');
    if (!$name || !$email || !$msg) {
        $error = 'Nama, e-mel dan mesej wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Alamat e-mel tidak sah.';
    } else {
        // No database: show success message only (message not stored)
        $message = 'Mesej anda telah dihantar. Terima kasih!';
        $_POST = [];
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <p class="text-muted lead mb-4">Hubungi kami untuk maklumat lanjut.</p>
        <div class="row g-4 mt-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted mb-3">Alamat & Hubungi</h6>
                        <p class="mb-3"><i class="bi bi-geo-alt text-primary me-2"></i><?= htmlspecialchars($settings['address'] ?? '') ?></p>
                        <p class="mb-3"><i class="bi bi-telephone text-primary me-2"></i><?= htmlspecialchars($settings['phone'] ?? '') ?></p>
                        <p class="mb-0"><i class="bi bi-envelope text-primary me-2"></i><?= htmlspecialchars($settings['email'] ?? '') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Hantar Mesej</h5>
                        <?php if ($message) : ?>
                        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                        <?php endif; ?>
                        <?php if ($error) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post" action="contact.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mel <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">Mesej <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="4" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Hantar Mesej</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
