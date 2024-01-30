<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use ZipArchive;


class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php

    public function index()
    {
        $laporanData = DB::table('laporan')->get();
        return view('presensi.hsl-lap-kry', ['laporanData' => $laporanData]);


    }

    public function downloadZip()
    {
    $zipFileName = 'Laporan-Karyawan.zip';
    $folderToZip = storage_path('\app\public\laporan'); // Ganti dengan path folder yang ingin Anda zip

    $zip = new ZipArchive;
    if ($zip->open(storage_path($zipFileName), ZipArchive::CREATE) === TRUE) {
        $files = File::allFiles($folderToZip);

        foreach ($files as $file) {
            $zip->addFile($file->getPathname(), $file->getRelativePathname());
        }
        $zip->close();

        return response()->download(storage_path($zipFileName))->deleteFileAfterSend();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file ZIP.');
        }
    }



    
}