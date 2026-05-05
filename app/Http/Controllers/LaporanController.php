<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Models\Mobil;
use App\Models\Pelanggan;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use Excel;
use App\Exports\LaporanHarianExport;
use App\Exports\LaporanBulananExport;
use App\Exports\LaporanTahunanExport;

class LaporanController extends Controller
{
    /**
     * Laporan Harian
     */
    public function harian(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');
        $totalPenyewaan = $penyewaans->count();
        
        $penyewaanAktif = $penyewaans->where('status', 'aktif')->count();
        $penyewaanSelesai = $penyewaans->where('status', 'selesai')->count();
        $penyewaanBatal = $penyewaans->where('status', 'batal')->count();

        return view('laporan.harian', compact(
            'penyewaans', 
            'tanggal',
            'totalPendapatan',
            'totalDenda',
            'totalPenyewaan',
            'penyewaanAktif',
            'penyewaanSelesai',
            'penyewaanBatal'
        ));
    }

    /**
     * Laporan Bulanan
     */
    public function bulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereYear('created_at', date('Y', strtotime($bulan)))
            ->whereMonth('created_at', date('m', strtotime($bulan)))
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');
        $totalPenyewaan = $penyewaans->count();
        
        $penyewaanAktif = $penyewaans->where('status', 'aktif')->count();
        $penyewaanSelesai = $penyewaans->where('status', 'selesai')->count();
        $penyewaanBatal = $penyewaans->where('status', 'batal')->count();

        // Data untuk chart
        $chartData = $this->getChartDataBulanan($bulan);

        return view('laporan.bulanan', compact(
            'penyewaans', 
            'bulan',
            'totalPendapatan',
            'totalDenda',
            'totalPenyewaan',
            'penyewaanAktif',
            'penyewaanSelesai',
            'penyewaanBatal',
            'chartData'
        ));
    }

    /**
     * Laporan Tahunan
     */
    public function tahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');
        $totalPenyewaan = $penyewaans->count();
        
        $penyewaanAktif = $penyewaans->where('status', 'aktif')->count();
        $penyewaanSelesai = $penyewaans->where('status', 'selesai')->count();
        $penyewaanBatal = $penyewaans->where('status', 'batal')->count();

        // Data untuk chart
        $chartData = $this->getChartDataTahunan($tahun);

        return view('laporan.tahunan', compact(
            'penyewaans', 
            'tahun',
            'totalPendapatan',
            'totalDenda',
            'totalPenyewaan',
            'penyewaanAktif',
            'penyewaanSelesai',
            'penyewaanBatal',
            'chartData'
        ));
    }

   /**
 * Laporan Mobil
 */
public function mobil(Request $request)
{
    $mobil = $request->get('mobil');
    $bulan = $request->get('bulan', date('Y-m'));

    $query = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian']);

    if ($mobil) {
        $query->where('mobil_id', $mobil);
    }

    if ($bulan) {
        $query->whereYear('created_at', date('Y', strtotime($bulan)))
              ->whereMonth('created_at', date('m', strtotime($bulan)));
    }

    $penyewaans = $query->orderBy('created_at', 'desc')->get();
    $mobils = Mobil::all();

    $totalPendapatan = $penyewaans->sum('total_biaya');
    $totalSewa = $penyewaans->count();

    return view('laporan.mobil', compact(
        'penyewaans',
        'mobils',
        'mobil',
        'bulan',
        'totalPendapatan',
        'totalSewa'
    ));
}

/**
 * Laporan Pelanggan
 */
public function pelanggan(Request $request)
{
    $pelanggan = $request->get('pelanggan');
    $bulan = $request->get('bulan', date('Y-m'));

    $query = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian']);

    if ($pelanggan) {
        $query->where('pelanggan_id', $pelanggan);
    }

    if ($bulan) {
        $query->whereYear('created_at', date('Y', strtotime($bulan)))
              ->whereMonth('created_at', date('m', strtotime($bulan)));
    }

    $penyewaans = $query->orderBy('created_at', 'desc')->get();
    $pelanggans = Pelanggan::all();

    $totalPendapatan = $penyewaans->sum('total_biaya');
    $totalSewa = $penyewaans->count();

    return view('laporan.pelanggan', compact(
        'penyewaans',
        'pelanggans',
        'pelanggan',
        'bulan',
        'totalPendapatan',
        'totalSewa'
    ));
}
    /**
     * Export Laporan Harian ke PDF
     */
    public function exportHarian(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');

        $pdf = PDF::loadView('laporan.export.harian', compact(
            'penyewaans', 
            'tanggal',
            'totalPendapatan',
            'totalDenda'
        ));

        return $pdf->download('laporan-harian-' . $tanggal . '.pdf');
    }

    /**
     * Export Laporan Bulanan ke PDF
     */
    public function exportBulanan(Request $request)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereYear('created_at', date('Y', strtotime($bulan)))
            ->whereMonth('created_at', date('m', strtotime($bulan)))
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');

        $pdf = PDF::loadView('laporan.export.bulanan', compact(
            'penyewaans', 
            'bulan',
            'totalPendapatan',
            'totalDenda'
        ));

        return $pdf->download('laporan-bulanan-' . $bulan . '.pdf');
    }

    /**
     * Export Laporan Tahunan ke PDF
     */
    public function exportTahunan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $penyewaans = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian'])
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $penyewaans->sum('total_biaya');
        $totalDenda = $penyewaans->sum('denda');

        $pdf = PDF::loadView('laporan.export.tahunan', compact(
            'penyewaans', 
            'tahun',
            'totalPendapatan',
            'totalDenda'
        ));

        return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
    }

    /**
     * Export Laporan Mobil ke PDF
     */
    public function exportMobil(Request $request)
    {
        $mobil = $request->get('mobil');
        $bulan = $request->get('bulan', date('Y-m'));

        $query = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian']);

        if ($mobil) {
            $query->where('mobil_id', $mobil);
        }

        if ($bulan) {
            $query->whereYear('created_at', date('Y', strtotime($bulan)))
                  ->whereMonth('created_at', date('m', strtotime($bulan)));
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->get();
        $selectedMobil = $mobil ? Mobil::find($mobil) : null;

        $totalPendapatan = $penyewaans->sum('total_biaya');

        $pdf = PDF::loadView('laporan.export.mobil', compact(
            'penyewaans',
            'selectedMobil',
            'bulan',
            'totalPendapatan'
        ));

        $filename = $selectedMobil ? 
            'laporan-mobil-' . str_slug($selectedMobil->merk . ' ' . $selectedMobil->model) . '-' . $bulan . '.pdf' :
            'laporan-mobil-' . $bulan . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Laporan Pelanggan ke PDF
     */
    public function exportPelanggan(Request $request)
    {
        $pelanggan = $request->get('pelanggan');
        $bulan = $request->get('bulan', date('Y-m'));

        $query = Penyewaan::with(['mobil', 'pelanggan', 'pengembalian']);

        if ($pelanggan) {
            $query->where('pelanggan_id', $pelanggan);
        }

        if ($bulan) {
            $query->whereYear('created_at', date('Y', strtotime($bulan)))
                  ->whereMonth('created_at', date('m', strtotime($bulan)));
        }

        $penyewaans = $query->orderBy('created_at', 'desc')->get();
        $selectedPelanggan = $pelanggan ? Pelanggan::find($pelanggan) : null;

        $totalPendapatan = $penyewaans->sum('total_biaya');

        $pdf = PDF::loadView('laporan.export.pelanggan', compact(
            'penyewaans',
            'selectedPelanggan',
            'bulan',
            'totalPendapatan'
        ));

        $filename = $selectedPelanggan ? 
            'laporan-pelanggan-' . str_slug($selectedPelanggan->nama) . '-' . $bulan . '.pdf' :
            'laporan-pelanggan-' . $bulan . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get Chart Data untuk Laporan Bulanan
     */
    private function getChartDataBulanan($bulan)
    {
        $year = date('Y', strtotime($bulan));
        $month = date('m', strtotime($bulan));
        
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $data = [
            'labels' => [],
            'pendapatan' => [],
            'penyewaan' => []
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $data['labels'][] = $day;
            
            $pendapatan = Penyewaan::whereDate('created_at', $date)
                ->get()
                ->sum(function($penyewaan) {
                    return $penyewaan->total_biaya + $penyewaan->denda;
                });
            
            $jumlahPenyewaan = Penyewaan::whereDate('created_at', $date)->count();
            
            $data['pendapatan'][] = $pendapatan;
            $data['penyewaan'][] = $jumlahPenyewaan;
        }

        return $data;
    }

    /**
     * Get Chart Data untuk Laporan Tahunan
     */
    private function getChartDataTahunan($tahun)
    {
        $data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'pendapatan' => [],
            'penyewaan' => []
        ];

        for ($month = 1; $month <= 12; $month++) {
            $pendapatan = Penyewaan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $month)
                ->get()
                ->sum(function($penyewaan) {
                    return $penyewaan->total_biaya + $penyewaan->denda;
                });
            
            $jumlahPenyewaan = Penyewaan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $month)
                ->count();
            
            $data['pendapatan'][] = $pendapatan;
            $data['penyewaan'][] = $jumlahPenyewaan;
        }

        return $data;
    }
}