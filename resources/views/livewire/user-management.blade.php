<div>
    <x-slot name="header">
        <h2 class="p-6 glass-morphism rounded-lg text-white">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end mb-4">
                <button wire:click="openModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-green-900 font-bold rounded-lg transition-all duration-300 transform hover:scale-105 uppercase tracking-widest text-xs">
                    + Tambah Pengguna Baru
                </button>
            </div>
            
            @if(session('success'))
                <div class="mb-4 bg-green-500/20 border border-green-400 text-green-100 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-500/20 border border-red-400 text-red-100 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg overflow-hidden">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-green-100">
                        <thead class="text-xs text-yellow-400 uppercase bg-white/10">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Tipe Pengguna</th>
                                <th scope="col" class="px-6 py-3">NIK</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-b border-white/10 hover:bg-white/5">
                                    <th scope="row" class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                        {{ $user->name }}
                                    </th>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->userType === 'officer')
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 rounded-full text-xs">Petugas</span>
                                            @if($user->is_pimpinan)
                                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 rounded-full text-xs ml-1">Pimpinan</span>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 bg-green-500/20 text-green-300 rounded-full text-xs">Mustahik</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $user->nik }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button wire:click="openModal({{ $user->id }})" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                                                Edit
                                            </button>
                                            
                                            @if($user->id !== auth()->id())
                                                <button wire:click="askToDeleteUser({{ $user->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Tidak ada data pengguna.</td>
                                </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($showUserModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            @livewire('user-form', ['userId' => $editingUserId], key($editingUserId))
        </div>
    @endif

    @if($confirmingUserDeletionId)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
            <div class="bg-green-900/95 backdrop-blur-lg rounded-xl p-6 border border-white/20 shadow-lg max-w-md w-full">

                <h3 class="text-xl font-bold text-white mb-4">Hapus Pengguna</h3>

                <p class="text-green-100 mb-6">
                    Anda yakin ingin menghapus pengguna ini? Semua data yang terkait akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-end gap-4">
                    <button wire:click="cancelDeleteUser" type="button" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-lg text-white font-bold rounded-lg border border-white/30 transition-all duration-300 uppercase tracking-widest text-xs">
                        Batal
                    </button>

                    <x-danger-button wire:click="confirmDeleteUser">
                        Ya, Hapus
                    </x-danger-button>
                </div>

            </div>
        </div>
    @endif
</div>