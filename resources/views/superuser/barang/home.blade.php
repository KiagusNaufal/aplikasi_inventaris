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
                <h6 class="m-0 font-weight-bold text-primary mb-2">Data Barang Inventaris</h6>
                <!-- Button trigger modal for Add -->
                <button type="button" class="btn btn-primary" id="addButton">
                    Tambah Barang
                </button>
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
                                <th>Barang Tanggal Terima</th>
                                <th>Barang Tanggal Entry</th>
                                <th>Barang Status</th>
                                <th>Barang Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                            <tr>
                                <td>{{ $item->br_kode }}</td>
                                <td>{{ $item->br_nama }}</td>
                                <td>{{ $item->br_tgl_nerima }}</td>
                                <td>{{ $item->br_tgl_entry }}</td>
                                <td>{{ $item->status_barang == 1 ? 'Tersedia' : 'Dipinjam' }}</td>
                                <td>{{ $item->kondisi_barang == 1 ? 'Bagus' : 'Jelek' }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm editButton"
                                        data-id="{{ $item->id }}"
                                        data-br_nama="{{ $item->br_nama }}"
                                        data-br_tgl_nerima="{{ $item->br_tgl_nerima }}"
                                        data-status_barang="{{ $item->status_barang }}"
                                        data-kondisi_barang="{{ $item->kondisi_barang }}"
                                        data-vendor_id="{{ $item->vendor_id }}"
                                        data-jns_barang_kode="{{ $item->jns_barang_kode }}">
                                        Edit
                                    </button>
                                    <form action="" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
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
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="itemForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                <input type="hidden" id="itemId" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="br_nama">Nama Barang</label>
                        <input type="text" class="form-control" id="br_nama" name="br_nama" required>
                    </div>
                    <div class="form-group">
                        <label for="br_tgl_nerima">Tanggal Terima</label>
                        <input type="date" class="form-control" id="br_tgl_nerima" name="br_tgl_nerima" required>
                    </div>
                    <div class="form-group">
                        <label for="status_barang">Status Barang</label>
                        <select class="form-control" id="status_barang" name="status_barang" required>
                            <option value="1">Tersedia</option>
                            <option value="0">Dipinjam</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vendor_id">Vendor</label>
                        <select class="form-control" id="vendor_id" name="vendor_id" required>
                            @foreach($vendor as $vendors)
                                <option value="{{ $vendors->id }}">{{ $vendors->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jns_barang_kode">Jenis Barang</label>
                        <select class="form-control" id="jns_barang_kode" name="jns_barang_kode" required>
                            @foreach($jenis as $jeniss)
                                <option value="{{ $jeniss->jns_barang_kode }}">{{ $jeniss->jns_barang_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kondisi_barang">Kondisi Barang</label>
                        <select class="form-control" id="kondisi_barang" name="kondisi_barang" required>
                            <option value="1">Bagus</option>
                            <option value="0">Jelek</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tambah Barang
    document.getElementById('addButton').addEventListener('click', function () {
        resetForm();
        document.getElementById('itemForm').action = "{{ route('barang-inventaris.create') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('itemModalLabel').innerText = 'Tambah Barang';
        $('#itemModal').modal('show');
    });

    // Edit Barang
    document.querySelectorAll('.editButton').forEach(button => {
        button.addEventListener('click', function () {
            resetForm();
            const item = this.dataset;

            document.getElementById('itemId').value = item.id;
            document.getElementById('br_nama').value = item.br_nama;
            document.getElementById('br_tgl_nerima').value = item.br_tgl_nerima;
            document.getElementById('status_barang').value = item.status_barang;
            document.getElementById('kondisi_barang').value = item.kondisi_barang;
            document.getElementById('vendor_id').value = item.vendor_id;
            document.getElementById('jns_barang_kode').value = item.jns_barang_kode;

            document.getElementById('itemForm').action = `/barang-inventaris/${item.id}`;
            document.getElementById('methodField').value = 'POST';
            document.getElementById('itemModalLabel').innerText = 'Edit Barang';
            $('#itemModal').modal('show');
        });
    });

    // Reset Form
    function resetForm() {
        document.getElementById('itemForm').reset();
        document.getElementById('methodField').value = 'POST';
        document.getElementById('itemId').value = '';
    }
</script>
@endsection

