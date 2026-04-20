<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Course;
use App\Models\Category;
use App\Models\Level;
use Livewire\Attributes\Url;

new #[Layout('layouts.app', ['class', 'bg-background font-body text-on-surface antialiased'])] class extends Component {
    use WithPagination;
    #[Url]
    public $search;
    #[Url]
    public $category_id;
    #[Url]
    public $level_id;
    #[Url]
    public $sort;
    #[Url]
    public $categorySearch = '';
    #[Url]
    public $levelSearch = '';

    protected $paginationTheme = 'tailwind';

    protected $updatesQueryString = ['search', 'category_id', 'level_id', 'sort', 'categorySearch', 'levelSearch'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getCoursesProperty()
    {
        $search = $this->search;
        $categoryId = $this->category_id;
        $levelId = $this->level_id;
        $sort = $this->sort;

        return Course::query()
            ->with(['instructor', 'level', 'category'])
            ->when($search, fn($q) => $q->whereLike('title', "%{$search}%"))
            ->when($this->category_id, fn($q) => $q->where('category_id', $this->category_id))
            ->when($this->level_id, fn($q) => $q->where('level_id', $this->level_id))
            ->when(
                $this->sort,
                fn($q) => match ($this->sort) {
                    'newest' => $q->orderByDesc('created_at'),
                    'highest_rating' => $q->orderByDesc('rating'),
                    'featured' => $q->orderByDesc('is_featured'),
                    default => null,
                },
            )
            ->paginate(PAGINATION_COUNT);
    }

    public function getCategoriesProperty()
    {
        return Category::query()
            ->when($this->categorySearch, fn($q) => $q->whereLike('name', "%{$this->categorySearch}%"))
            ->cursorPaginate(PAGINATION_COUNT);
    }

    public function getLevelsProperty()
    {
        return Level::query()
            ->when($this->levelSearch, fn($q) => $q->whereLike('name', "%{$this->levelSearch}%"))
            ->cursorPaginate(PAGINATION_COUNT);
    }

    public function openCourse($slug)
    {
        return redirect()->route('courses.show', $slug);
    }

    public function loginPage()
    {
        return redirect()->route('login');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category_id', 'level_id', 'sort']);
        $this->resetPage();
    }
};
?>

<div>
    <x-ui.main>
        <main class="pt-24 pb-32 px-6 max-w-7xl mx-auto">
            <!-- Hero Section -->
            <section class="mb-12">
                <div class="max-w-3xl">
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-black font-headline tracking-tighter text-on-surface mb-6 leading-[1.1]">
                        Curated Knowledge for <span class="text-primary">Discerning Minds.</span>
                    </h2>
                    <p class="text-lg text-on-surface-variant font-body leading-relaxed opacity-80">
                        Explore our collection of expert-led courses designed with editorial precision and scholarly
                        depth.
                    </p>
                </div>
            </section>
            <!-- Search and Filters -->
            <section class="mb-12 sticky top-20 md:top-24 z-40 bg-white/80 backdrop-blur-md py-4 border-b border-slate-100/50">
                <div class="flex flex-col lg:flex-row gap-4 items-center">
                    <!-- Search Bar -->
                    <div class="relative w-full md:flex-1 group" x-data="{ search: @entangle('search') }">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                        <input wire:model.live.debounce.300ms="search"
                            class="w-full h-14 pl-12 pr-12 bg-slate-50 border border-slate-100 rounded-xl font-body text-slate-900 focus:outline-none focus:border-primary focus:bg-white transition-all shadow-sm group-hover:shadow-md"
                            placeholder="Search for courses, authors, or subjects..." type="text" />
                        <button x-show="search" @click="search = ''"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-xl">close</span>
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:flex gap-2 md:gap-3 w-full lg:w-auto items-center">
                        <!-- Searchable Category -->
                        <div class="relative" x-data="{ 
                            open: false, 
                            selected: @entangle('category_id').live,
                            options: @js($this->categories->items()),
                            get selectedName() {
                                let opt = this.options.find(o => o.id == this.selected);
                                return opt ? opt.name : 'Category';
                            }
                        }">
                            <button @click="open = !open"
                                class="h-14 px-4 md:px-6 bg-white border border-slate-100 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-all flex items-center justify-between gap-2 md:gap-4 w-full md:min-w-[160px] shadow-sm active:scale-95">
                                <span x-text="selectedName" class="truncate"></span>
                                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-cloak x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                class="absolute left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-blue-50 z-[60] overflow-hidden">
                                <div class="p-4 border-b border-slate-50">
                                    <div class="relative">
                                        <span
                                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                                        <input wire:model.live.debounce.300ms="categorySearch" placeholder="Filter categories..."
                                            class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-sm focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all">
                                    </div>
                                </div>
                                <div class="max-h-64 overflow-y-auto p-2 scrollbar-hide">
                                    <button @click="selected = ''; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm text-slate-500 hover:bg-blue-50 hover:text-primary rounded-lg transition-colors">
                                        All Categories
                                    </button>
                                    <template x-for="category in options" :key="category.id">
                                        <button @click="selected = category.id; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-all"
                                            :class="selected == category.id ?
                                                'bg-primary text-white font-bold shadow-lg shadow-primary/20' :
                                                'text-slate-700 hover:bg-blue-50 hover:text-primary'">
                                            <span x-text="category.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Searchable Level -->
                        <div class="relative" x-data="{ 
                            open: false, 
                            selected: @entangle('level_id').live,
                            options: @js($this->levels->items()),
                            get selectedName() {
                                let opt = this.options.find(o => o.id == this.selected);
                                return opt ? opt.name : 'Level';
                            }
                        }">
                            <button @click="open = !open"
                                class="h-14 px-4 md:px-6 bg-white border border-slate-100 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-all flex items-center justify-between gap-2 md:gap-4 w-full md:min-w-[140px] shadow-sm active:scale-95">
                                <span x-text="selectedName" class="truncate"></span>
                                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-cloak x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                class="absolute left-0 mt-2 w-72 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-blue-50 z-[60] overflow-hidden">
                                <div class="p-4 border-b border-slate-50">
                                    <div class="relative">
                                        <span
                                            class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                                        <input wire:model.live.debounce.300ms="levelSearch" placeholder="Filter levels..."
                                            class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-lg text-sm focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all">
                                    </div>
                                </div>
                                <div class="max-h-64 overflow-y-auto p-2 scrollbar-hide">
                                    <button @click="selected = ''; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm text-slate-500 hover:bg-blue-50 hover:text-primary rounded-lg transition-colors">
                                        All Levels
                                    </button>
                                    <template x-for="level in options" :key="level.id">
                                        <button @click="selected = level.id; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm rounded-lg transition-all"
                                            :class="selected == level.id ?
                                                'bg-primary text-white font-bold shadow-lg shadow-primary/20' :
                                                'text-slate-700 hover:bg-blue-50 hover:text-primary'">
                                            <span x-text="level.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Toggle -->
                        <div class="relative" x-data="{
                            open: false,
                            selected: @entangle('sort').live,
                            get selectedName() {
                                const names = { 'newest': 'Newest', 'highest_rating': 'Top Rated', 'featured': 'Featured' };
                                return names[this.selected] || 'Sort';
                            }
                        }">
                            <button @click="open = !open"
                                class="h-14 px-4 md:px-6 bg-white border border-slate-100 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-all flex items-center justify-between gap-2 md:gap-4 w-full md:min-w-[120px] shadow-sm active:scale-95">
                                <span x-text="selectedName" class="truncate"></span>
                                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''">swap_vert</span>
                            </button>
                            <div x-cloak x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-blue-50 z-[60] overflow-hidden">
                                <div class="p-2">
                                    <template
                                        x-for="(name, val) in { 'newest': 'Newest', 'highest_rating': 'Top Rated', 'featured': 'Featured' }"
                                        :key="val">
                                        <button @click="selected = val; open = false"
                                            class="w-full text-left px-4 py-3 text-sm rounded-lg transition-all"
                                            :class="selected == val ?
                                                'bg-primary text-white font-bold shadow-lg shadow-primary/20' :
                                                'text-slate-700 hover:bg-blue-50 hover:text-primary'">
                                            <span x-text="name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        @if ($search || $category_id || $level_id || $sort)
                            <button wire:click="resetFilters"
                                class="h-14 px-6 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all flex items-center gap-2 shrink-0 active:scale-90 duration-200 col-span-2 md:col-span-1">
                                <span class="material-symbols-outlined text-lg">restart_alt</span>
                                <span>Reset</span>
                            </button>
                        @endif
                    </div>
                </div>
            </section>
            <!-- Course Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($this->courses as $course)
                    <div
                        class="group bg-surface-container-lowest rounded-[2rem] p-6 shadow-sm hover:bg-surface-bright transition-all duration-300 flex flex-col">
                        <div class="relative w-full aspect-square rounded-3xl overflow-hidden mb-6 shrink-0">
                            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                src="{{ $course->image }}" alt="{{ $course->title }}" />
                            <div class="absolute top-4 left-4">
                                <span
                                    class="bg-secondary-container/30 text-on-secondary-container px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider font-label">{{ $course->category->name }}</span>
                            </div>
                            @auth
                                <div class="absolute top-4 right-4">
                                    @if (auth()->user()->checkUserCourse($course->id) && $course->isCompleted(auth()->user()->id))
                                        <span
                                            class="bg-primary text-on-primary px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider font-label flex items-center gap-1"><span
                                                class="material-symbols-outlined text-[14px]">task_alt</span>Completed</span>
                                    @elseif (auth()->user()->checkUserCourse($course->id) && $course->getProgressPercentage(auth()->user()->id) < 100)
                                        <span
                                            class="bg-white/90 backdrop-blur-md text-primary px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider font-label shadow-sm">{{ round($course->getProgressPercentage(auth()->user()->id), 2) }}%
                                            Complete</span>
                                    @endif
                                </div>
                            @endauth
                        </div>
                        <div class="flex-1 flex flex-col">
                            {{-- <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-yellow-500 text-lg">star</span>
                                        <span class="text-on-surface font-bold">{{ $course->rating }}</span>
                                        <span class="text-on-surface-variant text-sm">({{ $course->review_count }})</span>
                                    </div>
                                    <span class="text-on-surface-variant text-sm font-medium">{{ $course->total_lessons }} lessons</span>
                                </div> --}}
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-1 text-tertiary-container">
                                    <span class="text-sm">⭐</span>
                                    <span class="font-bold text-xs">{{ $course->rating }}/5</span>
                                </div>
                                <span
                                    class="text-red-600 bg-red-50 px-3 py-1 rounded-md font-bold text-[10px] uppercase tracking-widest border border-red-100">{{ $course->level->name }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-on-surface mb-2 font-headline">{{ $course->title }}</h3>
                            <p
                                class="text-sm text-on-surface-variant font-body leading-relaxed opacity-80 mb-4 flex-1 line-clamp-2">
                                {{ $course->description }}</p>

                            <div class="flex items-center gap-3 mb-4">
                                <img class="w-8 h-8 rounded-full object-cover"
                                    src="{{ $course->instructor->profile }}" alt="{{ $course->instructor->name }}" />
                                <span class="text-on-surface font-medium">{{ $course->instructor->name }}</span>
                            </div>
                            @auth
                            @if (auth()->user()->checkUserCourse($course->id))
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-2"><span
                                            class="text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Progress</span><span
                                            class="text-[11px] font-bold text-primary">{{ round($course->getProgressPercentage(auth()->user()->id)) }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full"
                                            style="width: {{ $course->getProgressPercentage(auth()->user()->id) }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @endauth
                            <button @class([
                                'bg-primary text-on-primary' => $course->is_featured,
                                'bg-primary/10 text-primary' => !$course->is_featured,
                                'h-11 w-full font-bold rounded-xl hover:bg-primary hover:text-on-primary transition-all duration-200',
                            ])
                                wire:click="openCourse('{{ $course->slug }}')">View
                                Course</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="mt-20 flex justify-center items-center gap-2">

                {{-- Previous --}}
                <button
                    wire:click="previousPage"
                    @disabled($this->courses->onFirstPage())
                    class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container text-outline hover:text-primary disabled:opacity-50">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>

                {{-- Pages --}}
                @foreach ($this->courses->getUrlRange(1, $this->courses->lastPage()) as $page => $url)

                    @if ($page == $this->courses->currentPage())
                        <button
                            class="w-12 h-12 flex items-center justify-center rounded-xl bg-primary text-on-primary font-bold">
                            {{ $page }}
                        </button>
                    @else
                        <button
                            wire:click="gotoPage({{ $page }})"
                            class="w-12 h-12 flex items-center justify-center rounded-xl hover:bg-surface-container font-bold transition-colors">
                            {{ $page }}
                        </button>
                    @endif

                @endforeach

                {{-- Next --}}
                <button
                    wire:click="nextPage"
                    @disabled(!$this->courses->hasMorePages())
                    class="w-12 h-12 flex items-center justify-center rounded-xl bg-surface-container text-outline hover:text-primary disabled:opacity-50">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>

            </div>
        </main>
    </x-ui.main>
</div>
