<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-40 glass-morphism border-b border-white/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Logo BAZNAS" class="w-10 h-10 object-contain">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('accessOfficerFeatures')
                        
                        <x-nav-link :href="route('letters.index')" :active="request()->routeIs('letters.index')">
                            {{ __('Surat Masuk') }}
                        </x-nav-link>

                        <x-nav-link :href="route('archives.index')" :active="request()->routeIs('archives.index')">
                            {{ __('Arsip Internal') }}
                        </x-nav-link>

                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="left" width="48" contentClasses="py-1 bg-green-900/95 backdrop-blur-lg border border-white/20">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('reports.*') ? 'border-yellow-400 text-white' : 'border-transparent text-green-200' }} text-sm font-medium leading-5 hover:text-white hover:border-yellow-400/50 focus:outline-none focus:text-white focus:border-yellow-400/50 transition duration-150 ease-in-out">
                                        <div>{{ __('Laporan') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                    
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('reports.requests')">
                                        {{ __('Laporan Permohonan') }}
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        @can('isStaf')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                                {{ __('Manajemen Pengguna') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dispositions.my')" :active="request()->routeIs('dispositions.my')">
                                {{ __('Tugas Disposisi') }}
                            </x-nav-link>
                        @endcan
                        
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-green-900/95 backdrop-blur-lg border border-white/20">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-100 bg-white/10 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-200 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden glass-morphism border-t border-white/20">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @can('accessOfficerFeatures')
                
                <x-responsive-nav-link :href="route('letters.index')" :active="request()->routeIs('letters.index')">
                    {{ __('Surat Masuk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('archives.index')" :active="request()->routeIs('archives.index')">
                    {{ __('Arsip Internal') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.requests')" :active="request()->routeIs('reports.requests')">
                    {{ __('Laporan Permohonan') }}
                </x-responsive-nav-link>

                @can('isStaf')
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('Manajemen Pengguna') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dispositions.my')" :active="request()->routeIs('dispositions.my')">
                        {{ __('Tugas Disposisi') }}
                    </x-responsive-nav-link>
                @endcan

            @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-green-200">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>