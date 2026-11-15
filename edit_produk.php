<?php
include 'config.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id < 1){ header("Location: admin_produk.php"); exit; }

// === CARI GAMBAR FLEKSIBEL - SAMA KAYAK DETAIL & ADMIN ===
function cariGambar($nama_file){
  $nama_file = trim($nama_file ?? '');
  if($nama_file != '' && file_exists('uploads/'.$nama_file)) return 'uploads/'.$nama_file;
  $dasar = str_replace(['.jpg.png','.png.png','.jpeg.png'], '', $nama_file);
  $dasar = pathinfo($dasar, PATHINFO_FILENAME);
  foreach(['jpg','jpeg','png','webp'] as $ext){
    if(file_exists('uploads/'.$dasar.'.'.$ext)) return 'uploads/'.$dasar.'.'.$ext;
  }
  if(is_dir('uploads')){
    foreach(scandir('uploads') as $f){
      if($f=='.'||$f=='..') continue;
      if(!preg_match('/\.(jpg|jpeg|png|webp)$/i', $f)) continue;
      $fl = strtolower($f); $dl = strtolower($dasar);
      if(strlen($dl)>=3 && strpos($fl, $dl)!==false) return 'uploads/'.$f;
      if($dl=='baytat' && (strpos($fl,'bay')!==false || strpos($fl,'tat')!==false)) return 'uploads/'.$f;
      if($dl=='lepek' && strpos($fl,'lepek')!==false) return 'uploads/'.$f;
      if($dl=='bolukoja' && (strpos($fl,'bolu')!==false || strpos($fl,'koja')!==false)) return 'uploads/'.$f;
    }
  }
  return '';
}

$p = $conn->query("SELECT produk.*, kategori.nama_kategori FROM produk LEFT JOIN kategori ON produk.kategori_id=kategori.id WHERE produk.id=$id")->fetch_assoc();
if(!$p){ header("Location: admin_produk.php"); exit; }

if(isset($_POST['simpan'])){
  $nama = $conn->real_escape_string($_POST['nama']);
  $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
  $harga = (int)$_POST['harga']; $stok = (int)$_POST['stok']; $kategori_id = (int)$_POST['kategori_id'];
  $gambar = $p['gambar'];

  // Kalau upload foto baru
  if(!empty($_FILES['gambar']['name'])){
    if(!is_dir('uploads')) mkdir('uploads');
    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
    if(in_array($ext, ['jpg','jpeg','png','webp'])){
      $gambar = time().'_'.preg_replace('/[^a-z0-9]/i','', strtolower($nama)).'.'.$ext;
      move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/'.$gambar);
    }
  }
  $conn->query("UPDATE produk SET nama='$nama', deskripsi='$deskripsi', harga=$harga, stok=$stok, kategori_id=$kategori_id, gambar='$gambar' WHERE id=$id");
  header("Location: admin_produk.php?edit=1"); exit;
}
$gambar_fix = cariGambar($p['gambar']);
?>
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit <?= htmlspecialchars($p['nama']) ?> - PasarBengkulu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb;color:#1e1b2e}
.card{border:none;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.07);overflow:hidden;background:#fff}
.input-modern{border-radius:14px;padding:12px 14px;border:1px solid #e5e7eb}
.input-modern:focus{border-color:#5b21b6;box-shadow:0 0 0 3px #ede9fe;outline:none}
.label{font-weight:700;font-size:13px;margin-bottom:6px;display:block}
.btn-simpan{background:#5b21b6;color:#fff;border-radius:999px;padding:14px;font-weight:800;width:100%;border:none;font-size:15px;box-shadow:0 8px 20px rgba(91,33,182,.3)}
.btn-simpan:hover{background:#4c1d95;color:#fff}
.btn-batal{background:#f3f4f6;color:#333;border-radius:999px;padding:12px;font-weight:700;width:100%;border:none;display:block;text-align:center;text-decoration:none}
.btn-batal:hover{background:#e5e7eb;color:#333}
.foto-preview{width:100%;height:320px;object-fit:cover;border-radius:18px}
.upload-box{border:2px dashed #d1d5db;border-radius:18px;padding:16px;text-align:center;cursor:pointer;background:#fafaff}
.upload-box:hover{border-color:#5b21b6;background:#f5f3ff}
.badge-cat{background:#ede9fe;color:#5b21b6;padding:6px 12px;border-radius:999px;font-weight:700;font-size:12px}
.preview-harga{background:linear-gradient(135deg,#5b21b6,#7c3aed);color:#fff;border-radius:18px;padding:16px}
</style>
</head><body>

<div class="container py-4" style="max-width:900px">
<a href="admin_produk.php" class="text-decoration-none" style="color:#5b21b6;font-weight:700">← Kembali ke Kelola Toko</a>

<div class="row g-4 mt-2">
<!-- KIRI: FOTO + PREVIEW -->
<div class="col-md-5">
  <div class="card p-3">
    <?php if($gambar_fix!=''){ ?>
      <img id="previewFoto" src="<?= $gambar_fix ?>" class="foto-preview">
    <?php } else { ?>
      <div id="previewFotoBox" class="foto-preview" style="background:#ede9fe;display:flex;flex-direction:column;align-items:center;justify-content:center"><span style="font-size:70px">📷</span><span class="text-muted">Belum ada foto</span></div>
      <img id="previewFoto" src="" class="foto-preview" style="display:none">
    <?php } ?>
    <div class="d-flex gap-2 mt-3">
      <span class="badge-cat"><?= htmlspecialchars($p['nama_kategori'] ?? 'Makanan') ?></span>
      <span class="badge bg-success rounded-pill">Stok: <?= $p['stok'] ?></span>
      <span class="badge bg-light text-dark rounded-pill">ID: #<?= $p['id'] ?></span>
    </div>
    <h5 id="previewNama" class="mt-3" style="font-weight:800"><?= htmlspecialchars($p['nama']) ?></h5>
    <p id="previewDeskripsi" class="text-muted" style="font-size:13px"><?= htmlspecialchars($p['deskripsi']) ?></p>
    <div class="preview-harga d-flex justify-content-between align-items-center">
      <div><small>Preview Harga</small><br><b id="previewHarga" style="font-size:22px">Rp <?= number_format($p['harga']) ?></b></div>
      <div style="font-size:28px">⭐ 4.9</div>
    </div>
  </div>
</div>

<!-- KANAN: FORM EDIT -->
<div class="col-md-7">
  <div class="card p-4">
    <h4 style="font-weight:800">✏️ Edit Produk</h4>
    <p class="text-muted" style="font-size:13px">Ubah data produk di bawah ini, lalu simpan.</p>

    <form method="POST" enctype="multipart/form-data">
      <label class="label">Nama Produk</label>
      <input type="text" name="nama" id="inputNama" class="form-control input-modern mb-3" value="<?= htmlspecialchars($p['nama']) ?>" oninput="document.getElementById('previewNama').innerText=this.value" required>

      <label class="label">Deskripsi</label>
      <textarea name="deskripsi" id="inputDeskripsi" class="form-control input-modern mb-3" rows="3" oninput="document.getElementById('previewDeskripsi').innerText=this.value" required><?= htmlspecialchars($p['deskripsi']) ?></textarea>

      <div class="row">
        <div class="col-6">
          <label class="label">Harga (Rp)</label>
          <input type="number" name="harga" id="inputHarga" class="form-control input-modern mb-3" value="<?= $p['harga'] ?>" oninput="document.getElementById('previewHarga').innerText='Rp ' + Number(this.value).toLocaleString('id-ID')" required>
        </div>
        <div class="col-6">
          <label class="label">Stok</label>
          <input type="number" name="stok" class="form-control input-modern mb-3" value="<?= $p['stok'] ?>" required>
        </div>
      </div>

      <label class="label">Kategori</label>
      <select name="kategori_id" class="form-control input-modern mb-3">
        <?php $k=$conn->query("SELECT * FROM kategori"); while($r=$k->fetch_assoc()){ $sel = ($r['id']==$p['kategori_id'])?'selected':''; echo "<option value='{$r['id']}' $sel>{$r['nama_kategori']}</option>"; } ?>
      </select>

      <label class="label">Ganti Foto Produk <small class="text-muted">(kosongkan kalau tidak mau ganti)</small></label>
      <label class="upload-box w-100 mb-3">
        <div style="font-size:28px">📸</div>
        <b>Klik untuk pilih foto baru</b><br>
        <small class="text-muted">JPG, PNG, WEBP - Maks 2MB</small><br>
        <small id="namaFile" style="color:#5b21b6;font-weight:700"></small>
        <input type="file" name="gambar" id="inputFoto" accept="image/*" style="display:none" onchange="gantiFoto(event)">
      </label>

      <button name="simpan" class="btn-simpan">💾 Simpan Perubahan</button>
      <a href="admin_produk.php" class="btn-batal mt-2">Batal</a>
    </form>
  </div>
</div>
</div>
</div>

<script>
function gantiFoto(e){
  const file = e.target.files[0];
  if(!file) return;
  document.getElementById('namaFile').innerText = 'Dipilih: ' + file.name;
  const reader = new FileReader();
  reader.onload = function(ev){
    const img = document.getElementById('previewFoto');
    img.src = ev.target.result;
    img.style.display = 'block';
    const box = document.getElementById('previewFotoBox');
    if(box) box.style.display = 'none';
  }
  reader.readAsDataURL(file);
}
</script>
</body></html>
