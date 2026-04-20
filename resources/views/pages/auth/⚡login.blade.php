<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app', ['class' => 'bg-surface text-on-surface min-h-screen flex items-center justify-center p-6'])] class extends Component
{
    public $email = '';
    public $password = '';

    public function login()
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            return redirect()->intended(config('fortify.home'));
        }

        $this->addError('email', __('auth.failed'));
    }
};
?>

<div>
    <main
        class="w-full max-w-[1200px] grid grid-cols-1 lg:grid-cols-12 overflow-hidden bg-surface-container-low ambient-shadow min-h-[700px]">
        <!-- Branding / Visual Side (Asymmetric Layout) -->
        <section
            class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-12 overflow-hidden bg-primary-container text-on-primary">
            <!-- Decorative Background Element -->
            <div class="absolute inset-0 z-0 opacity-20">
                <img class="w-full h-full object-cover"
                    data-alt="minimalist abstract geometric patterns inspired by architectural blueprints with deep navy and slate blue tones"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuDuqNUVrmpR9lqVlKPh--kQASBYThXKp5jO20q5xCy4GLiGduaKNlTyVDfFvP9ZN7MskiAZ6aDeSCrv0JnamKvoAXJMY91EpVngTymB6FvhtUMsWpl1MWwMSeY44zjrkdbIniwDUF9Yi8vJuSeRu9Fg2Gnj262lX2_AztkXi2R6UFFzeiSdA-MrienQ31MI-9C2FjKMc458LZ5NUsUqwaNXhA0MO4_aXGYrzaYEtWn2ih2gi4LCHnsdFp49yMEpvsfYQzrce4BfuLI" />
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-16">
                    <span class="material-symbols-outlined text-4xl" data-icon="menu_book">menu_book</span>
                    <span class="text-2xl font-extrabold tracking-tighter">The Scholarly Editorial</span>
                </div>
                <h1 class="text-6xl font-extrabold tracking-tight leading-[1.1] max-w-md">
                    Curating the <span class="text-on-primary-container">Future</span> of Learning.
                </h1>
            </div>
            <div class="relative z-10 flex flex-col gap-4">
                <div class="flex -space-x-4">
                    <img class="w-12 h-12 rounded-full border-2 border-primary-container object-cover"
                        data-alt="professional portrait of a diverse academic researcher in a soft-lit library setting"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDmSAWCtZd6tPJGtl-gpXtdtyVEnubts8nWU5FsjyrmVgTKyrybkQtSyoALs8xtFAa0pGSrHo9ksk4OpA5bK07ss4fsvPUyYW5bxVyMcp-sPPUNl4Ydbxq7ebeYPA-PEkG31j-NxMD3R__JHUkhYunACEk8aLqB0AAWnkSI9wZ6evF50xCrH1VRYqCDw6gDIPWK3SIG3QcInAhvFTQDDcozhrogSpmGC-Sh3JOKbmtXKccKIooWCk2lihrbToTsOPt_5Uo--nYi0QA" />
                    <img class="w-12 h-12 rounded-full border-2 border-primary-container object-cover"
                        data-alt="smiling university professor in a modern office with warm natural lighting"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCBxbInv4gsOAA_3fyzFXC022B-qCSizc4o1YBK5SCIO3g9-zCZt2O1PT9OiSRBtNhH9pI1ir32_ASSKcYWhyUKgKoSr1i8DtxObhYWr1FA1P1M1nOWBIBHkH7WKhwc7jYURSafWqD6ZWn0m0OQmhazWJC-uLNFiSvSrGHRc9QWHDDtWLJuUw5GxNiIhFFjGKFEUxv_pcqSXOxHYLCouBpjq_Jp-bUTM6dRXz4IclprYAt8Lo7zTNS--9EQiLUw3A9h_jndNRSYcrM" />
                    <img class="w-12 h-12 rounded-full border-2 border-primary-container object-cover"
                        data-alt="young graduate student studying in a bright contemporary workspace"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBEuqDDuwwa0czM-SG-zV8wDfhlzRQOsfXG7yNEgjw2w6c3DXynEULh4upqSu_ThIrUnAlM_zYF8bmRWfsibkraYLcwX0fOlnnjIzkg46XdxEE2v4r1ULo970hL9McTJK3SUZEwOFe2mjWBQs0UWcq8x9i8fXzYQ74phgG7vI7NWBIhZfyzwkIE_S993TtadrtZkvWepOXjyGt72ZMyZrHYdnVqfRItUpZGK70pHSa4MuDVvIto-5EzOMlB8VjnRh2lchf60eev9fM" />
                    <div
                        class="w-12 h-12 rounded-full border-2 border-primary-container bg-primary flex items-center justify-center text-xs font-bold">
                        +12k</div>
                </div>
                <p class="text-sm font-medium text-primary-fixed-dim max-w-xs">
                    Join a community of 12,000+ scholars pursuing excellence through curated editorial content.
                </p>
            </div>
        </section>
        <!-- Form Side -->
        <section class="lg:col-span-5 bg-surface-container-lowest flex flex-col justify-center p-8 md:p-16 lg:p-20">
            <div class="mb-10 lg:hidden">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-3xl" data-icon="menu_book">menu_book</span>
                    <span class="text-xl font-extrabold tracking-tighter">The Scholarly Editorial</span>
                </div>
            </div>
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-on-surface tracking-tight mb-2">Welcome Back</h2>
                <p class="text-on-surface-variant text-sm">Please enter your scholarly credentials to access your
                    library.</p>
            </div>
            <form class="flex flex-col gap-6" wire:submit.prevent="login">
                @csrf
                <!-- Email Field -->
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
                        for="email">Email Address</label>
                    <div class="relative group">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors"
                            data-icon="mail">mail</span>
                        <input
                            class="w-full pl-12 pr-4 py-4 bg-surface-container-high border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline-variant"
                            id="email" name="email" placeholder="name@university.edu" type="email" wire:model.live="email" />
                    </div>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Password Field -->
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant"
                            for="password">Password</label>
                    </div>
                    <div class="relative group" x-data="{ show: false }">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors"
                            data-icon="lock">lock</span>
                        <input
                            class="w-full pl-12 pr-4 py-4 bg-surface-container-high border-none rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all text-on-surface placeholder:text-outline-variant"
                            id="password" name="password" placeholder="••••••••" :type="show ? 'text' : 'password'" wire:model.live="password" />
                        <button
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors"
                            type="button" @click="show = !show">
                            <span class="material-symbols-outlined" x-text="show ? 'lock_open' : 'lock'">lock</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Sign In Button -->
                <button
                    class="w-full py-4 bg-primary text-on-primary rounded-xl font-bold text-lg tracking-tight hover:shadow-lg hover:shadow-primary/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 mt-2"
                    type="submit">
                    <span>Sign In</span>
                    <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                </button>
            </form>
            <!-- Register Link -->
            <div class="mt-12 pt-8 border-t border-surface-variant flex flex-col gap-4 text-center">
                <p class="text-on-surface-variant text-sm">New to the community?</p>
                <a class="group inline-flex items-center justify-center gap-2 py-3 px-6 rounded-xl bg-primary/5 text-primary font-bold hover:bg-primary/10 transition-all"
                    href="{{ route('register') }}">
                    Create Account
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform"
                        data-icon="east">east</span>
                </a>
            </div>
            <!-- Footer Elements -->
        </section>
    </main>

</div>