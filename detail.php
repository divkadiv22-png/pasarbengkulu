<?php
include 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
if ($id < 1) $id = 1;

$cadangan = [
  1 => ['nama'=>'Cookies', 'deskripsi'=>'Cookies coklat lumer khas Bengkulu, renyah di luar lembut di dalam. Cocok buat cemilan sore sama teh hangat.', 'harga'=>15000, 'stok'=>50, 'gambar'=>'cookies.jpg', 'kategori'=>'Kue', 'cari'=>['cookies','cookie']],
  2 => ['nama'=>'Tas Anyaman Pandan', 'deskripsi'=>'Tas anyaman pandan handmade Bengkulu, kuat, ringan dan cantik. Dianyam langsung oleh pengrajin lokal.', 'harga'=>75000, 'stok'=>20, 'gambar'=>'tas.jpg', 'kategori'=>'Kerajinan', 'cari'=>['tas','anyaman','pandan']],
  3 => ['nama'=>'Batik Bengkulu', 'deskripsi'=>'Batik motif Rafflesia khas Bengkulu, adem dan elegan. Bahan katun premium, nyaman dipakai seharian.', 'harga'=>120000, 'stok'=>10, 'gambar'=>'batik.jpg', 'kategori'=>'Fashion', 'cari'=>['batik']],
  4 => ['nama'=>'Lemea', 'deskripsi'=>'Rebung fermentasi pedas asam khas Bengkulu, wajib coba. Pedasnya nampol, asamnya segar, dimakan sama nasi hangat paling enak.', 'harga'=>18000, 'stok'=>25, 'gambar'=>'lemea.jpg', 'kategori'=>'Makanan', 'cari'=>['lemea']],
  5 => ['nama'=>'Lepek Binti', 'deskripsi'=>'Ketan kelapa bungkus daun pisang, gurih dan lembut. Manisnya pas, cocok buat sarapan atau oleh-oleh.', 'harga'=>10000, 'stok'=>35, 'gambar'=>'lepek.jpg', 'kategori'=>'Makanan', 'cari'=>['lepek','binti']],
  6 => ['nama'=>'Kue Bay Tat', 'deskripsi'=>'Kue selai nanas manis khas Bengkulu, oleh-oleh wajib. Kulitnya renyah, selainya manis legit, nagih!', 'harga'=>20000, 'stok'=>40, 'gambar'=>'baytat.jpg', 'kategori'=>'Kue', 'cari'=>['bay','tat','baytat','bay-tat','bay_tat']],
  7 => ['nama'=>'Gulai Tempoyak', 'deskripsi'=>'Gulai durian pedas gurih khas Bengkulu. Buat pecinta durian, ini surga! Gurih, pedas, wangi durian.', 'harga'=>35000, 'stok'=>15, 'gambar'=>'tempoyak.jpg', 'kategori'=>'Makanan', 'cari'=>['tempoyak','gulai']],
  8 => ['nama'=>'Bolu Koja', 'deskripsi'=>'Bolu pandan kelapa lembut khas Bengkulu. Wangi pandan asli, teksturnya lembut banget, nggak seret.', 'harga'=>22000, 'stok'=>30, 'gambar'=>'bolukoja.jpg', 'kategori'=>'Kue', 'cari'=>['bolu','koja','bolukoja']],
];

$p = null;
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
  $p = $result->fetch_assoc();
  if (empty($p['gambar']) && !empty($p['foto'])) $p['gambar'] = $p['foto'];
  if (empty($p['deskripsi']) && !empty($p['deskripsi_produk'])) $p['deskripsi'] = $p['deskripsi_produk'];
}
if (!$p) {
  if (isset($cadangan[$id])) { $p = $cadangan[$id]; $p['id'] = $id; }
  else { $p = $cadangan[1]; $p['id'] = 1; $id = 1; }
}
if (isset($cadangan[$id])) {
  if (empty($p['kategori'])) $p['kategori'] = $cadangan[$id]['kategori'];
  if (strlen($p['deskripsi'] ?? '') < 20) $p['deskripsi'] = $cadangan[$id]['deskripsi'];
  $p['cari'] = $cadangan[$id]['cari'];
}
$p['nama'] = $p['nama'] ?? 'Produk Bengkulu';
$p['harga'] = $p['harga'] ?? 0;
$p['stok'] = $p['stok'] ?? 0;
$p['deskripsi'] = $p['deskripsi'] ?? 'Produk asli khas Bengkulu.';
$p['gambar'] = $p['gambar'] ?? '';
$p['kategori'] = $p['kategori'] ?? 'Bengkulu';

// INI YANG DIPERBAIKI TOTAL - CARI GAMBAR FLEKSIBEL
function cariGambar($nama_file, $kata_kunci = []){
  $nama_file = trim($nama_file);
  // 1. Coba persis
  if($nama_file != '' && file_exists('uploads/'.$nama_file)) return 'uploads/'.$nama_file;
  // 2. Coba ganti ekstensi
  $dasar = str_replace(['.jpg.png','.png.png','.jpeg.png'], '', $nama_file);
  $dasar = pathinfo($dasar, PATHINFO_FILENAME);
  foreach(['jpg','jpeg','png','webp'] as $ext){
    if(file_exists('uploads/'.$dasar.'.'.$ext)) return 'uploads/'.$dasar.'.'.$ext;
  }
  // 3. SCAN SEMUA FILE DI UPLOADS, CARI YANG NAMANYA MENGANDUNG KATA KUNCI
  // Ini yang bikin Lepek Binti & Bay Tat yang namanya beda-beda tetap ketemu
  if(is_dir('uploads')){
    $files = scandir('uploads');
    // Kumpulkan kata kunci: dari nama file + dari cadangan + dari nama produk
    $kunci = [];
    if($dasar != '') $kunci[] = strtolower($dasar);
    foreach($kata_kunci as $k){ $kunci[] = strtolower($k); }
    // Misal lepek.jpg -> kunci lepek, baytat.jpg -> baytat, bay, tat
    foreach($files as $f){
      if($f=='.'||$f=='..') continue;
      $f_lower = strtolower($f);
      // Harus file gambar
      if(!preg_match('/\.(jpg|jpeg|png|webp)$/i', $f)) continue;
      foreach($kunci as $k){
        if($k != '' && strlen($k) >= 3 && strpos($f_lower, $k) !== false){
          return 'uploads/'.$f;
        }
      }
    }
  }
  return '';
}
$gambar_fix = cariGambar($p['gambar'], $p['cari'] ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($p['nama']) ?> - Bengkulu Marketplace</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb;color:#1e1b2e}
.badge-cat{background:#ede9fe;color:#5b21b6;padding:6px 12px;border-radius:999px;font-weight:700;font-size:12px}
.card{border:none;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.07);overflow:hidden}
.detail-img{width:100%;height:480px;object-fit:cover;display:block}
.thumb{width:80px;height:80px;object-fit:cover;border-radius:14px;border:3px solid #5b21b6;cursor:pointer}
.thumb2{width:80px;height:80px;object-fit:cover;border-radius:14px;border:2px solid #eee;cursor:pointer;opacity:.7}
.btn-beli{background:#5b21b6;color:#fff;border-radius:999px;padding:14px;font-weight:800;width:100%;border:none;font-size:16px;box-shadow:0 8px 20px rgba(91,33,182,.3)}
.btn-beli:hover{background:#4c1d95;color:#fff;transform:translateY(-1px)}
.btn-keranjang{background:#fff;color:#5b21b6;border:2px solid #5b21b6;border-radius:999px;padding:12px;font-weight:800;width:100%}
.qty-btn{width:44px;height:44px;border-radius:12px;border:1px solid #ddd;background:#fff;font-size:20px;font-weight:800}
.qty-input{width:70px;height:44px;text-align:center;border:1px solid #ddd;border-radius:12px;font-weight:800;font-size:18px}
.info-box{border:1px solid #eee;border-radius:16px;padding:12px;text-align:center;background:#fafaff}
.ulasan-ava{width:44px;height:44px;border-radius:50%;background:#ede9fe;display:flex;align-items:center;justify-content:center;font-weight:800;color:#5b21b6}
.card-kecil{border:none;border-radius:18px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,.06);background:#fff;transition:.2s}
.card-kecil:hover{transform:translateY(-4px)}
.card-kecil img{width:100%;height:150px;object-fit:cover}
</style>
</head>
<body>

<div class="container mt-4 mb-5">
<a href="index.php" class="text-decoration-none" style="color:#5b21b6;font-weight:700">← Kembali ke Beranda</a>

<div class="row mt-3 g-4">
<div class="col-md-6">
  <div class="card">
    <?php if($gambar_fix != ''){ ?>
      <img id="fotoUtama" src="<?= $gambar_fix ?>" class="detail-img" alt="<?= htmlspecialchars($p['nama']) ?>">
    <?php } else { ?>
      <div class="p-5 text-center" style="height:480px;display:flex;flex-direction:column;justify-content:center">
        <div style="font-size:90px">📷</div>
        <p>Foto <b><?= htmlspecialchars($p['gambar']) ?></b> belum ketemu.<br>
        File di uploads: 
        <?php 
          if(is_dir('uploads')){ $fs=scandir('uploads'); foreach($fs as $ff){ if($ff=='.'||$ff=='..') continue; echo $ff.', '; } }
          else echo 'folder uploads tidak ada!';
        ?>
        </p>
      </div>
    <?php } ?>
    <div class="p-3 d-flex gap-2">
      <?php if($gambar_fix != ''){ ?>
        <img src="<?= $gambar_fix ?>" class="thumb" onclick="document.getElementById('fotoUtama').src=this.src">
        <img src="<?= $gambar_fix ?>" class="thumb2" onclick="document.getElementById('fotoUtama').src=this.src">
        <img src="<?= $gambar_fix ?>" class="thumb2" onclick="document.getElementById('fotoUtama').src=this.src">
      <?php } ?>
      <div class="ms-auto align-self-center text-end"><span class="badge-cat"><?= htmlspecialchars($p['kategori']) ?></span><br><small class="text-muted">100% Asli Bengkulu</small></div>
    </div>
  </div>
  <div class="card p-4 mt-4">
    <h5 style="font-weight:800">📝 Deskripsi Produk</h5>
    <p class="text-muted"><?= htmlspecialchars($p['deskripsi']) ?></p>
    <div class="row text-center">
      <div class="col-4"><div class="info-box">🏠<br><b>UMKM Lokal</b><br><small>Pengrajin Bengkulu</small></div></div>
      <div class="col-4"><div class="info-box">🌿<br><b>Bahan Alami</b><br><small>Tanpa Pengawet</small></div></div>
      <div class="col-4"><div class="info-box">📦<br><b>Packing Aman</b><br><small>Double Wrap</small></div></div>
    </div>
  </div>
</div>

<div class="col-md-6">
  <div class="card p-4">
    <div class="d-flex gap-2">
      <span class="badge bg-success rounded-pill">✅ Stok Tersedia: <?= $p['stok'] ?></span>
      <span class="badge rounded-pill" style="background:#fef3c7;color:#92400e">🔥 Terlaris di Bengkulu</span>
    </div>
    <h2 class="mt-3" style="font-weight:800;font-size:32px"><?= htmlspecialchars($p['nama']) ?></h2>
    <p>⭐ <b>4.9</b> (128 ulasan) • Terjual <b>200+</b> • 📍 Bengkulu</p>
    <div class="d-flex align-items-end gap-2">
      <h3 style="font-weight:800;color:#5b21b6;font-size:34px;margin:0">Rp <?= number_format($p['harga']) ?></h3>
      <s class="text-muted">Rp <?= number_format($p['harga']*1.25) ?></s>
      <span class="badge bg-danger">-20%</span>
    </div>
    <p class="text-success mt-1" style="font-weight:700">🎉 Promo hari ini: Gratis Ongkir Bengkulu Kota!</p>
    <hr>
    <label class="fw-bold">Jumlah</label>
    <div class="d-flex align-items-center gap-2 my-2">
      <button type="button" class="qty-btn" onclick="ubah(-1)">-</button>
      <input id="qty" value="1" class="qty-input" readonly>
      <button type="button" class="qty-btn" onclick="ubah(1)">+</button>
      <span class="text-muted">Stok: <?= $p['stok'] ?></span>
    </div>
    <div class="d-flex justify-content-between p-3 rounded" style="background:#f6f7fb">
      <span>Total Harga</span>
      <b id="total" style="font-size:20px;color:#5b21b6">Rp <?= number_format($p['harga']) ?></b>
    </div>
    <form action="keranjang.php" method="POST" class="mt-3">
      <input type="hidden" name="produk_id" value="<?= $p['id'] ?>">
      <input type="hidden" name="jumlah" id="jumlahForm" value="1">
      <button class="btn-beli">🛒 Tambah ke Keranjang</button>
    </form>
    <button class="btn-keranjang mt-2" onclick="alert('Beli langsung <?= htmlspecialchars($p['nama']) ?> sebanyak ' + qty + ' - Total Rp ' + (harga*qty).toLocaleString('id-ID'))">⚡ Beli Sekarang</button>
    <div class="d-flex gap-2 mt-3 text-center" style="font-size:12px;color:#666">
      <span class="flex-fill border rounded p-2">🚚 Gratis Ongkir<br>Bengkulu Kota</span>
      <span class="flex-fill border rounded p-2">🛡️ Garansi<br>100% Original</span>
      <span class="flex-fill border rounded p-2">↩️ Retur<br>7 Hari</span>
    </div>
  </div>
  <div class="card p-4 mt-4">
    <h5 style="font-weight:800">⭐ Ulasan Pembeli (128)</h5>
    <div class="d-flex gap-3 mt-3">
      <div class="ulasan-ava">R</div>
      <div><b>Rina - Bengkulu</b><br>⭐⭐⭐⭐⭐<br><span class="text-muted">Rasanya enak banget, pedasnya pas! Bakal order lagi.</span></div>
    </div>
    <hr>
    <div class="d-flex gap-3">
      <div class="ulasan-ava" style="background:#fff7ed;color:#ea580c">A</div>
      <div><b>Andi - Curup</b><br>⭐⭐⭐⭐⭐<br><span class="text-muted">Packing rapi, pengiriman cepat, rasanya autentik Bengkulu.</span></div>
    </div>
  </div>
</div>
</div>

<h4 class="mt-5" style="font-weight:800">🛍️ Kamu Mungkin Juga Suka</h4>
<div class="row mt-3">
<?php foreach($cadangan as $cid => $c){ if($cid==$id) continue; $g = cariGambar($c['gambar'], $c['cari']); if($g=='') $g='https://via.placeholder.com/400x300?text='.urlencode($c['nama']); ?>
  <div class="col-6 col-md-3 mb-3">
    <a href="detail.php?id=<?= $cid ?>" class="text-decoration-none text-dark">
      <div class="card-kecil">
        <img src="<?= $g ?>">
        <div class="p-3"><b><?= htmlspecialchars($c['nama']) ?></b><br><span style="color:#5b21b6;font-weight:800">Rp <?= number_format($c['harga']) ?></span><br><small>⭐ 4.9 • Bengkulu</small></div>
      </div>
    </a>
  </div>
<?php } ?>
</div>
</div>

<script>
let harga = <?= $p['harga'] ?>;
let stok = <?= $p['stok'] ?>;
let qty = 1;
function ubah(n){
  qty += n;
  if(qty < 1) qty = 1;
  if(qty > stok) qty = stok;
  document.getElementById('qty').value = qty;
  document.getElementById('jumlahForm').value = qty;
  document.getElementById('total').innerText = 'Rp ' + (harga*qty).toLocaleString('id-ID');
}
</script>
</body></html>
