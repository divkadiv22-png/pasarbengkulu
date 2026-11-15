<?php
include 'config.php';

// Hapus 1 produk
if(isset($_GET['hapus'])){
  unset($_SESSION['keranjang'][$_GET['hapus']]);
  header("Location: keranjang.php"); exit;
}
// Kosongkan semua
if(isset($_GET['kosongkan'])){
  $_SESSION['keranjang'] = [];
  header("Location: keranjang.php"); exit;
}
// Checkout
if(isset($_POST['checkout'])){
  $alamat = $_POST['alamat'] ?? 'Alamat tidak diisi';
  $total = 0;
  foreach($_SESSION['keranjang'] as $id=>$qty){
    $q = $conn->query("SELECT * FROM produk WHERE id=$id");
    if($r=$q->fetch_assoc()) $total += $r['harga']*$qty;
  }
  if($total>0){
    $conn->query("INSERT INTO pesanan (total, alamat_kirim, status) VALUES ($total, '".$conn->real_escape_string($alamat)."', 'Menunggu')");
    $pesanan_id = $conn->insert_id;
    foreach($_SESSION['keranjang'] as $id=>$qty){
      $q = $conn->query("SELECT * FROM produk WHERE id=$id");
      if($r=$q->fetch_assoc()){
        $sub = $r['harga']*$qty;
        $conn->query("INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, subtotal) VALUES ($pesanan_id, $id, $qty, $sub)");
      }
    }
    $_SESSION['keranjang'] = [];
    header("Location: pesanan.php?baru=1"); exit;
  }
}

$items = [];
$total = 0;
$jml_item = 0;
foreach($_SESSION['keranjang'] as $id=>$qty){
  $q = $conn->query("SELECT * FROM produk WHERE id=$id");
  if($r=$q->fetch_assoc()){
    $r['qty'] = $qty; $r['subtotal'] = $r['harga']*$qty;
    $items[] = $r; $total += $r['subtotal']; $jml_item += $qty;
  }
}
$ongkir = $total>0 ? 10000 : 0;
$grand = $total + $ongkir;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Keranjang - PasarBengkulu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#FFF7ED}
.navbar-brand{font-weight:800;font-size:22px}.navbar-brand span{color:#EA580C}
.hero{background:linear-gradient(135deg,#7C2D12,#EA580C,#F59E0B);color:white;border-radius:24px;padding:28px;position:relative;overflow:hidden}
.hero:after{content:"🛒";position:absolute;right:20px;top:5px;font-size:85px;opacity:.25;transform:rotate(-8deg)}
.card-x{background:white;border-radius:20px;border:0;box-shadow:0 8px 25px rgba(0,0,0,.06)}
.item-img{width:72px;height:72px;border-radius:16px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;font-size:32px}
.btn-orange{background:#EA580C;color:white;border-radius:999px;font-weight:700;padding:12px;border:0;width:100%}
.btn-orange:hover{background:#C2410C;color:white}
.btn-dark-pill{background:#111827;color:white;border-radius:999px;padding:8px 18px;font-weight:600;text-decoration:none}
.qty-btn{width:32px;height:32px;border-radius:999px;border:1px solid #ddd;background:white;font-weight:700}
.input-alamat{border-radius:14px;padding:12px}
.promo{background:#FFFBEB;border:1px dashed #F59E0B;border-radius:14px;padding:10px 14px}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">Pasar<span>Bengkulu</span> 🧺</a>
    <div class="ms-auto d-flex gap-2">
      <a href="pesanan.php" class="btn btn-outline-dark rounded-pill">📦 Pesanan Saya</a>
      <a href="index.php" class="btn-dark-pill">← Lanjut Belanja</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="hero mb-4">
    <p class="mb-1 opacity-75 fw-semibold">✨ Hampir selesai, tinggal checkout!</p>
    <h1 class="fw-bold display-6">Keranjang Belanja 🛒</h1>
    <p class="mb-0 opacity-90"><?= $jml_item ?> produk di keranjang kamu • Pengiriman dari Bengkulu</p>
  </div>

  <?php if(empty($items)): ?>
  <div class="card card-x text-center p-5">
    <div style="font-size:64px">🛒</div>
    <h4 class="fw-bold mt-2">Keranjang masih kosong</h4>
    <p class="text-muted">Yuk belanja produk lokal Bengkulu dulu, dukung UMKM!</p>
    <div class="d-grid"><a href="index.php" class="btn-orange text-decoration-none text-center">Belanja Sekarang →</a></div>
    <div class="row g-2 mt-4 text-start">
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">🍌 <b>Keripik Pisang</b><br><small class="text-muted">Rp 25.000 • ⭐ 4.9</small></div></div>
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">👜 <b>Tas Anyaman</b><br><small class="text-muted">Rp 85.000 • ⭐ 4.8</small></div></div>
      <div class="col-md-4"><div class="bg-light rounded-4 p-3">👘 <b>Batik Bengkulu</b><br><small class="text-muted">Rp 150.000 • ⭐ 5.0</small></div></div>
    </div>
  </div>
  <?php else: ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card card-x p-3">
        <div class="d-flex justify-content-between align-items-center mb-2 px-2"><b><?= count($items) ?> Produk</b><a href="?kosongkan=1" class="text-danger small text-decoration-none" onclick="return confirm('Kosongkan keranjang?')">🗑️ Kosongkan</a></div>
        <?php foreach($items as $it):
          $emoji = str_contains(strtolower($it['nama']),'keripik')?'🍌':(str_contains(strtolower($it['nama']),'tas')?'👜':(str_contains(strtolower($it['nama']),'batik')?'👘':'🎁'));
        ?>
        <div class="d-flex gap-3 align-items-center p-3 border-bottom">
          <div class="item-img"><?= $emoji ?></div>
          <div class="flex-grow-1">
            <b><?= htmlspecialchars($it['nama']) ?></b><br>
            <small class="text-muted">Rp <?= number_format($it['harga'],0,',','.') ?> / pcs • Stok: <?= $it['stok'] ?></small><br>
            <span class="badge bg-success-subtle text-success rounded-pill mt-1">✅ Ready</span>
          </div>
          <div class="text-end">
            <b class="text-success">Rp <?= number_format($it['subtotal'],0,',','.') ?></b><br>
            <small class="text-muted">Qty: <?= $it['qty'] ?></small><br>
            <a href="?hapus=<?= $it['id'] ?>" class="text-danger small text-decoration-none">Hapus ✕</a>
          </div>
        </div>
        <?php endforeach; ?>
        <div class="promo mt-3">🎉 <b>GRATIS ONGKIR</b> untuk pembelian di atas Rp 200.000 di wilayah Kota Bengkulu!</div>
      </div>
    </div>

    <div class="col-lg-4">
      <form method="POST" class="card card-x p-4 sticky-top" style="top:80px">
        <h5 class="fw-bold">Ringkasan Belanja</h5>
        <div class="d-flex justify-content-between py-1"><span>Subtotal (<?= $jml_item ?> item)</span><span>Rp <?= number_format($total,0,',','.') ?></span></div>
        <div class="d-flex justify-content-between py-1"><span>Ongkir Bengkulu</span><span>Rp <?= number_format($ongkir,0,',','.') ?></span></div>
        <div class="d-flex justify-content-between py-1"><span>Diskon UMKM</span><span class="text-success">- Rp 0</span></div>
        <hr>
        <div class="d-flex justify-content-between"><b>Total</b><h4 class="fw-bold text-success mb-0">Rp <?= number_format($grand,0,',','.') ?></h4></div>
        <label class="mt-3 fw-semibold small">📍 Alamat Pengiriman</label>
        <textarea name="alamat" class="form-control input-alamat" rows="3" placeholder="Nama, Jalan, Kecamatan, Kota Bengkulu, No HP..." required></textarea>
        <div class="d-flex gap-2 mt-3 small text-muted"><span>💳 COD</span><span>🏦 Transfer</span><span>📱 QRIS</span></div>
        <button class="btn-orange mt-3" name="checkout">Checkout Sekarang →</button>
        <p class="text-center text-muted small mt-2">🔒 Pembayaran aman • Pesanan langsung masuk ke penjual</p>
      </form>
    </div>
  </div>
  <?php endif; ?>
  <p class="text-center text-muted small mt-4">PasarBengkulu © 2026 • Universitas Muhammadiyah Bengkulu • Dukung UMKM Bengkulu 💛</p>
</div>
</body>
</html>
