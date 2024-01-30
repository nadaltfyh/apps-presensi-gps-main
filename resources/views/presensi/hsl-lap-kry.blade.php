<!-- resources/views/admin/laporan/index.blade.php -->

@extends('layouts.admin.tabler')

@section('content')

<div class="container">
    <h2>Data Laporan</h2>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form id="zipForm" action="{{ route('laporan.admin.download.selected') }}" method="post">
        @csrf
        <div class="row mt-3">
        <div class="col-lg-12">
        <table id="laporanTable" class="table">
            <thead>
                <tr>
                    <th></th> <!-- Kolom Checkbox -->
                    <th>Judul</th>
                    <th>Created At</th>
                    <th>Update At</th>
                    <th>Actions</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                @foreach($laporanData as $laporan)
                    <tr>
                        <td>
                            
                        </td>
                        <td>{{ $laporan->judul }}</td>
                        <td>{{ $laporan->created_at }}</td>
                        <td>{{ $laporan->updated_at }}</td>
                        <td>
                            <div class="btn-group">
                                <form action="/presensi/hsl-lap-kry/{{ $laporan->id }}/delete"
                                    method="POST" style="margin-left: 5px">
                                    @csrf
                                    {{-- btn btn-danger btn-sm  --}}
                                    <a href="#" class="delete-confirm text-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-trash"
                                    width="24" height="24" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z"
                                    fill="none"></path>
                                    <path d="M4 7l16 0"></path>
                                    <path d="M10 11l0 6"></path>
                                    <path d="M14 11l0 6"></path>
                                    <path
                                    d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12">
                                    </path>
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3">
                                    </path>
                                    </svg>
                                     </a>
                                     
                                    {{--  btn btn-success btn-sm --}}
                                    <a href="{{ url('hsl-lap-kry/'.$laporan->file_path) }}" download="{{$laporan->file_path}}" class="judul">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                    </a>
                                </form>
                                                            
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        </div>
        <button type="button" class="btn btn-primary" onclick="downloadSelected()">Download ZIP</button>
    </form>
</div>
@endsection

@push('myscript')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- Script untuk inisialisasi DataTables dan operasi CRUD -->
<script>
    $(document).ready(function() {
        $('#laporanTable').DataTable();
    });

    
    $(function() {
            $(".delete-confirm").click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0054a6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Deleted!',
                            'File anda telah dihapus',
                            'success'
                        )
                    }
                })
            });

        });

    function downloadSelected() {
        // Submit form untuk mengunduh ZIP
        document.getElementById('zipForm').submit();
    }
</script>
@endpush