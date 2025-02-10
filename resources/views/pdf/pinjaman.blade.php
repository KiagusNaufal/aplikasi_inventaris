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
                <th>Nama Siswa</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Kembali</th>
                <th>Nama Barang dan Kode Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $item)
            <tr>
                <td>{{ $item->pb_id }}</td>
                <td>{{ $item->siswa->nama }}</td>
                <td>{{ $item->pb_tgl }}</td>
                <td>{{ $item->pb_harus_kembali_tgl }}</td>
                <td>
                    @foreach($item->detailPeminjaman as $detail)
                        {{ $detail->barangInventaris->br_kode ?? 'N/A' }} - {{ $detail->barangInventaris->br_nama ?? 'N/A' }}<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
        
        
    </table>
</body>
</html>
