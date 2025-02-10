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
                <h6 class="m-0 font-weight-bold text-primary mb-2">Laporan Data Barang Inventaris</h6>

                <form method="GET" action="{{ route('laporan.barang') }}" class="mt-3">
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="search" class="form-control" placeholder="Cari Nama Barang" value="{{ request('search') }}">
                        </div>
                        <div class="col">
                            <input type="text" name="vendor" class="form-control" placeholder="Cari Nama Vendor" value="{{ request('vendor') }}">
                        </div>
                        <div class="col">
                            <select name="status_barang" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="1" {{ request('status_barang') == '1' ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ request('status_barang') == '0' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                        </div>
                        <div class="col">
                            <select name="kondisi_barang" class="form-control">
                                <option value="">Pilih Kondisi</option>
                                <option value="1" {{ request('kondisi_barang') == '1' ? 'selected' : '' }}>Bagus</option>
                                <option value="0" {{ request('kondisi_barang') == '0' ? 'selected' : '' }}>Jelek</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" value="{{ request('br_tgl_nerima') }}" name="br_tgl_nerima">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col">
                            <a href="{{ route('pdf.laporan.barang', request()->all()) }}" class="btn btn-danger">Download PDF</a>
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
                                <th>Nama Barang</th>
                                <th>Vendor</th>
                                <th>Barang Tanggal Terima</th>
                                <th>Barang Tanggal Entry</th>
                                <th>Barang Status</th>
                                <th>Barang Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                            <tr>
                                <td>{{ $item->br_kode }}</td>
                                <td>{{ $item->br_nama }}</td>
                                <td>{{ $item->vendor->name }}</td>
                                <td>{{ $item->br_tgl_nerima }}</td>
                                <td>{{ $item->br_tgl_entry }}</td>
                                <td>{{ $item->status_barang == 1 ? 'Tersedia' : 'Dipinjam' }}</td>
                                <td>{{ $item->kondisi_barang == 1 ? 'Bagus' : 'Jelek' }}</td>
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