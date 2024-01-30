<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        .title {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabledatakaryawan {
            margin-top: 40px;
        }

        .tabledatakaryawan tr td {
            padding: 5px;
        }

        .tablepresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tablepresensi tr th {
            border: 1px solid #131212;
            padding: 5px;
            background-color: #dbdbdb;
            font-size: 10px;
        }

        .tablepresensi tr td {
            border: 1px solid #131212;
            padding: 5px;
            font-size: 12px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
    @php
        //Function Untuk Menghitung Selisih Jam
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ' ' . 'Jam' . ' ' . round($sisamenit2) . ' ' . 'Menit';
        }
    @endphp

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <!-- Write HTML just like a web page -->
        <table style="width: 100%">
            <tr>
                <td>
                    <img width="100" src="{{ asset('assets/img/logo.png') }}" alt="">
                </td>
                <td>
                    <span class="title">
                        LAPORAN PRESENSI KARYAWAN <br>
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }} <br>
                        Puskesmas Cibiru Hilir  <br>
                    </span>
                    <span><i>Jl.Cibiru Hilir 40393 Cibiru Wetan 2 Jawa Barat</i></span>
                </td>
            </tr>
        </table>
        <table class="tabledatakaryawan">
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="150">
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $karyawan->nama_dept }}</td>
            </tr>
            <tr>
                <td>No Handphone</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
        </table>
        <table class="tablepresensi" border="1">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Keterangan</th>
                <th>Jumlah Jam Kerja</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $jamterlambat = selisih('07:00:00', $d->jam_in);
                @endphp
                <tr align="center">
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                    <td>
                        @if ($d->jam_in > '07:00')
                            Terlambat {{ $jamterlambat }}
                        @else
                            Tepat Waktu
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_out != null)
                            @php
                                $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                            @endphp
                        @else
                            @php
                                $jmljamkerja = 0;
                            @endphp
                        @endif
                        {{ $jmljamkerja }}
                    </td>
                </tr>
            @endforeach
        </table>
        <table width="100%" style="margin-top: 100px;">
            <tr>
                <td></td>
                <td style="text-align: center">Bandung, {{ date('d-F-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align: bottom" height="100px">
                    <u></u>Dadi Firmansyah, SKM<br>
                    <i><b>Admin Puskesmas Cibiru Hilir</b></i>
                </td>
                <td style="vertical-align: bottom; text-align: center">
                    <u>dr. Iyos Rosmawati </u><br>
                    <i><b>Kepala Puskesmas Cibiru Hilir</b></i>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
