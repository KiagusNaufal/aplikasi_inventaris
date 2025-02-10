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
                <form method="GET" action="{{ route('laporan.pengembalian') }}" class="mb-2">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="siswa" class="form-control" placeholder="Nama Siswa" value="{{ request('siswa') }}">
                        </div>
                        <div class="col">
                            <select name="kembali_status" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="1" {{ request('kembali_status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('kembali_status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="date" name="kembali_tgl" class="form-control" value="{{ request('kembali_tgl') }}">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col">
                            <a href="{{ route('pdf.laporan.pengembalian', request()->all()) }}" class="btn btn-danger">Download PDF</a>
                        </div>
                    </div>
                </form>
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
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection