<?php

use App\Filament\Resources\Courses\Pages\CreateCourse;
use App\Filament\Resources\Courses\Pages\EditCourse;
use App\Filament\Resources\Courses\Pages\ListCourses;
use App\Filament\Resources\Courses\Pages\ViewCourse;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Level;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create(['type' => 'admin']);
    actingAs($user);
});

it('can render the course list page', function () {
    Course::factory()->count(10)->create();

    Livewire::test(ListCourses::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Course::limit(10)->get());
});

it('can load the create page', function () {
    Livewire::test(CreateCourse::class)
        ->assertOk();
});

it('can create a course with image', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->image('course.png');

    $category = Category::factory()->create();
    $level = Level::factory()->create();
    $instructor = Instructor::factory()->create();
    $newCourseData = Course::factory()->make([
        'category_id' => $category->id,
        'level_id' => $level->id,
        'instructor_id' => $instructor->id,
    ]);

    $title = $newCourseData->title;
    $slug = Str::slug($title);

    Livewire::test(CreateCourse::class)
        ->fillForm([
            'title' => $title,
            'slug' => $slug,
            'description' => $newCourseData->description,
            'target_audience' => $newCourseData->target_audience,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'instructor_id' => $instructor->id,
            'is_featured' => true,
            'image' => [$file],
            'sections' => [['name' => 'Section 1', 'order' => 1]],
        ])
        ->set('data.slug', $slug)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('courses', ['title' => $title]);
});

it('can load the edit page', function () {
    $course = Course::factory()->create();

    Livewire::test(EditCourse::class, [
        'record' => $course->getRouteKey(),
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'title' => $course->title,
            'description' => $course->description,
        ]);
});

it('can edit and update a course', function () {
    $course = Course::factory()->create();
    $courseData = Course::factory()->make();

    Livewire::test(EditCourse::class, [
        'record' => $course->getRouteKey(),
    ])
        ->fillForm([
            'title' => $courseData->title,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($course->refresh()->title)->toBe($courseData->title);
});

it('can delete a course', function () {
    $course = Course::factory()->create();

    Livewire::test(EditCourse::class, [
        'record' => $course->getRouteKey(),
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    $this->assertSoftDeleted('courses', [
        'id' => $course->id,
    ]);
});

it('validates course form data', function (array $data, array $errors) {
    $course = Course::factory()->create();
    $newCourseData = Course::factory()->make();

    Livewire::test(EditCourse::class, [
        'record' => $course->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newCourseData->title,
            'description' => $newCourseData->description,
            'target_audience' => $newCourseData->target_audience,
            'category_id' => $course->category_id,
            'level_id' => $course->level_id,
            'instructor_id' => $course->instructor_id,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`title` is max 255 characters' => [['title' => Str::random(256)], ['title' => 'max']],
    '`description` is required' => [['description' => null], ['description' => 'required']],
    '`description` is max 2000 characters' => [['description' => Str::random(2001)], ['description' => 'max']],
]);

it('can load the view page', function () {
    $course = Course::factory()->create();

    Livewire::test(ViewCourse::class, [
        'record' => $course->getRouteKey(),
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'title' => $course->title,
            'description' => $course->description,
        ]);
});
