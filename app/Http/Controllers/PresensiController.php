<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\PDF;


class PresensiController extends Controller
{
    public function gethari(){
        $hari= date("D");
        
        switch($hari){
            case'Sun':
                $hari_ini = "Minggu";
                break;

            case'Mon':
                $hari_ini = "Senin";
                break;
                
            case'Tue':
                $hari_ini = "Selasa";
                break;

            case'Wed':
                $hari_ini = "Rabu";
                break;

            case'Thu':
                $hari_ini = "Kamis";
                break;

            case'Fri':
                $hari_ini = "Jumat";
                break;

            case'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }
        return $hari_ini;
    }

    public function create()
    {
        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja','=', 'jam_kerja.kode_jam_kerja' )
            ->where('nik', $nik)->where('hari', $namahari)->first();

        return view('presensi.create', compact('cek', 'lok_kantor','jamkerja'));
    }
    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
        $namahari = $this->gethari();
        $jamkerja = DB::table('konfigurasi_jamkerja')
            ->join('jam_kerja', 'konfigurasi_jamkerja.kode_jam_kerja','=', 'jam_kerja.kode_jam_kerja' )
            ->where('nik', $nik)->where('hari', $namahari)->first();

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;


        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf, Anda berada di luar jangkauan. Jarak Anda " . $radius . " meter dari kantor!|radius";
        } else {
            if ($cek > 0) {
                if ($jamkerja && $jamkerja->jam_pulang) {
                    echo "error|Maaf, belum waktunya pulang|out";
                } else {
                    $data_pulang = [
                        'jam_out' => $jam,
                        'foto_out' => $fileName,
                        'lokasi_out' => $lokasi
                    ];
                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                    if ($update) {
                        echo "success|Terima kasih atas kerja kerasnya!|out";
                        Storage::put($file, $image_base64);
                    } else {
                        echo "error|Maaf, Anda tidak bisa absen!|out";
                    }
                }
            } else {
                if ($jamkerja && $jamkerja->awal_jam_masuk && $jamkerja->akhir_jam_masuk) {
                    if ($jam < $jamkerja->awal_jam_masuk) {
                        echo "error|Maaf, belum waktunya melakukan presensi";
                    } else if ($jam > $jamkerja->akhir_jam_masuk) {
                        echo "error|Maaf, waktu presensi sudah habis";
                    } else {
                        $data = [
                            'nik' => $nik,
                            'tgl_presensi' => $tgl_presensi,
                            'jam_in' => $jam,
                            'foto_in' => $fileName,
                            'lokasi_in' => $lokasi
                        ];
                        $simpan = DB::table('presensi')->insert($data);
                        if ($simpan) {
                            echo "success|Terima kasih, semangat kerjanya!|in";
                            Storage::put($file, $image_base64);
                        } else {
                            echo "error|Maaf, Anda tidak bisa absen!|in";
                        }
                    }
                } else {
                    echo "error|Maaf, data jam kerja tidak ditemukan|in";
                }
            }
        }
        
                
            }


    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }

    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }

        try {
            DB::table('karyawan')->where('nik', $nik)->update($data);

            if ($request->hasFile('foto')) {
                $file_path = $file->storeAs($foto . '.' . $file->extension());
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil diubah!']);
        } catch (\Exception $e) {
            dd($e);
        }
        //$update =
        // if ($update) {
        //     if ($request->hasFile('foto')) {
        //         $folderPath = "public/uploads/karyawan/";
        //         $request->file('foto')->storeAs($folderPath, $foto);
        //     }
        //     return Redirect::back()->with(['success' => 'Data berhasil diubah!']);
        // } else {
        //     return Redirect::back()->with(['error' => 'Data gagal diubah!']);
        // }
    }
    
    //Laporan-Karyawan
    public function createLaporan()
    {
        return view('presensi.laporan-karyawan');
    }

    public function storeLaporan(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'judul' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
    ]);

    if ($validator->fails()) {
        return redirect('/presensi/laporan-karyawan')
                    ->withErrors($validator)
                    ->withInput();
    }

        $judul = $request->input('judul');
        $file = $request->file('file');

    // Validate the uploaded file
    if ($file && $file->isValid()) {
        // Store the file with a safer method to get the extension
        
        $file_path = $file->storeAs($judul . '.' . $file->extension());

        // Simpan informasi laporan ke database
        DB::table('laporan')->insert([
            'judul' => $judul,
            'file_path' => $file_path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            
    }
        return response()->json(['success' => true, 'message' => 'file berhasil diunggah.']);

    }
   

    public function izin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
{
    $nik = Auth::guard('karyawan')->user()->nik;
    $tgl_izin_start = $request->tgl_izin_start;
    $tgl_izin_end = $request->tgl_izin_end;
    $status = $request->status;
    $keterangan = $request->keterangan;

    $data = [
        'nik' => $nik,
        'tgl_izin_start' => $tgl_izin_start,
        'tgl_izin_end' => $tgl_izin_end,
        'status' => $status,
        'keterangan' => $keterangan
    ];

    $simpan = DB::table('pengajuan_izin')->insert($data);


    if ($simpan) {
        return redirect('/presensi/izin')->with(['success' => 'Data berhasil disimpan']);
    } else {
        return redirect('/presensi/izin')->with(['error' => 'Data gagal disimpan']);
    }
}

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //Fungsi header dengan mengirimkan raw 	data excel
            header("Content-type: application/vnd-ms-excel");
            //Mendefenisikan nama file export "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
            ->selectRaw('presensi.nik,nama_lengkap,
                MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
                MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
                MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
                MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
                MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
                MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
                MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
                MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
                MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
                MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
                MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
                MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
                MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
                MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
                MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
                MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
                MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
                MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
                MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
                MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
                MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
                MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
                MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
                MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
                MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
                MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
                MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
                MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
                MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
                MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
                MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.nik,nama_lengkap')
            ->get();

        if (isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //Fungsi header dengan mengirimkan raw 	data excel
            header("Content-type: application/vnd-ms-excel");
            //Mendefenisikan nama file export "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }

        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));

    }

    public function izinsakit(Request $request)
{
    $query = Pengajuanizin::query();
    $query->select('id', 'tgl_izin_start', 'tgl_izin_end', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
    $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');

    if (!empty($request->dari) && !empty($request->sampai)) {
        $query->whereBetween('tgl_izin_start', [$request->dari, $request->sampai]);
    }

    if (!empty($request->nik)) {
        $query->where('pengajuan_izin.nik', $request->nik);
    }

    if (!empty($request->nama_lengkap)) {
        $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
    }

    if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
        $query->where('status_approved', $request->status_approved);
    }

    $query->orderBy('tgl_izin_start', 'desc'); // Ganti 'tgl_izin' dengan 'tgl_izin_start'
    
    $izinsakit = $query->paginate(3);
    $izinsakit->appends($request->all());
    return view('presensi.izinsakit', compact('izinsakit'));
}


    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izin')->where('id', $id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data berhasil disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disetujui']);
        }
    }

    public function batalkanizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data berhasil disetujui']);
        } else {
            return Redirect::back()->with(['warning' => 'Data gagal disetujui']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

   
    

}