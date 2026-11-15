<?php include 'config.php'; ?>
<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb}.card{border:none;border-radius:20px;box-shadow:0 8px 25px rgba(0,0,0,.06)}</style>
</head><body>
<nav class="navbar bg-white shadow-sm sticky-top"><div class="container d-flex justify-content-between"><a href="index.php" class="text-decoration-none fw-bold fs-5" style="color:#5b21b6">Pasar<span style="color:#f97316">Bengkulu</span></a><a href="index.php" class="btn btn-dark rounded-pill px-4">Belanja Lagi</a></div></nav>
<div class="container mt-4">
<h3 style="font-weight:800">🧾 Riwayat Pesanan Saya</h3>
<p class="text-muted">Semua pesanan yang pernah kamu buat di sini.</p>

<?php $q=$conn->query("SELECT * FROM pesanan ORDER BY id DESC"); if($q->num_rows==0){ echo "<div class='card p-5 text-center'><div style='font-size:60px'>📦</div><h5>Belum ada pesanan</h5><p class='text-muted'>Yuk checkout dulu di keranjang</p><a href='index.php' class='btn btn-dark rounded-pill'>Belanja Sekarang</a></div>"; } ?>

<?php while($ps=$q->fetch_assoc()){ ?>
<div class="card p-4 mb-3">
  <div class="d-flex justify-content-between flex-wrap">
    <div><b>Pesanan #<?= $ps['id'] ?></b> <span class="badge bg-warning text-dark rounded-pill ms-2"><?= $ps['status'] ?></span><br><small class="text-muted"><?= $ps['tanggal'] ?> • Kirim ke: <?= $ps['alamat_kirim'] ?></small></div>
    <div class="text-end"><small class="text-muted">Total</small><br><b style="color:#5b21b6;font-size:18px">Rp <?= number_format($ps['total']) ?></b></div>
  </div>
  <hr>
  <?php $d=$conn->query("SELECT detail_pesanan.*, produk.nama FROM detail_pesanan LEFT JOIN produk ON detail_pesanan.produk_id=produk.id WHERE pesanan_id={$ps['id']}"); while($item=$d->fetch_assoc()){ ?>
  <div class="d-flex justify-content-between"><span>• <?= $item['nama'] ?> x <?= $item['jumlah'] ?></span><span>Rp <?= number_format($item['subtotal']) ?></span></div>
  <?php } ?>
</div>
<?php } ?>
</div></body></html>