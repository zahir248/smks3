<div class="org-card <?= $kategori=='pengetua' ? 'large' : ($kategori=='gkmp' ? 'small' : '') ?>">
    <div class="image-wrapper">
        <img src="<?= !empty($p['gambar']) ? htmlspecialchars($p['gambar']) : 'images/placeholder.png' ?>" 
             alt="<?= htmlspecialchars($p['nama']) ?>">
    </div>
    <h5><?= htmlspecialchars($p['nama']) ?></h5>
    <h6><?= htmlspecialchars($p['gred']) ?></h6>
    <p><?= htmlspecialchars($p['jawatan']) ?></p>
</div>