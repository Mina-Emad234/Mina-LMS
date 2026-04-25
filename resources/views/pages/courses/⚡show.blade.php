<?php

use App\Models\Course;
use App\Models\Enrolement;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\CourseRating;

new #[Layout('layouts.app', ['class', 'bg-surface font-body text-on-surface'])] class extends Component {
    public Course $course;

    public $enrolemntModal = false;
    public $ratingModal = false;
    public $userRating = 0;

    public function mount($course)
    {
        $this->course = $course;
        if (auth()->check()) {
            $rating = $this->course->ratings()->where('user_id', auth()->id())->first();
            $this->userRating = $rating ? $rating->rating : 0;
        }
    }

    public function openLesson($slug)
    {
        return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $slug]);
    }

    public function resumeLearning()
    {
        return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $this->course->resumeLearning(auth()->id())]);
    }

    public function toggleEnrollmentModal()
    {
        $this->enrolemntModal = !$this->enrolemntModal;
    }

    public function toggleRatingModal()
    {
        $this->ratingModal = !$this->ratingModal;
    }

    public function getIsRatingProperty()
    {
        return $this->course->checkEnrolement(auth()->id()) && !$this->course->checkRating(auth()->id());
    }

    public function setRating($rating)
    {
        $this->userRating = $rating;
        CourseRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $this->course->id,
            ],
            [
                'rating' => $this->userRating,
            ],
        );
        $this->ratingModal = false;
    }
    

    public function enrollCourse()
    {
        Enrolement::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $this->course->id,
            ],
            [
                'enroled_at' => now(),
            ],
        );
        $this->enrolemntModal = false;
    }

    public function reviewCourse()
    {
        return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $this->course->lessons()->first()->slug]);
    }

    public function login()
    {
        return redirect()->route('login');
    }

    public function downloadCertificate()
    {
        return redirect()->route('download.certificate', ['id' => $this->course->enrolements()->where('user_id', auth()->id())->first()->id]);
    }
};
?>

<div>
    <x-ui.main>
        <main class="pt-24 pb-32 px-6 max-w-7xl mx-auto">
            <!-- Hero Section: Cover Image & Title -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
                <div class="lg:col-span-8">
                    <div class="relative rounded-[2rem] overflow-hidden aspect-[21/9] mb-8 bg-slate-200">
                        <img alt="Course Cover" class="w-full h-full object-cover"
                            data-alt="Abstract digital visualization of global economic networks with glowing blue data lines and deep space background"
                            src="{{ $course->image }}" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 right-8">
                            <div class="flex gap-2 mb-4">
                                <span
                                    class="bg-tertiary-container text-on-tertiary-container px-4 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest">{{ $course->level->name }}</span>
                                <span
                                    class="bg-secondary text-on-secondary px-4 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest">{{ $course->category->name }}</span>
                            </div>
                            <h1
                                class="font-headline text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
                                {{ $course->title }}</h1>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-8 py-6 border-b border-outline-variant/15">
                        <div class="flex items-center gap-3">
                            <img alt="Instructor" class="w-12 h-12 rounded-full object-cover"
                                data-alt="Close-up portrait of a female university professor with glasses, looking confident and smiling in a bright office"
                                src="{{ $course->instructor->profile }}" />
                            <div>
                                <p class="text-xs text-on-surface-variant font-medium">Instructor</p>
                                <p class="font-headline font-bold text-on-surface">{{ $course->instructor->name }}</p>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-outline-variant/30 hidden md:block"></div>
                        <div class="flex flex-col">
                            <p class="text-xs text-on-surface-variant font-medium">Rating</p>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-on-surface">{{ $course->rating }}</span>
                                <div class="flex text-on-tertiary-container">
                                    <span class="material-symbols-outlined text-sm"
                                        style="font-variation-settings: 'FILL' 1;">star</span>
                                </div>
                                <span class="text-xs text-on-surface-variant">({{ $course->reviews_count }}
                                    Reviews)</span>
                            </div>
                        </div>
                        <div class="h-10 w-px bg-outline-variant/30 hidden md:block"></div>
                        <div class="flex flex-col">
                            <p class="text-xs text-on-surface-variant font-medium">Duration</p>
                            <p class="font-headline font-bold text-on-surface">{{ $course->period }}</p>
                        </div>
                    </div>
                </div>
                <!-- Sticky Enrollment Card -->
                <div class="lg:col-span-4">
                    <div
                        class="sticky top-28 bg-surface-container-lowest p-8 rounded-[2rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.04)] border border-outline-variant/10">
                        @auth
                            @if($course->isCompleted(auth()->user()->id))
                                <div class="mb-8">
                                    <div class="flex justify-between items-end mb-4">
                                        <div>
                                            <p class="text-secondary font-bold text-sm uppercase tracking-widest mb-1">
                                                Course Status
                                            </p>
                                            <h3 class="text-3xl font-extrabold text-on-surface">100% Complete</h3>
                                        </div>
                                        <span class="material-symbols-outlined text-secondary text-4xl"
                                            style="font-variation-settings: 'FILL' 1;">verified</span>
                                    </div>
                                    <div class="w-full bg-surface-container rounded-full h-3 mb-3">
                                        <div class="bg-secondary h-3 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <p class="text-on-surface-variant text-sm flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                                        Completed on
                                        {{ $course->enrolements()->where('user_id', auth()->user()->id)->first()->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <button wire:click="downloadCertificate"
                                    class="w-full bg-primary-container text-white py-4 rounded-xl font-bold text-lg mb-4 hover:opacity-90 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">workspace_premium</span>
                                    Download Certificate
                                </button>
                                <button wire:click="toggleRatingModal"
                                    class="w-full bg-primary/10 text-primary py-4 rounded-xl font-bold text-lg mb-8 hover:bg-primary/20 transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">rate_review</span>
                                    {{ $userRating > 0 ? 'Update Rating' : 'Rate Course' }}
                                </button>
                            @elseif ($course->isEnrolled(auth()->id()))
                                <div wire:poll.10s class="mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <span
                                            class="flex items-center gap-2 px-3 py-1 bg-secondary/10 text-secondary rounded-full text-sm font-bold">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Enrolled
                                        </span>
                                        <div class="text-right">
                                            <span
                                                class="text-on-surface-variant text-sm font-bold block">{{ round($this->course->getProgressPercentage(auth()->user()->id)) }}%</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden mb-4">
                                        <div class="bg-secondary h-full rounded-full"
                                            style="width: {{ $this->course->getProgressPercentage(auth()->user()->id) }}%">
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="material-symbols-outlined text-[14px] text-on-surface-variant/70">schedule</span>
                                        <span
                                            class="text-[10px] text-on-surface-variant/70 uppercase font-black tabular-nums">{{ $this->course->getProgressPeriod(auth()->id()) }}
                                            / {{ $course->period }} Completed</span>
                                    </div>
                                </div>
                                <button wire:click="resumeLearning"
                                    class="w-full bg-primary-container text-white py-4 rounded-xl font-bold text-lg mb-4 hover:opacity-90 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">play_arrow</span>
                                    Resume Learning
                                </button>
                                <button wire:click="toggleRatingModal"
                                    class="w-full bg-secondary/10 text-secondary py-4 rounded-xl font-bold text-lg mb-4 hover:bg-secondary/20 transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">star</span>
                                    {{ $userRating > 0 ? 'Update Rating' : 'Rate Course' }}
                                </button>
                            @else
                                <button wire:click="toggleEnrollmentModal"
                                    class="w-full bg-primary-container text-white py-4 rounded-xl font-bold text-lg mb-4 hover:opacity-90 transition-all active:scale-[0.98]">
                                    Enroll Now
                                </button>
                            @endif
                        @else
                            <button wire:click="login"
                                class="w-full bg-primary-container text-white py-4 rounded-xl font-bold text-lg mb-4 hover:opacity-90 transition-all active:scale-[0.98]">
                                Sign In to Enroll
                            </button>
                        @endauth
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-sm text-on-surface">
                                <span class="material-symbols-outlined text-primary-container">video_library</span>
                                <span>{{ $course->total_lessons }} HD Video Lessons</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-on-surface">
                                <span class="material-symbols-outlined text-primary-container">workspace_premium</span>
                                <span>University-grade Certification</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-on-surface">
                                <span class="material-symbols-outlined text-primary-container">all_inclusive</span>
                                <span>Lifetime access to updates</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Asymmetric Content Grid -->
            <section class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-7">
                    <!-- Course Description -->
                    <div class="mb-16">
                        <h2 class="font-headline text-3xl font-extrabold text-on-surface mb-6">Course Description</h2>
                        <div class="space-y-4 text-on-surface-variant leading-relaxed text-lg">
                            <p>{{ $course->description }}</p>
                        </div>
                    </div>
                    <!-- Audience & Outcomes (Bento Style) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-16">
                        <div class="bg-surface-container-low p-8 rounded-3xl">
                            <span class="material-symbols-outlined text-primary mb-4 text-3xl">groups</span>
                            <h3 class="font-headline font-bold text-xl mb-2">Target Audience</h3>
                            <p class="text-on-surface-variant text-sm">{{ $course->target_audience }}</p>
                        </div>
                    </div>
                    <!-- Lesson List -->
                    <div class="mb-16">
                        <div class="flex justify-between items-end mb-8">
                            <div>
                                <h2 class="font-headline text-3xl font-extrabold text-on-surface">Curriculum</h2>
                                <p class="text-on-surface-variant">{{ $course->total_lessons }} lessons distributed
                                    across {{ $course->sections()->count() }} modules</p>
                            </div>
                        </div>
                        <div class="space-y-3" wire:poll.10s>
                            @forelse ($course->lessons()->where('is_published', true)->get() as $lesson)
                                @if ($lesson->isUserComplete(auth()->id()) || $lesson->isAvailableForUser(auth()->id()) && auth()->check())
                                    <div wire:click="openLesson('{{ $lesson->slug }}')"
                                        class="group bg-surface-container-lowest p-5 rounded-2xl flex items-center justify-between hover:bg-white transition-colors cursor-pointer border border-transparent hover:border-primary/10">
                                        <div class="flex items-center gap-5">
                                            <span
                                                class="text-2xl font-black text-outline-variant group-hover:text-primary-container transition-colors">{{ $lesson->order }}</span>
                                            <div>
                                                <h4 class="font-headline font-bold text-on-surface">
                                                    {{ $lesson->title }}</h4>
                                                <p class="text-xs text-on-surface-variant">Video •
                                                    {{ $lesson->period }}</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-secondary">play_circle</span>
                                    </div>
                                @else
                                    <div
                                        class="group bg-surface-container p-5 rounded-2xl flex items-center justify-between opacity-70 cursor-not-allowed">
                                        <div class="flex items-center gap-5">
                                            <span
                                                class="text-2xl font-black text-outline-variant">{{ $lesson->order }}</span>
                                            <div>
                                                <h4 class="font-headline font-bold text-on-surface">
                                                    {{ $lesson->title }}</h4>
                                                <p class="text-xs text-on-surface-variant">Video •
                                                    {{ $lesson->period }}</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-outline">lock</span>
                                    </div>
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <!-- Instructor Profile Block -->
                    <div class="bg-primary-container text-white p-10 rounded-[2.5rem] relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <img alt="{{ $course->instructor->name }} Profile"
                                class="w-24 h-24 rounded-2xl object-cover mb-6 border-2 border-white/20 shadow-xl"
                                data-alt="Professional studio headshot of Dr. Aris Thorne, female professor in a navy blazer against a neutral gray background"
                                src="{{ $course->instructor->profile }}" />
                            <h3 class="font-headline text-2xl font-bold mb-1">{{ $course->instructor->name }}</h3>
                            <p class="text-primary-fixed-dim text-sm font-medium mb-6">{{ $course->instructor->bio }}
                            </p>

                            <div class="flex flex-wrap items-center gap-3">
                                <a class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl text-sm font-bold hover:bg-white/20 transition-all"
                                    href="{{ $course->instructor->linkedin }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 48 48">
                                        <path fill="#0288D1"
                                            d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z">
                                        </path>
                                        <path fill="#FFF"
                                            d="M12 19H17V36H12zM14.485 17h-.028C12.965 17 12 15.888 12 14.499 12 13.08 12.995 12 14.514 12c1.521 0 2.458 1.08 2.486 2.499C17 15.887 16.035 17 14.485 17zM36 36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698-1.501 0-2.313 1.012-2.707 1.99C24.957 25.543 25 26.511 25 27v9h-5V19h5v2.616C25.721 20.5 26.85 19 29.738 19c3.578 0 6.261 2.25 6.261 7.274L36 36 36 36z">
                                        </path>
                                    </svg>
                                    LinkedIn
                                </a>
                                <a href="mailto:{{ $course->instructor->email }}"
                                    class="w-10 h-10 bg-white/10 flex items-center justify-center rounded-xl hover:bg-white/20 transition-all"
                                    title="Email Instructor">
                                    <span class="material-symbols-outlined text-xl">mail</span>
                                </a>
                                <a href="tel:{{ $course->instructor->phone }}"
                                    class="w-10 h-10 bg-white/10 flex items-center justify-center rounded-xl hover:bg-white/20 transition-all"
                                    title="Call Instructor">
                                    <span class="material-symbols-outlined text-xl">call</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Course Stats -->
                    <div class="mt-8 grid grid-cols-2 gap-4">
                        <div
                            class="bg-surface-container-lowest p-6 rounded-3xl border border-outline-variant/10 text-center">
                            <p class="text-3xl font-black text-primary-container">{{ $course->enrolements_count }}+
                            </p>
                            <p class="text-xs text-on-surface-variant uppercase font-bold tracking-widest mt-2">Active
                                Students</p>
                        </div>
                        <div
                            class="bg-surface-container-lowest p-6 rounded-3xl border border-outline-variant/10 text-center">
                            <p class="text-3xl font-black text-secondary">{{ $course->rating }}/5</p>
                            <p class="text-xs text-on-surface-variant uppercase font-bold tracking-widest mt-2">Avg
                                Rating
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    @if ($enrolemntModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-data>
            <!-- Backdrop -->
            <div wire:click="toggleEnrollmentModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>

            <!-- Modal Card -->
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="relative bg-surface-container-lowest w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden">
                <!-- Course Preview Header -->
                <div class="relative h-48">
                    <img src="{{ $course->image }}" class="w-full h-full object-cover" alt="{{ $course->title }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <h3 class="text-white font-headline text-2xl font-bold leading-tight">{{ $course->title }}
                        </h3>
                        <p class="text-white/80 text-sm mt-1">Ready to start your journey?</p>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="flex items-center gap-3 text-on-surface">
                            <div class="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center">
                                <span
                                    class="material-symbols-outlined text-primary-container text-xl">video_library</span>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider font-bold text-on-surface-variant">
                                    Lessons</p>
                                <p class="text-sm font-bold">{{ $course->total_lessons }} Units</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-on-surface">
                            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-xl">schedule</span>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider font-bold text-on-surface-variant">
                                    Duration</p>
                                <p class="text-sm font-bold">{{ $course->period }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button wire:click="enrollCourse"
                            class="w-full bg-primary text-on-primary py-4 rounded-2xl font-bold text-lg hover:opacity-95 transition-all active:scale-[0.98] shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">auto_awesome</span>
                            Confirm Enrollment
                        </button>
                        <button wire:click="toggleEnrollmentModal"
                            class="w-full bg-surface-container-high text-on-surface py-4 rounded-2xl font-bold text-lg hover:bg-surface-container-highest transition-all active:scale-[0.98]">
                            Not Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($ratingModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-data="{ hover: 0 }">
            <!-- Backdrop -->
            <div wire:click="toggleRatingModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>

            <!-- Modal Card -->
            <div x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="relative bg-surface-container-lowest w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
                
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-secondary/10 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-secondary text-4xl" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    
                    <h3 class="text-on-surface font-headline text-2xl font-bold mb-2">Rate this Course</h3>
                    <p class="text-on-surface-variant text-sm mb-8">How would you rate your learning experience?</p>
                    
                    <div class="flex justify-center gap-2 mb-10">
                        <template x-for="i in 5">
                            <button 
                                @click="$wire.setRating(i)"
                                @mouseenter="hover = i"
                                @mouseleave="hover = 0"
                                class="transition-all duration-200 transform hover:scale-125 focus:outline-none"
                            >
                                <span 
                                    class="material-symbols-outlined text-4xl"
                                    :style="(hover >= i || ($wire.userRating >= i && hover === 0)) ? 'font-variation-settings: \'FILL\' 1;' : ''"
                                    :class="(hover >= i || ($wire.userRating >= i && hover === 0)) ? 'text-secondary' : 'text-outline-variant'"
                                >star</span>
                            </button>
                        </template>
                    </div>
                    
                    <div class="flex gap-4">
                        <button wire:click="toggleRatingModal"
                            class="flex-1 bg-surface-container-high text-on-surface py-4 rounded-2xl font-bold hover:bg-surface-container-highest transition-all active:scale-[0.98]">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </x-ui.main>


</div>
