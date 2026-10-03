<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-2xl font-bold text-white">
            {{ $userType === 'mustahik' ? 'Permohonan Saya' : 'Daftar Permohonan' }}
        </h2>
        <div class="flex gap-2">
            <button wire:click="$set('filter', 'all')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'all' ? 'bg-yellow-400 text-green-900' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Semua ({{ $counts['all'] }})
            </button>
            <button wire:click="$set('filter', 'pending')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'pending' ? 'bg-yellow-400 text-green-900' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Pending ({{ $counts['pending'] }})
            </button>
            <button wire:click="$set('filter', 'approved')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'approved' ? 'bg-yellow-400 text-green-900' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Disetujui ({{ $counts['approved'] }})
            </button>
            <button wire:click="$set('filter', 'rejected')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter === 'rejected' ? 'bg-yellow-400 text-green-900' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Ditolak ({{ $counts['rejected'] }})
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4">
        <input 
            wire:model.live.debounce.300ms="search" 
            type="text" 
            placeholder="Cari berdasarkan jenis, alasan, @if($userType === 'officer') nama... @endif"
            class="flex-1 px-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white placeholder-green-100 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
        
        <select wire:model.live="sortBy" class="w-full md:w-1/5 px-4 py-3 bg-white/10 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
            <option value="created_at_desc" class="text-gray-800">Urutkan: Terbaru</option>
            <option value="created_at_asc" class="text-gray-800">Urutkan: Terlama</option>
            <option value="amount_desc" class="text-gray-800">Jumlah: Terbesar</option>
            <option value="amount_asc" class="text-gray-800">Jumlah: Terkecil</option>
        </select>
    </div>

    <div class="grid gap-4">
        @forelse($requests as $request)
            <div wire:key="{{ $request->id }}" class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <h3 class="text-lg font-semibold text-white">
                                {{ Str::ucfirst($request->requestType) }}
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $this->getStatusColor($request->status) }}">
                                {{ $this->getStatusText($request->status) }}
                            </span>
                        </div>

                        @if($userType === 'officer' && $request->user)
                            <div class="bg-white/10 rounded-lg p-3 mb-3">
                                <p class="text-sm text-green-100">
                                    <strong>Pemohon:</strong> {{ $request->user->name }}
                                </p>
                                <p class="text-sm text-green-100">
                                    <strong>Email:</strong> {{ $request->user->email }}
                                </p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-green-200">Jumlah Bantuan</p>
                                <p class="font-semibold text-yellow-400">
                                    Rp {{ number_format($request->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-green-200">Tanggal Ajuan</p>
                                <p class="font-semibold text-white">
                                    {{ $request->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <p class="text-green-100 mb-3">
                            <strong>Alasan:</strong> {{ Str::limit($request->reason, 100) }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2">
                        <button 
                            wire:click="$dispatch('openDetailModal', { requestId: {{ $request->id }} })" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-300">
                            Detail
                        </button>

                        @if($userType === 'officer' && $request->status === 'pending')
                            <button wire:click="updateRequestStatus({{ $request->id }}, 'approved', 'Permohonan disetujui')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                Setujui
                            </button>
                            <button wire:click="updateRequestStatus({{ $request->id }}, 'rejected', 'Permohonan ditolak')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                Tolak
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-8 text-center border border-white/20">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-semibold text-white mb-2">
                    @if($search)
                        Tidak Ditemukan
                    @else
                        Tidak Ada Permohonan
                    @endif
                </h3>
                <p class="text-green-100">
                    @if($search)
                        Tidak ada permohonan yang cocok dengan pencarian "{{ $search }}".
                    @else
                        Tidak ada permohonan dengan status ini.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>