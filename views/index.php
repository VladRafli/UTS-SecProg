<!DOCTYPE html>
<html lang="en">

<head>
    <title>E-Commerce Handal</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('home.css') ?>">
</head>

<body>
    <header>
        <h1>E-Commerce Handal</h1>
    </header>
    <main>
        <h1>Hasil Query Transaksi User</h1>
        <table>
            <tr>
                <th>kode_transaksi</th>
                <th>nama</th>
                <th>alamat_pengiriman</th>
                <th>total_transaksi</th>
                <th>diskon</th>
                <th>Total</th>
            </tr>
            <?php foreach($data as $row) { ?>
            <tr>
                <th><?= $row["kode_transaksi"] ?></th>
                <th><?= $row["Nama"] ?></th>
                <th><?= $row["alamat_pengiriman"] ?></th>
                <th><?= $row["total_transaksi"] ?></th>
                <th><?= $row["diskon"] ?>%</th>
                <th><?= $row["total_transaksi"] - ($row["total_transaksi"] * $row["diskon"] / 100) ?></th>
            </tr>
            <?php } ?>
        </table>
    </main>
</body>

</html>