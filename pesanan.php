<?php include 'config.php';
$pesanan = $conn->query("SELECT * FROM pesanan ORDER BY tanggal DESC");
$total_pesanan = $pesanan->num_rows;
$total_belanja = 0;
$pesanan2 = $conn->query("SELECT SUM(total) as t FROM pesanan");
if($r=$pesanan2->fetch_assoc()) $total_belanja = $r['t'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Riwayat Pesanan - PasarBengkulu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#FFF7ED}
.navbar-brand{font-weight:800;font-size:22px}
.navbar-brand span{color:#EA580C}
.hero{background:linear-gradient(135deg,#7C2D12,#EA580C,#F59E0B);color:white;border-radius:24px;padding:32px;position:relative;overflow:hidden}
.hero:after{content:"🛍️";position:absolute;right:20px;top:10px;font-size:90px;opacity:.25;transform:rotate(10deg)}
.stat-card{background:white;border-radius:18px;padding:18px;border:0;box-shadow:0 8px 25px rgba(0,0,0,.06)}
.order-card{background:white;border-radius:20px;border:0;box-shadow:0 8px 25px rgba(0,0,0,.06);overflow:hidden;transition:.2s}
.order-card:hover{transform:translateY(-3px);box-shadow:0 14px 30px rgba(0,0,0,.1)}
.badge-menunggu{background:#FEF3C7;color:#92400E}
.badge-selesai{background:#DCFCE7;color:#166534}
.badge-diproses{background:#DBEAFE;color:#1E40AF}
.btn-orange{background:#EA580C;color:white;border-radius:999px;font-weight:700;padding:10px 22px;border:0}
.btn-orange:hover{background:#C2410C;color:white}
.btn-dark-pill{background:#111827;color:white;border-radius:999px;padding:8px 18px;font-weight:600;text-decoration:none}
.empty-icon{font-size:64px}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Pasar<span>Bengkulu</span> 🧺</a>
    <div class="ms-auto d-flex gap-2">
      <a href="keranjang.php" class="btn btn-outline-dark rounded-pill">🛒 Keranjang</a>
      <a href="index.php" class="btn-dark-pill">← Belanja Lagi</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="hero mb-4">
    <p class="mb-1 opacity-75 fw-semibold">✨ PasarBengkulu • Marketplace UMKM Bengkulu</p>
    <h1 class="fw-bold display-6">Riwayat Pesanan Saya 📦</h1>
    <p class="mb-0 opacity-90">Semua pesanan kamu, dari checkout sampai sampai di rumah. Pantau di sini ya!</p>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="stat-card d-flex align-items-center gap-3"><div style="font-size:32px">🧾</div><div><small class="text-muted">Total Pesanan</small><h4 class="mb-0 fw-bold"><?= $total_pesanan ?> Pesanan</h4></div></div></div>
    <div class="col-md-4"><div class="stat-card d-flex align-items-center gap-3"><div style="font-size:32px">💰</div><div><small class="text-muted">Total Belanja</small><h4 class="mb-0 fw-bold text-success">Rp <?= number_format($total_belanja,0,',','.') ?></h4></div></div></div>
    <div class="col-md-4"><div class="stat-card d-flex align-items-center gap-3"><div style="font-size:32px">🚚</div><div><small class="text-muted">Pengiriman</small><h4 class="mb-0 fw-bold">Bengkulu • 1-3 Hari</h4></div></div></div>
  </div>

  <?php if($total_pesanan==0): ?>
  <div class="card order-card text-center p-5">
    <div class="empty-icon">📦</div>
    <h4 class="fw-bold mt-2">Belum ada pesanan nih</h4>
    <p class="text-muted">Yuk checkout dulu di keranjang, biar pesanan kamu muncul di sini.</p>
    <div><a href="index.php" class="btn-orange text-decoration-none d-inline-block">Belanja Sekarang →</a></div>
    <div class="row g-2 mt-4 text-start">
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">🍌 <b>Keripik Pisang</b><br><small class="text-muted">Rp 25.000 • Terlaris</small></div></div>
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">👜 <b>Tas Anyaman</b><br><small class="text-muted">Rp 85.000 • Handmade</small></div></div>
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">👘 <b>Batik Bengkulu</b><br><small class="text-muted">Rp 150.000 • Premium</small></div></div>
    </div>
  </div>
  <?php else: ?>
    <?php while($p=$pesanan->fetch_assoc()):
      $badge = $p['status']=='Selesai' ? 'badge-selesai' : ($p['status']=='Diproses' ? 'badge-diproses' : 'badge-menunggu');
      $detail = $conn->query("SELECT dp.*, pr.nama FROM detail_pesanan dp JOIN produk pr ON dp.produk_id=pr.id WHERE dp.pesanan_id=".$p['id']);
    ?>
    <div class="card order-card mb-3">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between flex-wrap gap-2">
          <div><span class="badge bg-dark rounded-pill">Pesanan #<?= $p['id'] ?></span> <span class="badge <?= $badge ?> rounded-pill"><?= htmlspecialchars($p['status']) ?></span><div class="text-muted small mt-2">📅 <?= date('d M Y, H:i', strtotime($p['tanggal'])) ?> • 📍 <?= htmlspecialchars($p['alamat_kirim']) ?></div></div>
          <div class="text-end"><small class="text-muted">Total</small><h4 class="fw-bold text-success mb-0">Rp <?= number_format($p['total'],0,',','.') ?></h4></div>
        </div>
        <hr>
        <?php while($d=$detail->fetch_assoc()): ?>
        <div class="d-flex justify-content-between py-1"><span>🛍️ <?= htmlspecialchars($d['nama']) ?> <span class="text-muted">x <?= $d['jumlah'] ?></span></span><b>Rp <?= number_format($d['subtotal'],0,',','.') ?></b></div>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <p class="text-center text-muted small mt-4">PasarBengkulu © 2026 • Universitas Muhammadiyah Bengkulu • Dukung UMKM Bengkulu 💛</p>
</div>
</body>
</html>
