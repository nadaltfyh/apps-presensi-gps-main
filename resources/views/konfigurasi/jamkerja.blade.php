@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Konfigurasi Jam Kerja
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahJK">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-user-plus" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                            <path d="M16 19h6"></path>
                                            <path d="M19 16v6"></path>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
                                        </svg>
                                        Tambah Data Jam Kerja
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-vcenter table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Jam</th>
                                                <th>Nama Jam</th>
                                                <th>Awal Jam Masuk</th>
                                                <th>Jam Masuk</th>
                                                <th>Akhir Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jamkerja as $d)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{$d->kode_jam_kerja}}</td>
                                                    <td>{{$d->nama_jam_kerja}}</td>
                                                    <td>{{$d->awal_jam_masuk}}</td>
                                                    <td>{{$d->jam_masuk}}</td>
                                                    <td>{{$d->akhir_jam_masuk}}</td>
                                                    <td>{{$d->jam_pulang}}</td>

                                                    <td align="center">
                                                        <div class="btn-group">
                                                            {{--  btn btn-info btn-sm --}}
                                                            <a href="#" class="edit" kode_jam_kerja="{{ $d->kode_jam_kerja }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="icon icon-tabler icon-tabler-edit"
                                                                    width="24" height="24" viewBox="0 0 24 24"
                                                                    stroke-width="2" stroke="currentColor" fill="none"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"
                                                                        fill="none">
                                                                    </path>
                                                                    <path
                                                                        d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1">
                                                                    </path>
                                                                    <path
                                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z">
                                                                    </path>
                                                                    <path d="M16 5l3 3"></path>
                                                                </svg>
                                                            </a>
                                                            <form action="/konfigurasi/{{ $d->kode_jam_kerja }}/delete"
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
                                                            </form>
                                                        </div>
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
            </div>
        </div>
    </div>
    <!-- Modals Form -->
    <div class="modal modal-blur fade" id="modal-inputJK" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Jam Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/konfigurasi/storejamkerja" method="post" id="frmJK" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-id"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path
                                                d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z">
                                            </path>
                                            <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                            <path d="M15 8l2 0"></path>
                                            <path d="M15 12l2 0"></path>
                                            <path d="M7 16l10 0"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="kode_jam_kerja"
                                        id="kode_jam_kerja" placeholder="Kode Jam Kerja">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user"
                                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="nama_jam_kerja"
                                        id="nama_jam_kerja" placeholder="nama Jam Kerja">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm" width="24" 
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" 
                                        stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path 
                                        d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" />
                                        <path d="M17 4l2.75 2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="awal_jam_masuk"
                                        id="awal_jam_masuk" placeholder="awal Jam Masuk">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm" width="24" 
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" 
                                        stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path 
                                        d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" />
                                        <path d="M17 4l2.75 2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="jam_masuk"
                                        id="jam_masuk" placeholder="Jam Masuk">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm" width="24" 
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" 
                                        stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path 
                                        d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" />
                                        <path d="M17 4l2.75 2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="akhir_jam_masuk"
                                        id="akhir_jam_masuk" placeholder="Akhir Jam Masuk">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alarm" width="24" 
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" 
                                        stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path 
                                        d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" />
                                        <path d="M17 4l2.75 2" />
                                        </svg>
                                    </span>
                                    <input type="text" value="" class="form-control" name="jam_pulang"
                                        id="jam_pulang" placeholder="Jam Pulang">
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2">
                                        </path>
                                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M14 4l0 4l-6 0l0 -4"></path>
                                    </svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals Edit -->
    <div class="modal modal-blur fade" id="modal-editJK" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Jam Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadeditform">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#btnTambahJK").click(function() {
                $("#modal-inputJK").modal("show");
            });

            $(".edit").click(function() {
                var kode_jam_kerja = $(this).attr('kode_jam_kerja');
                $.ajax({
                    type: 'POST',
                    url: '/konfigurasi/editjamkerja',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_jam_kerja: kode_jam_kerja
                    },
                    success: function(respond) {
                        $("#loadeditform").html(respond);
                    }
                });
                $("#modal-editJK").modal("show");
            });

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


        $("#frmJK").submit(function() {
            var kode_jam_kerja = $("#kode_jam_kerja").val();
            var nama_jam_kerja = $("#nama_jam_kerja").val();
            var awal_jam_masuk = $("#awal_jam_masuk").val();
            var jam_masuk = $("#jam_masuk").val();
            var akhir_jam_masuk = $("#akhir_jam_masuk").val();
            var jam_pulang = $("#jam_pulang").val();
            

            if (kode_jam_kerja == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Kode Harus Diisi!',
                    icon: 'warning',
                    cancelButtonColor: "#206bc4",
                    confirmButtonColor: "#206bc4",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#kode_jam_kerja").focus();
                });
                return false;

            } else if (nama_jam_kerja == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Nama Harus Diisi!',
                    icon: 'warning',
                    cancelButtonColor: "#206bc4",
                    confirmButtonColor: "#206bc4",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#nama_jam_kerja").focus();
                });
                return false;

            } else if (jam_masuk == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'awal jam masuk Harus Diisi!',
                    icon: 'warning',
                    cancelButtonColor: "#206bc4",
                    confirmButtonColor: "#206bc4",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#jam_masuk").focus();
                });
                return false;

            } else if (jam_pulang == "") {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Departemen Harus Diisi!',
                    icon: 'warning',
                    cancelButtonColor: "#206bc4",
                    confirmButtonColor: "#206bc4",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#jam_pulang").focus();
                });
                return false;
            }
        });
    </script>
@endpush
