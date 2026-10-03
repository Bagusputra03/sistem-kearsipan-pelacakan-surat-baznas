<div>
    <x-slot name="header">
        <h2 class="p-6 glass-morphism rounded-lg text-white">
            {{ __('Tugas Disposisi Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-500/20 border border-green-400 text-green-100 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg overflow-hidden">
                <div class="space-y-4">

                    @forelse($pendingDispositions as $task)
                        <div wire:key="task-{{ $task->id }}" class="bg-white/10 backdrop-blur-lg rounded-xl p-4 border border-white/20 flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <div>
                                <p class="text-sm text-green-200">
                                    Dari: <span class="font-semibold text-white">{{ $task->fromUser->name ?? 'N/A' }}</span>
                                    <span class="mx-2">|</span>
                                    Surat: <span class="font-semibold text-white">{{ $task->incomingLetter->subject ?? 'N/A' }}</span>
                                </p>
                                <p class="text-lg text-yellow-400 mt-2 bg-white/10 p-2 rounded-md">
                                    "{{ $task->notes }}"
                                </p>
                            </div>

                            <div class="flex-shrink-0">
                                <button wire:click="openFollowUpModal({{ $task->id }})" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg transition-all duration-300 text-xs">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Kirim Laporan
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-green-100 p-4">
                            Tidak ada tugas disposisi yang sedang menunggu.
                        </div>
                    @endforelse

                </div>

                <div class="mt-4">
                    {{ $pendingDispositions->links() }}
                </div>
            </div>
        </div>
    </div>

    @if($showFollowUpModal && $selectedDispositionId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('disposition-follow-up-form', ['dispositionId' => $selectedDispositionId], key('follow-up-'.$selectedDispositionId))
        </div>
    @endif

</div>