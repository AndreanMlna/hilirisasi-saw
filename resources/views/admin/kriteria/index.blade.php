@extends('layouts.app')
@section('title', 'SPK SAW | Kriteria')
@section('css')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" >
@endsection
@section('content')
    <div class="row">
        <!-- Kolom Tambah Kriteria -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <a href="#tambahkriteria" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Kriteria</h6>
                </a>
                <div class="collapse show" id="tambahkriteria">
                    <div class="card-body">
                        @if (Session::has('msg'))
                            <div class="alert alert-info alert-dismissible">
                                <strong>Info!</strong> {{ Session::get('msg') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                        @endif
                        <form action="{{ route('kriteria.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="nama">Nama Kriteria</label>
                                <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror" name="nama_kriteria">
                                @error('nama_kriteria')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="attribut">Attribut Kriteria</label>
                                <select name="attribut" id="attribut" class="form-control"  @error('attribut') is-invalid @enderror" required>
                                    <option >Benefit</option>
                                    <option >Cost</option>
                                </select>

                                @error('attribut')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bobot">Bobot Kriteria</label>
                                <input type="text" class="form-control @error('bobot') is-invalid @enderror" name="bobot">
                                @error('bobot')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>  
            </div>
        </div> <!-- ✅ TAG DITUTUP DENGAN BENAR -->

        <!-- Kolom List Kriteria -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <a href="#listkriteria" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary">List Kriteria</h6>
                </a>
                <div class="collapse show" id="listkriteria">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="DataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kriteria</th>
                                        <th>Attribut</th>
                                        <th>Bobot</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($kriteria as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $row->nama_kriteria }}</td>
                                            <td>{{ $row->attribut }}</td>
                                            <td>{{ $row->bobot }}</td>
                                            <td>
                                                <a href="{{ route('kriteria.show', $row->id) }}" class="btn btn-sm btn-circle btn-info"><i class="fa fa-eye"></i></a>
                                                <a href="{{ route('kriteria.edit', $row->id) }}" class="btn btn-sm btn-circle btn-warning"><i class="fa fa-edit"></i></a>
                                                <form action="{{ route('kriteria.destroy', $row->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-circle btn-danger hapus"><i class="fa fa-trash"></i></button>
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
        </div> <!-- ✅ TAG DITUTUP DENGAN BENAR -->
    </div>
@stop
@section('js')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>

<script>
$(document).ready(function () {
    $('#DataTable').DataTable();

    $('.hapus').on('click', function (e) {
        e.preventDefault();

        const form = $(this).closest('form');
        const url = form.attr('action');
        const token = form.find('input[name="_token"]').val();
        const method = form.find('input[name="_method"]').val();

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Apakah kamu yakin?",
            text: "Sekali kamu hapus, tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim request AJAX
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: token,
                        _method: method
                    },
                    success: function (response) {
                        swalWithBootstrapButtons.fire({
                            title: "Deleted!",
                            text: "Data telah dihapus.",
                            icon: "success"
                        }).then(() => {
                            location.reload(); // Reload halaman untuk update tabel
                        });
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        swalWithBootstrapButtons.fire({
                            title: "Error",
                            text: "Terjadi kesalahan saat menghapus data.",
                            icon: "error"
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Datamu aman :)",
                    icon: "error"
                });
            }
        });
    });
});
</script>
@endsection