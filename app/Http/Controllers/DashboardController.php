<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Penyewaan;
use App\Models\Pelanggan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMobil = Mobil::count();
        $totalPelanggan = Pelanggan::count();
        $penyewaanAktif = Penyewaan::where('status', 'aktif')->count();
        $mobilTersedia = Mobil::where('status', 'tersedia')->count();
        $mobilsTersedia = Mobil::where('status', 'tersedia')->take(5)->get();
        
        $penyewaanBulanIni = Penyewaan::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // Hitung total pendapatan bulan ini
        $totalPendapatanBulanIni = Penyewaan::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get()
            ->sum(function($penyewaan) {
                return $penyewaan->total_biaya + $penyewaan->denda;
            });

        // Data chart 6 bulan terakhir
        $chartData = $this->getChartData();

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        return view('dashboard', compact(
            'totalMobil', 
            'totalPelanggan', 
            'penyewaanAktif', 
            'mobilTersedia',
            'penyewaanBulanIni',
            'mobilsTersedia',
            'totalPendapatanBulanIni',
            'chartData',
            'recentActivities'
        ));
    }

    private function getChartData()
    {
        $data = [
            'labels' => [],
            'pendapatan' => [],
            'penyewaan' => []
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            $data['labels'][] = $monthName;

            // Hitung pendapatan bulanan
            $pendapatan = Penyewaan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->get()
                ->sum(function($penyewaan) {
                    return $penyewaan->total_biaya + $penyewaan->denda;
                });
            
            $data['pendapatan'][] = $pendapatan;

            // Hitung jumlah penyewaan
            $jumlahPenyewaan = Penyewaan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $data['penyewaan'][] = $jumlahPenyewaan;
        }

        return $data;
    }

    private function getRecentActivities()
    {
        return [
            [
                'icon' => 'fas fa-car text-success',
                'color' => 'bg-success',
                'text' => 'Mobil Toyota Avanza berhasil disewa',
                'time' => '5 menit lalu'
            ],
            [
                'icon' => 'fas fa-user text-info',
                'color' => 'bg-info',
                'text' => 'Pelanggan baru terdaftar',
                'time' => '1 jam lalu'
            ],
            [
                'icon' => 'fas fa-undo text-warning',
                'color' => 'bg-warning',
                'text' => 'Pengembalian mobil Honda Jazz',
                'time' => '2 jam lalu'
            ],
            [
                'icon' => 'fas fa-chart-line text-primary',
                'color' => 'bg-primary',
                'text' => 'Laporan bulanan di-generate',
                'time' => 'Hari ini'
            ]
        ];
    }
}