<?php include 'config.php'; $id=$_GET['id']; $p=$conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
$emoji = ($p['kategori_id']==1?'🍪':($p['kategori_id']==2?'🧶':'👕')); ?>
<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb}.card{border:none;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.07)}.btn-beli{background:#5b21b6;color:#fff;border-radius:999px;padding:12px;font-weight:700;width:100%;border:none}.btn-beli:hover{background:#4c1d95;color:#fff}</style>
</head><body>
<div class="container mt-4">
<a href="index.php" class="text-decoration-none">← Kembali</a>
<div class="row mt-3 g-4">
<div class="col-md-6"><div class="card p-5 text-center" style="font-size:130px;background:linear-gradient(135deg,#ede9fe,#fff7ed)"><?= $emoji ?><h4 class="mt-2" style="font-size:18px"><?= $p['nama'] ?></h4></div></div>
<div class="col-md-6"><div class="card p-4">
<span class="badge bg-success rounded-pill" style="width:fit-content">✅ Stok Tersedia: <?= $p['stok'] ?></span>
<h2 class="mt-2" style="font-weight:800"><?= $p['nama'] ?></h2>
<p>⭐ 4.9 (128 ulasan) • Terjual 200+ • Bengkulu</p>
<h3 style="font-weight:800;color:#5b21b6">Rp <?= number_format($p['harga']) ?></h3>
<p class="text-muted"><?= $p['deskripsi'] ?></p>
<hr>
<form action="keranjang.php" method="POST">
<input type="hidden" name="produk_id" value="<?= $p['id'] ?>">
<label class="fw-bold">Jumlah</label>
<input type="number" name="jumlah" value="1" min="1" max="<?= $p['stok'] ?>" class="form-control mb-3" style="border-radius:12px">
<button class="btn-beli">🛒 Tambah ke Keranjang</button>
</form>
<div class="d-flex gap-2 mt-3 text-center" style="font-size:12px;color:#666"><span class="flex-fill border rounded p-2">🚚 Gratis Ongkir<br>Bengkulu Kota</span><span class="flex-fill border rounded p-2">🛡️ Garansi<br>100% Original</span><span class="flex-fill border rounded p-2">↩️ Retur<br>7 Hari</span></div>
</div></div>
</div></div></body></html>