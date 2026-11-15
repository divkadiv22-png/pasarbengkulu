<?php include 'config.php';
if(isset($_POST['tambah'])){
  $nama = $_POST['nama']; $deskripsi = $_POST['deskripsi']; $harga = $_POST['harga']; $stok = $_POST['stok']; $kategori_id = $_POST['kategori_id'];
  $conn->query("INSERT INTO produk(penjual_id,kategori_id,nama,deskripsi,harga,stok,gambar) VALUES(1,$kategori_id,'$nama','$deskripsi',$harga,$stok,'default.jpg')");
  header("Location: admin_produk.php"); exit;
}
if(isset($_GET['hapus'])){ $conn->query("DELETE FROM produk WHERE id=".$_GET['hapus']); header("Location: admin_produk.php"); exit; }
$total_produk = $conn->query("SELECT COUNT(*) as c FROM produk")->fetch_assoc()['c'];
$total_stok = $conn->query("SELECT SUM(stok) as s FROM produk")->fetch_assoc()['s'];
$total_pesanan = $conn->query("SELECT COUNT(*) as c FROM pesanan")->fetch_assoc()['c'];
$total_omset = $conn->query("SELECT SUM(total) as t FROM pesanan")->fetch_assoc()['t'];
?>
<!DOCTYPE html>
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb}
.sidebar{background:#fff;border-radius:20px;padding:20px;height:fit-content;position:sticky;top:20px;box-shadow:0 8px 25px rgba(0,0,0,.05)}
.menu{padding:11px 14px;border-radius:12px;display:block;color:#333;text-decoration:none;font-weight:600;margin-bottom:6px}
.menu.active{background:#5b21b6;color:white}
.menu:hover{background:#ede9fe;color:#5b21b6}
.stat{border:none;border-radius:20px;padding:18px;color:white}
.card{border:none;border-radius:20px;box-shadow:0 8px 25px rgba(0,0,0,.06)}
.input-modern{border-radius:12px;padding:10px 14px;border:1px solid #e5e7eb}
.btn-tambah{background:#5b21b6;color:white;border-radius:999px;padding:11px;font-weight:700;width:100%;border:none}
.btn-tambah:hover{background:#4c1d95;color:white}
.badge-cat{background:#ede9fe;color:#5b21b6;padding:5px 10px;border-radius:999px;font-size:12px;font-weight:700}
</style>
</head><body>
<div class="container-fluid p-3 p-md-4"><div class="row g-4">

<!-- SIDEBAR KIRI -->
<div class="col-md-3">
  <div class="sidebar">
    <h4 style="font-weight:800">Pasar<span style="color:#f97316">Bengkulu</span></h4>
    <p class="text-muted" style="font-size:13px">Dashboard Penjual • Toko Budi</p>
    <a href="admin_produk.php" class="menu active">📦 Kelola Produk</a>
    <a href="index.php" class="menu">🏪 Lihat Toko Saya</a>
    <a href="keranjang.php" class="menu">🛒 Keranjang</a>
    <hr>
    <div style="background:linear-gradient(135deg,#5b21b6,#f97316);color:white;border-radius:16px;padding:16px">
      <b>💡 Tips Jualan</b>
      <p style="font-size:13px;margin:0;margin-top:6px;opacity:.9">Foto produk yang bagus bikin pembeli 3x lebih tertarik. Tambah deskripsi yang jelas ya!</p>
    </div>
  </div>
</div>

<!-- KONTEN KANAN -->
<div class="col-md-9">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <div><h3 style="font-weight:800">Halo, Toko Budi 👋</h3><p class="text-muted">Ini ringkasan toko kamu hari ini.</p></div>
    <span class="badge bg-success rounded-pill px-3 py-2">● Toko Aktif</span>
  </div>

  <!-- STATISTIK -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat" style="background:linear-gradient(135deg,#5b21b6,#7c3aed)"><div style="font-size:26px">📦</div><h4 class="mb-0 mt-1"><?= $total_produk ?></h4><small>Total Produk</small></div></div>
    <div class="col-6 col-md-3"><div class="stat" style="background:linear-gradient(135deg,#f97316,#fb923c)"><div style="font-size:26px">📊</div><h4 class="mb-0 mt-1"><?= $total_stok ?></h4><small>Total Stok</small></div></div>
    <div class="col-6 col-md-3"><div class="stat" style="background:linear-gradient(135deg,#059669,#10b981)"><div style="font-size:26px">🧾</div><h4 class="mb-0 mt-1"><?= $total_pesanan ?></h4><small>Pesanan Masuk</small></div></div>
    <div class="col-6 col-md-3"><div class="stat" style="background:linear-gradient(135deg,#db2777,#f472b6)"><div style="font-size:26px">💰</div><h4 class="mb-0 mt-1">Rp <?= number_format($total_omset ?? 0) ?></h4><small>Total Omset</small></div></div>
  </div>

  <!-- FORM TAMBAH -->
  <div class="card p-4 mb-4">
    <h5 style="font-weight:800">✨ Tambah Produk Baru</h5>
    <p class="text-muted" style="font-size:13px">Lengkapi semua kolom di bawah ini.</p>
    <form method="POST">
      <div class="row">
        <div class="col-md-6"><label class="fw-bold" style="font-size:13px">Nama Produk</label><input type="text" name="nama" class="form-control input-modern mb-3" placeholder="Contoh: Kopi Robusta Bengkulu 250gr" required></div>
        <div class="col-md-6"><label class="fw-bold" style="font-size:13px">Kategori</label><select name="kategori_id" class="form-control input-modern mb-3"><?php $k=$conn->query("SELECT * FROM kategori"); while($r=$k->fetch_assoc()){ echo "<option value='{$r['id']}'>{$r['nama_kategori']}</option>"; } ?></select></div>
        <div class="col-md-6"><label class="fw-bold" style="font-size:13px">Harga (Rp)</label><input type="number" name="harga" class="form-control input-modern mb-3" placeholder="25000" required></div>
        <div class="col-md-6"><label class="fw-bold" style="font-size:13px">Stok</label><input type="number" name="stok" class="form-control input-modern mb-3" placeholder="30" required></div>
        <div class="col-12"><label class="fw-bold" style="font-size:13px">Deskripsi Produk</label><textarea name="deskripsi" class="form-control input-modern mb-3" rows="2" placeholder="Ceritain kelebihannya, misal: Kopi asli dari petani Bengkulu, aroma..." required></textarea></div>
      </div>
      <button name="tambah" class="btn-tambah">+ Tambah Produk ke Toko</button>
    </form>
  </div>

  <!-- TABEL PRODUK -->
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center"><h5 style="font-weight:800">📋 Daftar Produk Kamu</h5><span class="text-muted" style="font-size:13px"><?= $total_produk ?> produk</span></div>
    <div class="table-responsive mt-3">
    <table class="table align-middle">
      <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
      <?php $q=$conn->query("SELECT produk.*, kategori.nama_kategori FROM produk LEFT JOIN kategori ON produk.kategori_id=kategori.id"); while($p=$q->fetch_assoc()){
        $emoji = ($p['kategori_id']==1?'🍪':($p['kategori_id']==2?'🧶':'👕'));
      ?>
      <tr>
        <td><div class="d-flex align-items-center gap-2"><span style="font-size:28px;background:#f6f7fb;border-radius:12px;padding:6px 10px"><?= $emoji ?></span><div><b><?= $p['nama'] ?></b><br><small class="text-muted"><?= substr($p['deskripsi'],0,35) ?>...</small></div></div></td>
        <td><span class="badge-cat"><?= $p['nama_kategori'] ?></span></td>
        <td><b>Rp <?= number_format($p['harga']) ?></b></td>
        <td><?php if($p['stok']>10) echo "<span class='badge bg-success rounded-pill'>Stok Aman • $p[stok]</span>"; elseif($p['stok']>0) echo "<span class='badge bg-warning text-dark rounded-pill'>Stok Menipis • $p[stok]</span>"; else echo "<span class='badge bg-danger rounded-pill'>Habis</span>"; ?></td>
        <td><a href="edit_produk.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm rounded-pill px-3">Edit</a> <a href="?hapus=<?= $p['id'] ?>" onclick="return confirm('Yakin mau hapus <?= $p['nama'] ?>?')" class="btn btn-danger btn-sm rounded-pill px-3">Hapus</a></td>
      </tr>
      <?php } ?>
    </table>
    </div>
  </div>
  <p class="text-center text-muted mt-3" style="font-size:12px">PasarBengkulu • Dashboard Penjual © 2026</p>
</div>
</div></div>
</body></html>