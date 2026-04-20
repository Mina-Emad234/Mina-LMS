<?php

use App\Models\Course;
use App\Models\Lesson;
use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app', ['class', 'bg-surface font-body text-on-surface'])] class extends Component {
    public Course $course;
    public Lesson $lesson;
    public $sections;
    public $isFirstLesson;

    public function mount($course, $lesson)
    {   
        $this->course = $course;
        $this->lesson = $lesson;
        $this->isFirstLesson = $lesson->order === 1;
        $this->sections = $this->course->sections()->whereHas('lessons')->get();
    }

    public function getIsLastLessonProperty()
    {
        return $this->lesson->order === $this->course->lessons()->count()||!$this->lesson->isUserComplete(auth()->id());
    }

    public function nextLesson()
    {
        $nextLesson = $this->course->lessons()->isPublished()->where('order', '>', $this->lesson->order)->first();
        if ($nextLesson && $this->lesson->isUserComplete(auth()->id())) {
            return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $nextLesson->slug]);
        }
    }

    public function previousLesson()
    {
        $previousLesson = $this->course->lessons()->where('order', '<', $this->lesson->order)->first();
        if ($previousLesson) {
            return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $previousLesson->slug]);
        }
    }

    public function markAsComplete()
    {
        $this->lesson->markAsComplete(auth()->id());
    }

    public function openLesson($lessonSlug)
    {
        return redirect()->route('courses.lessons.show', ['course' => $this->course->slug, 'lesson' => $lessonSlug]);
    }
};
?>

<div>

    <x-ui.main>
        <main class="pt-24 pb-20 px-4 md:px-8 max-w-[1600px] mx-auto min-h-screen">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Main Content: Player and Description -->
                <div class="lg:col-span-8 flex flex-col gap-8">
                    <div x-data="{ 
                        player: null,
                        init() {
                            this.player = new Plyr(this.$refs.player, {
                                controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
                                settings: ['captions', 'quality', 'speed'],
                                speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5, 2] }
                            });
                        },
                        destroy() {
                            if (this.player) this.player.destroy();
                        }
                    }" wire:ignore id="player" class="plyr__video-embed relative aspect-video rounded-[2rem] overflow-hidden bg-surface-container-highest shadow-2xl group">
                        <iframe src="{{ $this->lesson->video_url }}"
                            allowfullscreen allowtransparency allow="autoplay"></iframe>
                    </div>
                    <!-- Navigation & Action Bar -->
                    <div wire:poll.10s
                        class="flex flex-wrap items-center justify-between gap-4 bg-surface-container-lowest p-6 rounded-[1.5rem]">
                        <div class="flex items-center gap-3">
                            <button wire:click="previousLesson" @disabled($isFirstLesson)
                                class="flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary font-bold hover:bg-primary/20 rounded-xl transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                                Previous
                            </button>
                            <button wire:click="nextLesson" @disabled($this->isLastLesson)
                                class="flex items-center gap-2 px-6 py-2 bg-primary text-on-primary font-bold rounded-xl shadow-lg hover:shadow-primary/20 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next Lesson
                                <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                            </button>
                        </div>
                        @php
                            $isCurrentCompleted = $this->course->checkLessonProgress(auth()->id(), $this->lesson->id);
                        @endphp
                        <button wire:click="markAsComplete" @disabled($isCurrentCompleted)
                            class="flex items-center gap-2 px-6 py-3 border-2 border-secondary text-secondary font-extrabold rounded-xl hover:bg-secondary hover:text-white hover:shadow-lg hover:shadow-secondary/20 transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="material-symbols-outlined" data-icon="check_circle"
                                style="font-variation-settings: 'FILL' {{ $isCurrentCompleted ? 1 : 0 }}">check_circle</span>
                            {{ $isCurrentCompleted ? 'Completed' : 'Mark as Complete' }}
                        </button>
                    </div>
                    <!-- Textual Content (Editorial Layout) -->
                    <article class="bg-surface-container-low p-8 md:p-12 rounded-[2.5rem]">
                        <div class="flex items-center gap-2 mb-6">
                            <span
                                class="px-3 py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-[10px] font-bold uppercase tracking-[0.1em]">{{ $course->level->name }}</span>
                            <span class="text-outline text-sm">•</span>
                            <span class="text-outline text-sm font-medium">{{ $lesson->section->name ?? 'Introduction' }}, Lesson
                                {{ str_pad($lesson->order, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1
                            class="font-headline text-4xl md:text-5xl font-extrabold text-on-surface mb-8 tracking-tight leading-tight">
                            {{ $lesson->title }}</h1>
                        <div class="flex gap-12 flex-col md:flex-row">
                            <div class="flex-1">
                                <div class="text-lg text-on-surface-variant leading-relaxed mb-6 font-body prose prose-slate max-w-none">
                                    {!! $lesson->description !!}
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <!-- Sidebar: Course Curriculum -->
                <aside class="lg:col-span-4 flex flex-col gap-6 sticky top-24">
                    <div class="bg-surface-container-low rounded-[2rem] overflow-hidden">
                        <!-- Progress Header -->
                        <div class="p-8 bg-primary text-white">
                            <h3 class="font-headline font-extrabold text-xl mb-4">Course Progress</h3>
                            <div class="w-full h-2 bg-white/20 rounded-full mb-2">
                                <div class="h-full bg-secondary rounded-full shadow-[0_0_8px_rgba(78,222,163,0.5)] transition-all duration-1000"
                                    style="width: {{ $this->course->getProgressPercentage(auth()->id()) }}%">
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold uppercase tracking-wider text-primary-fixed-dim">
                                    {{ $this->course->getProgressCount(auth()->id()) }} Lessons
                                </span>
                                <span class="text-xs font-bold text-white">{{ round($this->course->getProgressPercentage(auth()->id())) }}% Complete</span>
                            </div>
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-white/10">
                                <span class="text-[10px] font-black uppercase tracking-widest text-primary-fixed-dim/70">Duration Progress</span>
                                <span class="text-[10px] font-bold text-white/90">
                                    {{ $this->course->getProgressPeriod(auth()->id()) }} / {{ $this->course->period }}
                                </span>
                            </div>
                        </div>
                        <!-- Lesson List -->
                        <div wire:poll.10s class="p-4 flex flex-col gap-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            @foreach ($this->sections as $section)
                                <h4 class="px-4 pt-4 pb-2 text-[10px] font-black uppercase tracking-[0.2em] text-outline">
                                    {{ $section->name }}</h4>
                                @foreach ($section->lessons()->where('is_published', true)->get() as $sectionLesson)
                                    @php
                                        $isCurrent = $sectionLesson->id === $lesson->id;
                                        $isCompleted = $this->course->checkLessonProgress(auth()->id(), $sectionLesson->id);
                                        $isAvailable = $sectionLesson->isAvailableForUser(auth()->id())
                                    @endphp
                                    <div @if (!$isCurrent && $isAvailable) wire:click="openLesson('{{ $sectionLesson->slug }}')" @endif
                                        @class([
                                            'flex items-center justify-between p-4 rounded-2xl group transition-all',
                                            'shadow-xl shadow-primary/20 scale-[1.02]' => $isCurrent || $isCompleted,
                                            'bg-primary-container shadow-primary/20' => $isCurrent,
                                            'hover:bg-surface-container-high' => !$isCurrent && !$isCompleted && $isAvailable,
                                            'cursor-pointer hover:bg-primary-container/10' => $isAvailable && !$isCurrent,
                                            'cursor-not-allowed opacity-60' => !$isAvailable,
                                        ])>
                                        <div class="flex items-center gap-4">
                                            @if ($isCompleted)
                                                <span @class([
                                                    'material-symbols-outlined text-secondary',
                                                    'text-white' => $isCurrent,
                                                ]) style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                            @elseif($isAvailable)
                                                <span @class([
                                                    'material-symbols-outlined animate-pulse',
                                                    'text-white' => $isCurrent,
                                                ]) style="font-variation-settings: 'FILL' 1;">play_circle</span>
                                            @else
                                                <span class="material-symbols-outlined text-outline">lock</span>
                                            @endif
                                            <div>
                                                <p @class(['text-sm font-bold', 'text-white' => $isCurrent, 'text-on-surface' => !$isCurrent])>
                                                    {{ $sectionLesson->title }}</p>
                                                <p @class([
                                                    'text-[10px] uppercase tracking-tighter font-bold',
                                                    'text-primary-fixed-dim' => $isCurrent,
                                                    'text-outline' => !$isCurrent,
                                                ])>
                                                    {{ $isCurrent ? 'Now Playing' : ($isCompleted ? 'Completed' : $sectionLesson->period) }}
                                                </p>
                                            </div>
                                        </div>
                                        <span @class([
                                            'text-[10px] font-mono',
                                            'text-primary-fixed-dim' => $isCurrent,
                                            'text-outline' => !$isCurrent,
                                        ])>{{ $sectionLesson->period }}</span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="p-4 pt-0">
                            <a href="{{ route('courses.show', $this->course->slug) }}"
                                class="w-full flex items-center justify-center gap-2 py-3 bg-surface-container-high text-on-surface text-sm font-bold rounded-xl hover:bg-surface-container-highest transition-all group">
                                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
                                View Course Details
                            </a>
                        </div>
                    </div>
                    <!-- Instructor Card -->
                    <div class="bg-surface-container-lowest p-6 rounded-[1.5rem] flex items-center gap-4 shadow-sm">
                        <img alt="Instructor" class="w-12 h-12 rounded-xl object-cover"
                            data-alt="close-up of a distinguished academic male professor with gray hair and glasses in a bright architectural setting"
                            src="{{ $this->course->instructor->profile }}" />
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-primary">
                                Instructor</p>
                            <h5 class="font-headline font-bold text-on-surface">{{ $this->course->instructor->name }}
                            </h5>
                            <p class="text-xs text-outline">{{ $this->course->instructor->bio }}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-auto">
                            <a href="{{ $this->course->instructor->linkedin }}" target="_blank"
                                class="text-primary p-2 hover:bg-primary/5 rounded-full transition-colors flex items-center justify-center"
                                title="LinkedIn Profile">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 48 48">
                                    <path fill="#0288D1"
                                        d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z">
                                    </path>
                                    <path fill="#FFF"
                                        d="M12 19H17V36H12zM14.485 17h-.028C12.965 17 12 15.888 12 14.499 12 13.08 12.995 12 14.514 12c1.521 0 2.458 1.08 2.486 2.499C17 15.887 16.035 17 14.485 17zM36 36h-5v-9.099c0-2.198-1.225-3.698-3.192-3.698-1.501 0-2.313 1.012-2.707 1.99C24.957 25.543 25 26.511 25 27v9h-5V19h5v2.616C25.721 20.5 26.85 19 29.738 19c3.578 0 6.261 2.25 6.261 7.274L36 36 36 36z">
                                    </path>
                                </svg>
                            </a>
                            <a href="mailto:{{ $this->course->instructor->email }}"
                                class="text-primary p-2 hover:bg-primary/5 rounded-full transition-colors flex items-center justify-center"
                                title="Email Instructor">
                                <span class="material-symbols-outlined text-2xl">mail</span>
                            </a>
                            <a href="tel:{{ $this->course->instructor->phone }}"
                                class="text-primary p-2 hover:bg-primary/5 rounded-full transition-colors flex items-center justify-center"
                                title="Call Instructor">
                                <span class="material-symbols-outlined text-2xl">call</span>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </x-ui.main>
</div>
