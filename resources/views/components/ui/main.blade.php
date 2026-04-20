<div class="min-h-screen flex flex-col">
    <!-- TopAppBar -->
    <header
        class="bg-white/70 backdrop-blur-xl shadow-sm fixed top-0 w-full z-50">
        <div class="flex justify-between items-center px-6 py-4 w-full max-w-7xl mx-auto">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-4">
                    <button
                        class="material-symbols-outlined text-slate-600 p-2 hover:bg-blue-50 transition-colors rounded-full active:scale-95 duration-200"
                        data-icon="menu">menu</button>
                    <h1 class="text-xl font-extrabold tracking-tighter text-blue-900 font-headline">The
                        Scholarly Editorial</h1>
                </div>
                <nav class="hidden md:flex items-center gap-6">
                    <a wire:navigate href="{{ route('courses.index') }}"
                        class="text-sm font-bold transition-all hover:scale-105 active:scale-95 {{ request()->routeIs('courses.index') ? 'text-primary' : 'text-slate-600 hover:text-primary' }}">
                        Courses
                    </a>
                </nav>
            </div>
            <div class="flex items-center gap-4">


                @auth
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-10 h-10 rounded-full bg-surface-container-high overflow-hidden border-2 border-primary-fixed focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all active:scale-95">
                            <img alt="Profile" class="w-full h-full object-cover"
                                src="{{ auth()->user()->profile }}" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-cloak x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 mt-4 w-72 bg-white/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-blue-50 overflow-hidden z-[60]">
                            <!-- User Info Section -->
                            <div class="p-6 border-b border-slate-100">
                                <p class="text-sm font-extrabold text-blue-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <!-- Actions Section -->
                            <div class="p-2">
                                @if (auth()->user()->type === \App\Enums\UserTypeEnum::ADMIN)
                                    <a href="/admin"
                                        class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-blue-50 rounded-xl transition-colors">
                                        <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                                        <span>Admin Panel</span>
                                    </a>
                                    <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                @endif
                                {{-- <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 hover:bg-blue-50 rounded-xl transition-colors">
                                    <span class="material-symbols-outlined text-xl" data-icon="school">school</span>
                                    <span>My Learning</span>
                                </a> --}}
                                <div class="h-px bg-slate-100 my-2 mx-4"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                        <span class="material-symbols-outlined text-xl" data-icon="logout">logout</span>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-primary text-on-primary text-sm font-bold rounded-full hover:shadow-lg hover:shadow-primary/20 transition-all active:scale-95">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="hidden md:block px-6 py-2 border-2 border-primary text-primary text-sm font-bold rounded-full hover:bg-primary/5 transition-all active:scale-95">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </header>
    {{ $slot }}
    <footer
        class="mt-auto py-8 bg-on-surface-variant flex justify-center items-center text-[10px] uppercase tracking-[0.2em] font-bold text-surface-container-lowest">
        <span>© {{ date('Y') }} All rights reserved by {{ config('app.name') }}</span>
    </footer>
</div>