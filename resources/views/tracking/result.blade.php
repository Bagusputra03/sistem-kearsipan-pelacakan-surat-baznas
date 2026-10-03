<x-guest-layout>
    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 shadow-2xl border border-white/20 text-white w-full max-w-2xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Hasil Pelacakan</h2>
            <p class="text-lg text-yellow-400 font-semibold">{{ $letter->tracking_code }}</p>
        </div>

        <div class="bg-white/10 rounded-lg p-6 mb-8 space-y-4">
            <div>
                <p class="text-sm text-green-200">Perihal</p>
                <p class="text-lg font-semibold text-white">{{ $letter->subject }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Asal Surat / Pengirim</p>
                <p class="text-lg font-semibold text-white">{{ $letter->sender_name }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Tanggal Diterima</p>
                <p class="text-lg font-semibold text-white">{{ $letter->created_at->format('d M Y, H:i') }}</U></p>
            </div>
        </div>

        <h3 class="text-xl font-bold text-white mb-6">Riwayat Status</h3>
        <div class="space-y-6">
            @foreach ($steps as $step)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                        @if($step['status'] == 'completed') bg-green-500
                        @elseif($step['status'] == 'completed_error') bg-red-500
                        @elseif($step['status'] == 'current') bg-yellow-500 animate-pulse
                        @endif">
                        <span class="text-xl">
                            @if($step['status'] == 'completed') ✅
                            @elseif($step['status'] == 'completed_error') ❌
                            @elseif($step['status'] == 'current') 🔄
                            @endif
                        </span>
                    </div>
                    
                    <div class="ms-4">
                        <h4 class="text-lg font-semibold text-white">{{ $step['name'] }}</h4>
                        <p class="text-sm text-green-200">{{ $step['time'] ? $step['time']->format('d M Y, H:i') : '...' }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if($isCompleted && $letter->requestData)
            <div class="border-t border-white/20 mt-8 pt-6">
                <h3 class="text-xl font-bold text-white mb-4">Hasil Akhir</h3>
                <div class="bg-white/10 rounded-lg p-6">
                    <p class="text-sm text-green-200 mb-2">Keterangan Petugas:</p>
                    <p class="text-white">
                        {{ $letter->requestData->reviewComments ?? 'Permohonan telah selesai diproses.' }}
                    </p>
                </div>
            </div>
        @endif


        <div class="text-center mt-8">
            <a href="/" class="text-yellow-400 hover:text-yellow-300 font-medium underline">
                &larr; Lakukan pelacakan baru
            </a>
        </div>
    </div>
</x-guest-layout>