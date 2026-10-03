<div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-white/20 text-white">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-white">Detail Permohonan</h3>
        <button
            wire:click="$dispatch('closeModal')"
            class="text-white hover:text-red-300 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-green-200">ID Permohonan</p>
                <p class="font-semibold text-white">{{ $request->id }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Status</p>
                <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $this->getStatusColor($request->status) }}">
                    {{ $this->getStatusText($request->status) }}
                </span>
            </div>
        </div>

        @if($request->user)
        <div class="bg-white/10 rounded-lg p-4">
            <h4 class="font-semibold text-white mb-2">Data Pemohon</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                <p class="text-green-100"><strong>Nama:</strong> {{ $request->user->name }}</p>
                <p class="text-green-100"><strong>NIK:</strong> {{ $request->user->nik }}</p>
                <p class="text-green-100"><strong>Email:</strong> {{ $request->user->email }}</p>
                <p class="text-green-100"><strong>Telepon:</strong> {{ $request->user->phone }}</p>
                <p class="md:col-span-2 text-green-100"><strong>Alamat:</strong> {{ $request->user->address }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-green-200">Jenis Bantuan</p>
                <p class="font-semibold text-white">{{ Str::ucfirst($request->requestType) }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Jumlah Bantuan</p>
                <p class="font-semibold text-yellow-400">Rp {{ number_format($request->amount, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Tingkat Urgensi</p>
                <p class="font-semibold">
                    {{ Str::ucfirst($request->urgency) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-green-200">Anggota Keluarga</p>
                <p class="font-semibold text-white">{{ $request->familyMembers }} orang</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Penghasilan/Bulan</p>
                <p class="font-semibold text-white">Rp {{ number_format($request->monthlyIncome, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-green-200">Status Pekerjaan</p>
                <p class="font-semibold text-white">{{ Str::ucfirst($request->jobStatus) }}</p>
            </div>
        </div>

        <div>
            <p class="text-sm text-green-200 mb-2">Alasan Permohonan</p>
            <p class="text-white bg-white/10 rounded-lg p-3">{{ $request->reason }}</p>
        </div>

        @if($request->description)
        <div>
            <p class="text-sm text-green-200 mb-2">Keterangan Tambahan</p>
            <p class="text-white bg-white/10 rounded-lg p-3">{{ $request->description }}</p>
        </div>
        @endif

        @if($request->reviewComments)
        <div>
            <p class="text-sm text-green-200 mb-2">Komentar Review</p>
            <p class="text-white bg-blue-500/20 border border-blue-400/30 rounded-lg p-3">{{ $request->reviewComments }}</p>
        </div>
        @endif
    </div>

</div>