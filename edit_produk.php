<?php include 'config.php'; $id=$_GET['id']; $p=$conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
if(isset($_POST['update'])){ $conn->query("UPDATE produk SET nama='{$_POST['nama']}', deskripsi='{$_POST['deskripsi']}', harga={$_POST['harga']}, stok={$_POST['stok']}, kategori_id={$_POST['kategori_id']} WHERE id=$id"); header("Location: admin_produk.php"); exit; } ?>
<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb}.card{border:none;border-radius:24px;box-shadow:0 10px 30px rgba(0,0,0,.07)}.input-modern{border-radius:12px;padding:11px 14px}</style>
</head><body>
<div class="container mt-4" style="max-width:650px">
<a href="admin_produk.php" class="text-decoration-none">← Kembali ke Kelola Toko</a>
<div class="card p-4 mt-3">
<h4 style="font-weight:800">✏️ Edit Produk</h4><p class="text-muted" style="font-size:13px">Ubah data produk di bawah ini, lalu simpan.</p>
<form method="POST">
<label class="fw-bold" style="font-size:13px">Nama Produk</label><input type="text" name="nama" value="<?= $p['nama'] ?>" class="form-control input-modern mb-3" required>
<label class="fw-bold" style="font-size:13px">Deskripsi</label><textarea name="deskripsi" class="form-control input-modern mb-3" rows="3"><?= $p['deskripsi'] ?></textarea>
<div class="row"><div class="col"><label class="fw-bold" style="font-size:13px">Harga</label><input type="number" name="harga" value="<?= $p['harga'] ?>" class="form-control input-modern mb-3" required></div><div class="col"><label class="fw-bold" style="font-size:13px">Stok</label><input type="number" name="stok" value="<?= $p['stok'] ?>" class="form-control input-modern mb-3" required></div></div>
<label class="fw-bold" style="font-size:13px">Kategori</label><select name="kategori_id" class="form-control input-modern mb-3"><?php $k=$conn->query("SELECT * FROM kategori"); while($r=$k->fetch_assoc()){ $sel=($r['id']==$p['kategori_id'])?'selected':''; echo "<option value='{$r['id']}' $sel>{$r['nama_kategori']}</option>"; } ?></select>
<button name="update" class="btn w-100 text-white rounded-pill py-2" style="background:#5b21b6;font-weight:700">Simpan Perubahan</button>
<a href="admin_produk.php" class="btn w-100 btn-light rounded-pill py-2 mt-2">Batal</a>
</form></div></div></body></html>