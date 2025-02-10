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
                <h6 class="m-0 font-weight-bold text-primary mb-2">Data Pengembalian</h6>
                <!-- Button trigger modal for Add -->
                <select id="siswaSelect" class="form-control">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
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
                                <th>No Peminjaman</th>
                                <th>Kembali Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengembalian as $item)
                            <tr>
                                <td>{{ $item->kembali_id }}</td>
                                <td>{{ $item->pb_id }}</td>
                                <td>{{ $item->kembali_tgl }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pengembalian.store') }}" method="POST">
                @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="pb_id">PB ID</label>
                    <input type="text" class="form-control" name="pb_id" id="pb_id" readonly>
                </div>
                <div class="form-group">
                    <label for="br_kode">Kode Barang</label>
                    <ul id="br_kode_list"></ul> 
                </div>
                <div class="form-group">
                    <label for="br_nama">Nama Barang</label>
                    <ul id="br_nama_list"></ul> 
                </div>
                <div class="form-group mt-2">
                    <label class="m-0 font-weight-bold text-primary mb-2" for="kembali_tgl">Tanggal Peminjaman</label>
                    <input type="date" class="form-control" id="kembali_tgl" name="kembali_tgl" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#siswaSelect').on('change', function() {
        var siswa_id = $(this).val();

        $.ajax({
            url: '/pengembalian', // Sesuaikan URL request
            method: 'GET',
            data: { siswa_id: siswa_id },
            success: function(response) {
                console.log(response); // Cek apakah data benar-benar datang

                if (response.length > 0) {
                    // Clear previous lists
                    $('#pb_id').val(response[0].pb_id);
                    $('#br_kode_list').empty();
                    $('#br_nama_list').empty();

                    // Loop untuk setiap data yang datang dan tambahkan ke list
                    response.forEach(function(item) {
                        $('#br_kode_list').append('<li>' + item.br_kode + '</li>');
                        $('#br_nama_list').append('<li>' + item.br_nama + '</li>');
                    });

                    // Tampilkan modal
                    $('#itemModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan: ", error);
            }
        });
    });

    $('#submitBtn').on('click', function() {
        $(this).prop('disabled', true);
        $(this).closest('form').submit();
    });
});
</script>
@endsection
