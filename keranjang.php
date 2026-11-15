<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif;background:#f6f7fb}.card{border:none;border-radius:20px;box-shadow:0 8px 25px rgba(0,0,0,.06)}</style>
</head><body>
<div class="container mt-4">
<a href="index.php">← Lanjut Belanja</a>
<h3 class="mt-2" style="font-weight:800">🛒 Keranjang Belanja</h3>
<?php if(empty($_SESSION['keranjang'])){ echo "<div class='card p-5 text-center mt-3'><div style='font-size:60px'>🛒</div><h5>Keranjang masih kosong</h5><p class='text-muted'>Yuk belanja produk lokal Bengkulu dulu</p><a href='index.php' class='btn btn-dark rounded-pill'>Belanja Sekarang</a></div>"; } else { ?>
<div class="row mt-3"><div class="col-md-8"><div class="card p-3">
<table class="table align-middle"><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
<?php $total=0; foreach($_SESSION['keranjang'] as $pid=>$jml){ $p=$conn->query("SELECT * FROM produk WHERE id=$pid")->fetch_assoc(); $sub=$p['harga']*$jml; $total+=$sub; ?>
<tr><td><b><?= $p['nama'] ?></b></td><td>Rp <?= number_format($p['harga']) ?></td><td><span class="badge bg-light text-dark border"><?= $jml ?></span></td><td><b>Rp <?= number_format($sub) ?></b></td><td><a href="?hapus=<?= $pid ?>" class="btn btn-danger btn-sm rounded-pill">Hapus</a></td></tr>
<?php } ?></table></div></div>
<div class="col-md-4"><div class="card p-4"><h5 style="font-weight:800">Ringkasan Belanja</h5><div class="d-flex justify-content-between"><span>Total</span><b style="color:#5b21b6;font-size:20px">Rp <?= number_format($total) ?></b></div><hr><form method="POST"><textarea name="alamat_kirim" class="form-control mb-3" placeholder="Isi alamat kirim lengkap, contoh: Jl. Bali No. 10, Bengkulu" rows="3" required></textarea><button name="checkout" class="btn w-100 text-white rounded-pill py-2" style="background:#5b21b6;font-weight:700">Buat Pesanan Sekarang →</button></form><p class="text-center text-muted mt-2" style="font-size:12px">🔒 Pembayaran aman & terpercaya</p></div></div></div>
<?php } ?>
</div></body></html>