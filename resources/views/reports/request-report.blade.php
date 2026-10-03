<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="p-6 glass-morphism rounded-lg text-white">
                {{ __('Laporan Permohonan Bantuan') }}
            </h2>

            <button onclick="window.print()" class="noprint inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-8a1 1 0 011-1h.01a1 1 0 110 2H18a1 1 0 01-1-1zM5 12a1 1 0 011-1h.01a1 1 0 110 2H6a1 1 0 01-1-1z"></path></svg>
                Cetak Laporan (PDF)
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <p class="text-green-200 text-sm">Total Bantuan Disetujui</p>
                    <p class="text-3xl font-bold text-yellow-400">
                        Rp {{ number_format($kpis['total_disbursed'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <p class="text-green-200 text-sm">Rata-rata Bantuan Disetujui</p>
                    <p class="text-3xl font-bold text-white">
                        Rp {{ number_format($kpis['average_approved_amount'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <p class="text-green-200 text-sm">Total Permohonan Masuk</p>
                    <p class="text-3xl font-bold text-white">
                        {{ $statsByStatus->sum('total_count') }}
                    </p>
                </div>
                <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <p class="text-green-200 text-sm">Persentase Disetujui</p>
                    <p class="text-3xl font-bold text-green-400">
                        {{ $statsByStatus->where('status', 'approved')->first()->percentage ?? 0 }}%
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                
                <div class="lg:col-span-2 bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <h3 class="text-lg font-semibold text-white mb-4">Grafik Permohonan (Status)</h3>
                    <div>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-3 bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                    <h3 class="text-lg font-semibold text-white mb-4">Total Bantuan Disetujui (Kategori)</h3>
                    <div>
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

            </div>

            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Tren Penyaluran Bantuan (12 Bulan Terakhir)</h3>
                <div>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Chart Status (Donat)
            const ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: @json($statusChartData['labels']),
                        datasets: [{
                            data: @json($statusChartData['data']),
                            backgroundColor: [
                                'rgba(74, 222, 128, 0.7)',  // Hijau (Approved)
                                'rgba(250, 204, 21, 0.7)',  // Kuning (Pending)
                                'rgba(248, 113, 113, 0.7)', // Merah (Rejected)
                            ],
                            borderColor: ['#1a4a3a'],
                            borderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: 'white' }
                            }
                        }
                    }
                });
            }

            // 2. Chart Kategori (Bar)
            const ctxCategory = document.getElementById('categoryChart');
            if (ctxCategory) {
                new Chart(ctxCategory, {
                    type: 'bar',
                    data: {
                        labels: @json($categoryChartData['labels']),
                        datasets: [{
                            label: 'Total Dana (Rp)',
                            data: @json($categoryChartData['data']),
                            backgroundColor: 'rgba(250, 204, 21, 0.7)', // Kuning
                            borderColor: 'rgba(250, 204, 21, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        indexAxis: 'y', // Membuat bar menjadi horizontal
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.x !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.x);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: 'white' },
                                grid: { color: 'rgba(255,255,255,0.1)' }
                            },
                            y: {
                                ticks: { color: 'white' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 3. Chart Tren (Garis)
            const ctxTrend = document.getElementById('trendChart');
            if (ctxTrend) {
                new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: @json($trendChartData['labels']),
                        datasets: [{
                            label: 'Dana Disalurkan (Rp)',
                            data: @json($trendChartData['data']),
                            backgroundColor: 'rgba(74, 222, 128, 0.2)',
                            borderColor: 'rgba(74, 222, 128, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                ticks: { color: 'white' },
                                grid: { color: 'rgba(255,255,255,0.1)' }
                            },
                            y: {
                                ticks: { color: 'white' },
                                grid: { color: 'rgba(255,255,255,0.1)' }
                            }
                        }
                    }
                });
            }

        });
    </script>
    @endpush
</x-app-layout>