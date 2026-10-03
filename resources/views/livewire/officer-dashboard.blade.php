<div class="space-y-6">
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
        <h1 class="text-3xl font-bold text-white mb-2">Dashboard Petugas BAZNAS</h1>
        <p class="text-green-100">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Total Permohonan</p>
            <p class="text-2xl font-bold text-white">{{ $stats['totalRequests'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Pending</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['pendingRequests'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Disetujui</p>
            <p class="text-2xl font-bold text-green-400">{{ $stats['approvedRequests'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Ditolak</p>
            <p class="text-2xl font-bold text-red-400">{{ $stats['rejectedRequests'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Total Mustahik</p>
            <p class="text-2xl font-bold text-purple-400">{{ $stats['totalUsers'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20">
            <p class="text-green-200 text-xs">Total Bantuan</p>
            <p class="text-xl font-bold text-yellow-400">
                Rp {{ number_format($stats['totalAmount'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
        @livewire('request-list', ['userType' => 'officer'])
    </div>
    
    @if($selectedRequestId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('request-detail-modal', ['requestId' => $selectedRequestId], key('detail-officer-'.$selectedRequestId))
        </div>
    @endif
</div>