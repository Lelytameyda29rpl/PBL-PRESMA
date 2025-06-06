@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('prestasi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah Prestasi
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Status</label>
                    <select id="filter_status" class="form-control">
                        <option value="">- Semua -</option>
                        <option value="Pending">Pending</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <!-- <div class="col-md-3">
                        <label>Periode</label>
                        <select id="filter_periode" class="form-control">
                            <option value="">- Semua -</option>
                            @foreach ($listPeriode as $periode)
                                <option value="{{ $periode->id }}">{{ $periode->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Bidang Keahlian</label>
                        <select id="filter_keahlian" class="form-control">
                            <option value="">- Semua -</option>
                            @foreach ($listKeahlian as $keahlian)
                                <option value="{{ $keahlian->id }}">{{ $keahlian->keahlian }}</option>
                            @endforeach
                        </select>
                    </div> -->
            </div>

            <table class="table modern-table display nowrap" id="table_prestasi" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Prestasi</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Jumlah Mahasiswa</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal AJAX --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-hidden="true">
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function modalAction(url = '') {
            $('#myModal').modal('hide').removeData('bs.modal');
            $('#myModal').html('');
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var dataPrestasi;
        $(document).ready(function () {
            dataPrestasi = $('#table_prestasi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('prestasi/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function (d) {
                        d.status = $('#filter_status').val();
                        d.periode = $('#filter_periode').val();
                        d.keahlian = $('#filter_keahlian').val();
                    }
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nama_prestasi"
                },
                {
                    data: "status"
                },
                {
                    data: "catatan"
                },
                {
                    data: "jumlah_mahasiswa",
                    className: "text-center"
                },
                {
                    data: "action",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
                ]
            });
            $('#filter_status, #filter_periode, #filter_keahlian').on('change', function () {
                dataPrestasi.ajax.reload();
            });
        });

        function ubahStatus(id, aksi) {
            let url = `/prestasi/${id}/${aksi}_ajax`;
            let inputCatatan =
                `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (optional)"></textarea>`;
            let title = aksi === 'approve' ? 'Setujui Prestasi?' : 'Tolak Prestasi?';
            let text = "Tindakan ini akan mengubah status prestasi.";
            let icon = aksi === 'approve' ? 'success' : 'warning';
            let confirmButtonText = aksi === 'approve' ? 'Ya, Setujui!' : 'Ya, Tolak!';
            let confirmButtonColor = aksi === 'approve' ? '#28a745' : '#d33';

            // Jika status ditolak, buat textarea sebagai input wajib
            if (aksi === 'reject') {
                inputCatatan =
                    `<textarea id="catatan" class="form-control" placeholder="Masukkan catatan (wajib)" required></textarea>`;
            }

            $('#myModal').modal('hide');

            setTimeout(() => {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    html: inputCatatan,
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#6c757d',
                    cancelButtonText: 'Batal',
                    confirmButtonText: confirmButtonText,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let catatan = document.getElementById('catatan').value;
                        // Jika tidak ada catatan pada saat disetujui, beri kalimat default
                        if (aksi === 'approve' && !catatan) catatan = 'Tidak ada catatan';

                        $.post(url, {
                            _token: '{{ csrf_token() }}',
                            catatan: catatan // Kirim catatan ke server
                        }, function (res) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.success,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            // Refresh tabel di halaman index
                            if ($.fn.DataTable.isDataTable('#table_prestasi')) {
                                $('#table_prestasi').DataTable().ajax.reload(null,
                                    false); // reload tanpa reset halaman
                            }
                        }).fail(function () {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat memproses data.', 'error');
                        });
                    }
                });
            }, 500);
        }
    </script>
@endpush