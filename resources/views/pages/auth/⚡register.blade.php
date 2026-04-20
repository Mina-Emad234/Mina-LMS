<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

use App\Actions\Fortify\CreateNewUser;

new #[Layout('layouts.app', ['class' => 'bg-surface text-on-surface min-h-screen flex flex-col overflow-x-hidden'])] class extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';

    public function register(CreateNewUser $creator)
    {
        $creator->create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        auth()->attempt(['email' => $this->email, 'password' => $this->password]);

        return redirect()->intended(config('fortify.home'));
    }
};
?>

<div>

      <main class="flex-grow flex flex-col lg:flex-row min-h-screen">
    <!-- Left Column: Editorial Brand Presence -->
    <section
      class="lg:w-5/12 bg-primary flex flex-col justify-between p-12 lg:p-20 relative overflow-hidden text-on-primary">
      <!-- Decorative Background Element -->
      <div
        class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-container rounded-full blur-[120px] opacity-50">
      </div>
      <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-secondary rounded-full blur-[100px] opacity-30">
      </div>
      <div class="relative z-10">
        <div class="flex items-center gap-3 mb-16">
          <span class="material-symbols-outlined text-4xl" data-icon="menu_book"
            style="font-variation-settings: 'FILL' 1;">menu_book</span>
          <span class="text-2xl font-extrabold tracking-tighter headline-font">The Scholarly Editorial</span>
        </div>
        <h1 class="text-5xl lg:text-7xl font-extrabold headline-font leading-[1.1] mb-8 tracking-tighter">
          Elevate Your <span class="text-primary-fixed-dim italic">Intellectual</span> Journey.
        </h1>
        <p class="text-xl lg:text-2xl text-on-primary/80 font-light max-w-md leading-relaxed">
          Join an exclusive community of curators, scholars, and lifelong learners.
        </p>
      </div>
      <div class="relative z-10 mt-12 lg:mt-0">
        <div class="p-6 bg-white/10 backdrop-blur-md rounded-2xl inline-flex items-center gap-4 border border-white/5">
          <div class="flex -space-x-3">
            <img alt="Curator Profile" class="w-10 h-10 rounded-full border-2 border-primary"
              data-alt="Portrait of a professional academic male with glasses and a friendly expression in a minimalist studio setting"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUazu_drZ81jAxyDsmi49LAX-i8owfYw_NfsEk04CiL0hxYEsm3GOSa2hGmqdw0cPUoFPXwvoIWGBkQSdlRIRzRMNa48RQMpO-VwLyyV43LkBjJgFgiKbLL5Kxd9JN0fd4T6BSEXf_TczpMjv4TuQQs9ww3Mp0-fIAeftWZUgG_beNVdEIDXoQK1-FXxj4bAv-KXiowa5CU9I683WJYcLbPcAwyc8BSJf-NRmLCTg9pUKP_aam7yqXtgV_-X5d9gPO0G6zC8vnZ8Q" />
            <img alt="Curator Profile" class="w-10 h-10 rounded-full border-2 border-primary"
              data-alt="Close-up of a sophisticated woman with organized workspace background and soft natural lighting"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrXTzZtyTLgt5FAx5pyt6gpqInRINpbqffI2CEhUCp0Eu8c1AeQ_qT9E041hQFvsX6O-Lt3v-nlp2lPLDi6uKGT_s4TFtqgQrL4MS7ku3uoNTfxFes77jDPzCG746hwxXpv6ddQ3OF6EeS8ff98JoyKftBh2XBOyQRNa1tZqJDiPKvTK5lyL6bYVS1JlRcf7AFFhaCDdZex-ohh1UuasrLsBZUkjmKoNpsKuCmsu6BcWK12dmzUPaF9PqOnK3PPG7JKSjF7TKDoRg" />
            <img alt="Curator Profile" class="w-10 h-10 rounded-full border-2 border-primary"
              data-alt="Modern professional man in a library setting with warm ambient glow and blurred bookshelves"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuB85Imgcesvinbn9zmfDquiMTQsFGL7pdi-UdVAsiuv1THvNZpRvxaKVMDq6dgdxH3RWS5adKRlt49Lplq2O96fGLCKTarBoq0dhYIlCWrCEc16yXgrf45KiKgBiU5q3LeSOJt2meFJUhkyPBuXJgvRs1_AhrvCJQAQRwHAi5CcoT980m40YKqTVTvpk-TimThGszhqv1fP0DyXyVat3CyWTya37yunePJvJAELe96c_n6P_jsmPtH4_w0qcEYemwwliuJAZIi6-wY" />
          </div>
          <p class="text-sm font-medium tracking-wide">Join 12,000+ Active Scholars</p>
        </div>
      </div>
    </section>
    <!-- Right Column: Registration Form Canvas -->
    <section class="lg:w-7/12 bg-surface-container-low flex items-center justify-center p-6 lg:p-24">
      <div
        class="w-full max-w-lg bg-surface-container-lowest p-10 lg:p-14 rounded-[2rem] shadow-[0_4px_32px_rgba(0,0,0,0.02)]">
        <header class="mb-10">
          <h2 class="text-3xl font-extrabold headline-font text-on-surface mb-2 tracking-tight">Create Your Account</h2>
          <p class="text-on-surface-variant font-body">Begin your curated learning experience today.</p>
        </header>
        <form class="space-y-6" wire:submit.prevent="register">
          @csrf
          <!-- Full Name Field -->
          <div class="space-y-2">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
              for="full_name">Full Name</label>
            <div class="relative">
              <input
                class="w-full bg-surface-container-high border-none rounded-xl px-4 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant text-on-surface"
                id="full_name" placeholder="Johnathan Doe" type="text" wire:model.live="name" />
              <span class="material-symbols-outlined absolute right-4 top-4 text-outline-variant"
                data-icon="person">person</span>
            </div>
            @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
          </div>
          <!-- Email Field -->
          <div class="space-y-2">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
              for="email">Email Address</label>
            <div class="relative">
              <input
                class="w-full bg-surface-container-high border-none rounded-xl px-4 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant text-on-surface"
                id="email" placeholder="scholar@editorial.com" type="email" wire:model.live="email" />
              <span class="material-symbols-outlined absolute right-4 top-4 text-outline-variant"
                data-icon="mail">mail</span>
            </div>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
          </div>
         <!-- Phone Field -->
          <div class="space-y-2">
            <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
              for="phone">Phone Number</label>
            <div class="relative">
              <input
                class="w-full bg-surface-container-high border-none rounded-xl px-4 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant text-on-surface"
                id="phone" placeholder="08123456789" type="text" wire:model.live="phone" />
              <span class="material-symbols-outlined absolute right-4 top-4 text-outline-variant"
                data-icon="phone">phone</span>
            </div>
            @error('phone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
          </div>
          <!-- Password Field -->
          <div class="grid lg:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
                for="password">Password</label>
              <div class="relative" x-data="{ show: false }">
                <input
                  class="w-full bg-surface-container-high border-none rounded-xl px-4 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant text-on-surface"
                  id="password" placeholder="••••••••" :type="show ? 'text' : 'password'" wire:model.live="password" />
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
            <div class="space-y-2">
              <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1"
                for="confirm_password">Confirm</label>
              <div class="relative" x-data="{ show: false }">
                <input
                  class="w-full bg-surface-container-high border-none rounded-xl px-4 py-4 focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest transition-all placeholder:text-outline-variant text-on-surface"
                  id="confirm_password" placeholder="••••••••" :type="show ? 'text' : 'password'" wire:model.live="password_confirmation" />
               <button
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors"
                  type="button" @click="show = !show">
                  <span class="material-symbols-outlined" x-text="show ? 'lock_open' : 'lock'">lock</span>
                </button>
              </div>
            </div>
            @error('password_confirmation')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
          </div>
        
          <!-- Sign Up CTA -->
          <button
            class="w-full bg-primary text-on-primary font-bold py-5 rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-container hover:shadow-xl hover:shadow-primary/30 active:scale-[0.98] transition-all duration-200 text-lg headline-font tracking-tight mt-4"
            type="submit">
            Create Account
          </button>
          <!-- Login Link -->
          <p class="text-center text-on-surface-variant text-sm mt-8">
            Already part of the editorial?
            <a class="text-primary font-bold hover:underline decoration-2 underline-offset-4 transition-all ml-1"
              href="{{ route('login') }}">Sign In</a>
          </p>
        </form>
      </div>
    </section>
  </main>
</div>