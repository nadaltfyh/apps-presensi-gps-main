@extends('layouts.presensi')
@section('header')
    <!-- DatepickerCSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 460px !important;
        }

        .datepicker-date-display {
            background-color: #0d6efd !important;
        }

        .datepicker-cancel,
        .datepicker-clear,
        .datepicker-today,
        .datepicker-done {
            color: #0d6efd !important;
        }
    </style>
    <!-- *App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin</div>
        <div class="right"></div>
    </div>
    <!-- App Header* -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <form action="/presensi/storeizin" method="post" id="frmIzin">
                @csrf
                <div class="form-group">
                    <label for="tgl_izin_start">Tanggal Mulai Izin</label>
                    <input type="text" name="tgl_izin_start" id="tgl_izin_start" class="form-control datepicker"
                        placeholder="Tanggal Mulai">
                </div>
                <div class="form-group">
                    <label for="tgl_izin_end">Tanggal Akhir Izin</label>
                    <input type="text" name="tgl_izin_end" id="tgl_izin_end" class="form-control datepicker"
                        placeholder="Tanggal Akhir">
                </div>
                <div class="form-group">
                    <select name="status" id="status" class="form-control">
                        <option value="">Izin / Sakit /Dinas Luar</option>
                        <option value="i">Izin</option>
                        <option value="s">Sakit</option>
                        <option value="dl">Dinas Luar</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"
                        placeholder="Keterangan"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            $("#tgl_izin_start, #tgl_izin_end").change(function(e) {
                // Add your logic for date range validation or any other functionality
            });

            // The rest of your script remains the same

        });
    </script>
@endpush
