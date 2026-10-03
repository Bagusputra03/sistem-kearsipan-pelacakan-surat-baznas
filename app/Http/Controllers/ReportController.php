<?php

namespace App\Http\Controllers;

use App\Models\Request as BantuanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan permohonan.
     */
    public function requestReport()
    {
        // 1. STATISTIK BERDASARKAN STATUS (DENGAN PERSENTASE)
        $statsByStatus = BantuanRequest::select(
                'status',
                DB::raw('count(*) as total_count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->groupBy('status')
            ->get();
        
        $totalRequests = $statsByStatus->sum('total_count');
        
        // Tambahkan data persentase ke koleksi
        $statsByStatus = $statsByStatus->map(function($item) use ($totalRequests) {
            $item->percentage = ($totalRequests > 0) ? round(($item->total_count / $totalRequests) * 100, 1) : 0;
            return $item;
        });

        // Data untuk Chart Donat (Status)
        $statusChartData = [
            'labels' => $statsByStatus->pluck('status')->map('ucfirst'),
            'data' => $statsByStatus->pluck('total_count'),
        ];


        // 2. STATISTIK BERDASARKAN KATEGORI (UNTUK CHART BARU)
        $statsByCategory = BantuanRequest::where('status', 'approved') // Kita hanya peduli kategori yang disetujui
            ->select(
                'requestType', //
                DB::raw('count(*) as total_count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->groupBy('requestType')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Data untuk Chart Bar (Kategori)
        $categoryChartData = [
            'labels' => $statsByCategory->pluck('requestType')->map('ucfirst'),
            'data' => $statsByCategory->pluck('total_amount'),
        ];


        // 3. TREN PENYALURAN BANTUAN 12 BULAN (UNTUK CHART BARU)
        $approvedTrend = BantuanRequest::where('status', 'approved')
            ->where('created_at', '>=', Carbon::now()->subMonths(12)) //
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('sum(amount) as total_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();
        
        // Format data untuk Chart.js (memastikan 12 bulan penuh)
        $trendLabels = [];
        $trendData = [];
        $date = Carbon::now()->subMonths(11)->startOfMonth();

        for ($i = 0; $i < 12; $i++) {
            $monthLabel = $date->format('M Y');
            $trendLabels[] = $monthLabel;
            
            $found = $approvedTrend->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            
            $trendData[] = $found ? $found->total_amount : 0;
            $date->addMonth();
        }

        $trendChartData = [
            'labels' => $trendLabels,
            'data' => $trendData,
        ];


        // 4. KPI TAMBAHAN (Key Performance Indicators)
        $kpis = [
            'average_approved_amount' => BantuanRequest::where('status', 'approved')->avg('amount') ?? 0,
            'total_disbursed' => $statsByStatus->where('status', 'approved')->first()->total_amount ?? 0,
        ];

        // Kirim semua data ke view
        return view('reports.request-report', compact(
            'statsByStatus', 
            'statusChartData', 
            'categoryChartData',
            'trendChartData',
            'kpis'
        ));
    }
}