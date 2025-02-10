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
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary mb-2">Form Peminjaman</h6>
                <!-- Button trigger modal for Add -->
                <button type="button" class="btn btn-primary btn-block" id="addPeminjamanButton">
                    Pilih Barang
                </button>
                <!-- Input field for Student Search -->
                <div class="form-group mt-2">
                    <button type="button" class="btn btn-primary btn-block mt-2" id="searchStudentButton">
                        Cari Siswa
                    </button>
                </div>
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
                <form action="{{ route('peminjaman.create') }}" method="POST" id="peminjamanForm">
                    @csrf
                    <h5 class="mt-3">Barang yang Dipilih</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="selectedItemsTable">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selectedItemsBody">
                                <!-- Barang yang dipilih akan muncul di sini -->
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-3">Siswa yang Dipilih</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="selectedStudentsTable">
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selectedStudentsBody">
                                <!-- Siswa yang dipilih akan muncul di sini -->
                            </tbody>
                        </table>
                    </div>   

                    <!-- Input field for Date -->
                    <div class="form-group mt-2">
                        <label class="m-0 font-weight-bold text-primary mb-2" for="pb_harus_kembali_tgl">Tanggal Pengembalian Barang</label>
                        <input type="date" class="form-control" id="pb_harus_kembali_tgl" name="pb_harus_kembali_tgl" required>
                    </div>

                    <!-- Hidden input fields to store selected items and students -->
                    <input type="hidden" name="br_kode" id="selectedItemsInput">
                    <input type="hidden" name="siswa_id" id="selectedStudentsInput">

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Barang -->
<div class="modal fade" id="daftarBarangModal" tabindex="-1" role="dialog" aria-labelledby="daftarBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="daftarBarangModalLabel">Daftar Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="searchBarang">Cari Barang</label>
                    <input type="text" class="form-control" id="searchBarang" placeholder="Cari Barang...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- Data will be populated by search -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveSelectedItems">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Student Search -->
<div class="modal fade" id="searchStudentModal" tabindex="-1" role="dialog" aria-labelledby="searchStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchStudentModalLabel">Cari Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="searchStudent">Cari Siswa</label>
                    <input type="text" class="form-control" id="searchStudent" placeholder="Cari Siswa...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Pilih</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <!-- Data will be populated by search -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveSelectedStudents">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    let selectedItems = [];
    let selectedStudent = null;

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('searchBarang').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            fetch('/search-barang?query=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('barangTableBody');
                    tbody.innerHTML = ''; // Kosongkan tabel sebelum menampilkan hasil

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada barang ditemukan</td></tr>';
                    } else {
                        data.forEach(barang => {
                            if (barang.status_barang == 1) { // Hanya barang tersedia
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td><input class="barang-checkbox" type="checkbox" value="${barang.br_kode}" data-name="${barang.br_nama}" data-status="${barang.status_barang == 1 ? 'Tersedia' : 'Dipinjam'}"></td>
                                    <td>${barang.br_kode}</td>
                                    <td>${barang.br_nama}</td>
                                    <td>${barang.status_barang == 1 ? 'Tersedia' : 'Dipinjam'}</td>
                                `;
                                tbody.appendChild(row);
                            }
                        });
                    }
                });
        });

        document.getElementById('searchStudent').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            fetch('/search-siswa?query=' + searchTerm)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('studentTableBody');
                    tbody.innerHTML = ''; 

                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada siswa ditemukan</td></tr>';
                    } else {
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><input class="student-radio" type="radio" name="selectedStudent" value="${student.id}" data-name="${student.nama}" data-nis="${student.nis}"></td>
                                <td>${student.nis}</td>
                                <td>${student.nama}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                });
        });

        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('barang-checkbox')) {
                const checkbox = event.target;
                const barangId = checkbox.value;
                const barangName = checkbox.getAttribute('data-name') || 'Nama Tidak Ditemukan';
                const barangStatus = checkbox.getAttribute('data-status') || 'Status Tidak Diketahui';

                const barangObj = { br_kode: barangId, name: barangName, status: barangStatus };

                if (checkbox.checked) {

                    selectedItems = selectedItems.filter(item => item !== barangId);

                    const isAlreadySelected = selectedItems.some(item => item.br_kode === barangId);
                    if (!isAlreadySelected) {
                        selectedItems.push(barangObj);
                    }
                } else {
                    selectedItems = selectedItems.filter(item => item.br_kode !== barangId);
                }

                console.log('Selected Items:', selectedItems);
                updateSelectedItemsTable(); 
            }

            if (event.target.classList.contains('student-radio')) {
                const radio = event.target;
                const studentId = radio.value;
                const studentNis = radio.getAttribute('data-nis');
                const studentName = radio.getAttribute('data-name') || 'Nama Tidak Ditemukan';

                selectedStudent = { id: studentId, name: studentName, nis: studentNis };

                console.log('Selected Student:', selectedStudent);
                updateSelectedStudentsTable(); // Memperbarui tampilan tabel
            }
        });

        // Update tabel barang yang dipilih
        function updateSelectedItemsTable() {
            const tbody = document.getElementById('selectedItemsBody');
            tbody.innerHTML = ''; // Kosongkan tabel sebelum update

            if (selectedItems.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">Belum ada barang yang dipilih</td></tr>';
            } else {
                selectedItems.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${item.br_kode}</td>
                        <td>${item.name}</td>
                        <td><button class="btn btn-danger btn-sm remove-item" data-id="${item.br_kode}">Hapus</button></td>
                    `;
                    tbody.appendChild(row);
                });
            }
        }

        // Update tabel siswa yang dipilih
        function updateSelectedStudentsTable() {
            const tbody = document.getElementById('selectedStudentsBody');
            tbody.innerHTML = ''; // Kosongkan tabel sebelum update

            if (!selectedStudent) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">Belum ada siswa yang dipilih</td></tr>';
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${selectedStudent.nis}</td>
                    <td>${selectedStudent.name}</td>
                    <td><button class="btn btn-danger btn-sm remove-student" data-id="${selectedStudent.id}">Hapus</button></td>
                `;
                tbody.appendChild(row);
            }
        }

        // Event listener untuk menghapus barang dari daftar yang dipilih
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                const barangId = event.target.getAttribute('data-id');
                selectedItems = selectedItems.filter(item => item.br_kode !== barangId);
                updateSelectedItemsTable();
            }

            if (event.target.classList.contains('remove-student')) {
                selectedStudent = null;
                updateSelectedStudentsTable();
            }
        });

        // Simpan barang yang dipilih saat klik tombol "Simpan"
        document.getElementById('saveSelectedItems').addEventListener('click', function () {
            $('#daftarBarangModal').modal('hide');
            updateSelectedItemsTable();
            console.log('Barang yang dipilih:', selectedItems);
        });

        // Simpan siswa yang dipilih saat klik tombol "Simpan"
        document.getElementById('saveSelectedStudents').addEventListener('click', function () {
            $('#searchStudentModal').modal('hide');
            updateSelectedStudentsTable();
            console.log('Siswa yang dipilih:', selectedStudent);
        });

        // Tambah Peminjaman
        document.getElementById('addPeminjamanButton').addEventListener('click', function () {
            $('#daftarBarangModal').modal('show');
        });

        // Cari Siswa
        document.getElementById('searchStudentButton').addEventListener('click', function () {
            $('#searchStudentModal').modal('show');
        });

        // Handle form submission for Peminjaman
        document.getElementById('peminjamanForm').addEventListener('submit', function (event) {
            // Prevent the default form submission
            event.preventDefault();

            // Set the values of the hidden input fields
            document.getElementById('selectedItemsInput').value = JSON.stringify(selectedItems.map(item => item.br_kode));
            document.getElementById('selectedStudentsInput').value = selectedStudent.id;

            // Disable the submit button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // Submit the form
            this.submit();
        });

        // Handle form submission for Peminjaman
        document.getElementById('peminjamanForm').addEventListener('submit', function () {
            // You can now send the selectedItems and selectedStudent to your server or handle it as needed
            console.log('Form Submitted with Selected Items:', selectedItems.map(item => item.br_kode));
            console.log('Form Submitted with Selected Student:', selectedStudent.id);
        });
    });
</script>

@endsection
