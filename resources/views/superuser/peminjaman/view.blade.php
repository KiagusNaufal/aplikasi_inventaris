@extends('layouts.admin')

@section('main-content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-4">
                <h6 class="m-0 font-weight-bold text-primary mb-2">Data Peminjaman</h6>
                <a class="btn btn-primary" href="{{ route('peminjaman.store') }}">
                    Tambah Peminjaman
                </a>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger border-left-danger" role="alert">
                <ul class="pl-4 my-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Tanggal Kembali</th>
                                <th>Aksi</th>
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
                                    <button class="btn btn-info view-detail" 
                                        data-barang="{{ json_encode($item->detailPeminjaman->map(function($detail) {
                                            return [
                                                'br_kode' => $detail->barangInventaris->br_kode ?? 'N/A',
                                                'br_nama' => $detail->barangInventaris->br_nama ?? 'N/A'
                                            ];
                                        })) }}">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="barangDetailContent">
                    <!-- Data barang akan dimasukkan di sini oleh jQuery -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Load jQuery & Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('.view-detail').on('click', function() {
            var barang = $(this).data('barang');

            let barangHTML = "";
            if (barang.length > 0) {
                barang.forEach(item => {
                    barangHTML += `<p><strong>Kode Barang:</strong> ${item.br_kode}</p>
                                   <p><strong>Nama Barang:</strong> ${item.br_nama}</p>
                                   <hr>`;
                });
            } else {
                barangHTML = "<p>Tidak ada data barang.</p>";
            }

            $('#barangDetailContent').html(barangHTML);
            $('#viewModal').modal('show');
        });
    });
</script>

@endsection
