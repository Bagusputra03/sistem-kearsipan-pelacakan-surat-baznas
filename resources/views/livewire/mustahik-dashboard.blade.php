<div class="space-y-6">
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="text-green-100">Dashboard Mustahik - Riwayat Permohonan Bantuan Anda</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <p class="text-green-200 text-sm">Total Permohonan</p>
            <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <p class="text-green-200 text-sm">Menunggu Review</p>
            <p class="text-3xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <p class="text-green-200 text-sm">Disetujui</p>
            <p class="text-3xl font-bold text-green-400">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <p class="text-green-200 text-sm">Ditolak</p>
            <p class="text-3xl font-bold text-red-400">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
        @livewire('request-list', ['userType' => 'mustahik'])
    </div>

    @if($selectedRequestId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('request-detail-modal', ['requestId' => $selectedRequestId], key('detail-mustahik-'.$selectedRequestId))
        </div>
    @endif

</div>