<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Inventaris</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Barang Inventaris</h2>
    <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>Kondisi</th>
                <th>Tanggal Terima</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $item)
                <tr>
                    <td>{{ $item->br_kode }}</td>
                    <td>{{ $item->br_nama }}</td>
                    <td>{{ $item->vendor->name }}</td>
                    <td>{{ $item->status_barang == 1 ? 'Tersedia' : 'Dipinjam' }}</td>
                    <td>{{ $item->kondisi_barang == 1 ? 'Bagus' : 'Jelek' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->br_tgl_nerima)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
