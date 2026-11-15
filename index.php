<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb;color:#1a1a2e}
.navbar{background:#fff!important;box-shadow:0 2px 20px rgba(0,0,0,.06);padding:14px 0}
.navbar-brand{font-weight:800;font-size:22px;color:#5b21b6!important}
.navbar-brand span{color:#f97316}
.hero{background:linear-gradient(135deg,#5b21b6 0%,#7c3aed 50%,#f97316 100%);border-radius:24px;padding:36px;color:white;margin-top:20px;overflow:hidden;position:relative}
.hero h1{font-weight:800;font-size:34px;line-height:1.2}
.hero p{opacity:.9}
.btn-hero{background:white;color:#5b21b6;font-weight:700;border-radius:999px;padding:10px 22px;border:none}
.cat-pill{background:white;border:1px solid #eee;border-radius:999px;padding:7px 16px;font-weight:600;font-size:13px}
.card-produk{border:none;border-radius:20px;overflow:hidden;box-shadow:0 8px 25px rgba(0,0,0,.06);transition:.3s;background:white}
.card-produk:hover{transform:translateY(-6px);box-shadow:0 15px 35px rgba(0,0,0,.12)}
.img-box{height:200px;display:flex;align-items:center;justify-content:center;font-size:70px;background:linear-gradient(135deg,#ede9fe,#fff7ed);overflow:hidden}
.img-box img{width:100%;height:100%;object-fit:cover;display:block}
.badge-stok{background:#dcfce7;color:#15803d;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700}
.harga{font-weight:800;font-size:19px;color:#5b21b6}
.btn-lihat{background:#5b21b6;color:white;border-radius:999px;font-weight:600;width:100%;padding:10px}
.btn-lihat:hover{background:#4c1d95;color:white}
.footer{margin-top:40px;text-align:center;color:#888;font-size:13px;padding-bottom:20px}
</style>
</head>
<body>

<nav class="navbar sticky-top"><div class="container d-flex justify-content-between align-items-center">
<a class="navbar-brand" href="#">Pasar<span>Bengkulu</span> 🛍️</a>
<div class="d-flex gap-2">
<a href="admin_produk.php" class="btn btn-outline-secondary rounded-pill px-3">Kelola Toko</a>
<a href="pesanan.php" class="btn btn-outline-secondary rounded-pill px-3">📦 Pesanan Saya</a>
<a href="keranjang.php" class="btn btn-dark rounded-pill px-4">🛒 Keranjang (<?= array_sum($_SESSION['keranjang']) ?>)</a>
</div></div></nav>

<div class="container">
  <div class="hero d-flex justify-content-between align-items-center flex-wrap">
    <div style="max-width:580px">
      <span class="badge bg-white text-dark rounded-pill px-3 py-2">✨ Marketplace Tradisional Bengkulu</span>
      <h1 class="mt-3">Belanja Produk Lokal, Langsung dari Pengrajin Bengkulu</h1>
      <p>Keripik, Batik, Anyaman Pandan dan produk UMKM lainnya. Tanpa perantara, harga jujur.</p>
      <button class="btn-hero" onclick="document.getElementById('produk').scrollIntoView({behavior:'smooth'})">Belanja Sekarang ↓</button>
    </div>
    <div style="font-size:110px">🎁</div>
  </div>

  <form method="GET" class="d-flex gap-2 mt-4">
    <input type="text" name="cari" class="form-control" placeholder="🔍 Cari keripik, batik, tas anyaman..." value="<?= $_GET['cari'] ?? '' ?>" style="border-radius:999px;padding:11px 18px">
    <button class="btn btn-dark rounded-pill px-4">Cari</button>
    <?php if(isset($_GET['cari'])) echo '<a href="index.php" class="btn btn-light rounded-pill px-3">Reset</a>'; ?>
  </form>

  <div class="d-flex gap-2 mt-4 flex-wrap">
    <span class="cat-pill">🍽️ Semua</span><span class="cat-pill">🍪 Makanan</span><span class="cat-pill">🧶 Kerajinan</span><span class="cat-pill">👕 Pakaian</span><span class="cat-pill">⭐ Terlaris</span>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-4 mb-3" id="produk">
    <h4 style="font-weight:800">Produk Pilihan Untuk Kamu</h4>
    <span class="text-muted" style="font-size:13px"><?= $conn->query("SELECT COUNT(*) as c FROM produk")->fetch_assoc()['c'] ?> produk tersedia</span>
  </div>

  <div class="row">

  <!-- 1. COOKIES -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/cookies.png" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 50</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Cookies</h5>
        <p class="text-muted" style="font-size:13px">Cookies khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 15.000</span>
          <span>⭐ 4.9</span>
        </div>
        <a href="detail.php?id=1" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 2. TAS -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/tas.png" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 20</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Tas Anyaman Pandan</h5>
        <p class="text-muted" style="font-size:13px">Tas anyaman handmade</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 75.000</span>
          <span>⭐ 4.9</span>
        </div>
        <a href="detail.php?id=2" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 3. BATIK -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/batik.png" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 10</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Batik Bengkulu</h5>
        <p class="text-muted" style="font-size:13px">Batik motif Rafflesia khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 120.000</span>
          <span>⭐ 4.9</span>
        </div>
        <a href="detail.php?id=3" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 4. LEMEA -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/Lemea.jpg" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 25</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Lemea</h5>
        <p class="text-muted" style="font-size:13px">Rebung fermentasi pedas asam khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 18.000</span>
          <span>⭐ 4.8</span>
        </div>
        <a href="detail.php?id=4" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 5. LEPEK BINTI -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/Lepek Binti.jpg" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 35</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Lepek Binti</h5>
        <p class="text-muted" style="font-size:13px">Ketan kelapa bungkus daun pisang khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 10.000</span>
          <span>⭐ 4.9</span>
        </div>
        <a href="detail.php?id=5" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 7. GULAI TEMPOYAK -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/Gulai Tempoyak.jpeg" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 15</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Gulai Tempoyak</h5>
        <p class="text-muted" style="font-size:13px">Gulai durian pedas gurih khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 35.000</span>
          <span>⭐ 4.8</span>
        </div>
        <a href="detail.php?id=7" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 8. BOLU KOJA -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/Bolu Koja.jpeg" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 30</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Bolu Koja</h5>
        <p class="text-muted" style="font-size:13px">Bolu pandan kelapa lembut khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 22.000</span>
          <span>⭐ 4.9</span>
        </div>
        <a href="detail.php?id=8" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

  <!-- 6. KUE BAY TAT -->
  <div class="col-md-4 mb-4">
    <div class="card-produk">
      <div class="img-box">
        <img src="uploads/Kue Bay Tat.jpeg" style="width:100%;height:100%;object-fit:cover">
      </div>
      <div class="p-3">
        <span class="badge-stok">Stok Tersedia: 40</span>
        <h5 class="mt-2 mb-1" style="font-weight:700">Kue Bay Tat</h5>
        <p class="text-muted" style="font-size:13px">Kue selai nanas manis khas Bengkulu</p>
        <div class="d-flex justify-content-between align-items-center">
          <span class="harga">Rp 20.000</span>
          <span>⭐ 5.0</span>
        </div>
        <a href="detail.php?id=6" class="btn btn-lihat mt-3">Lihat Detail →</a>
      </div>
    </div>
  </div>

</div>

</div>
  </div>
  <div class="footer">Dibuat untuk Tugas Web Marketplace Tradisional - Universitas Muhammadiyah Bengkulu • © 2026 PasarBengkulu</div>
</div>
</body>
</html>
