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
    <h2>Laporan Pinjaman</h2>
    <p>Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Peminjaman</th>
                <th>Nama Siswa</th>
                <th>Kembali Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalian as $item)
            <tr>
                <td>{{ $item->kembali_id }}</td>
                <td>{{ $item->pb_id }}</td>
                <td>{{ $item->peminjaman->siswa->nama }}</td>
                <td>{{ $item->kembali_tgl }}</td>
            </tr>
            @endforeach
        </tbody>
        
        
    </table>
</body>
</html>
