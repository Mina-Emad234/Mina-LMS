<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can view courses page as authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('courses.index'));
    $response->assertOk();

    $response->assertSee('Courses');

    $this->assertAuthenticatedAs($user);
});

it('can enroll in a course as authenticated user', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create([
        'category_id' => Category::factory()->create()->id,
        'level_id' => Level::factory()->create()->id,
        'instructor_id' => Instructor::factory()->create()->id,
    ]);
    Livewire::actingAs($user)
        ->test('pages::courses.show', ['course' => $course])
        ->call('enrollCourse')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('enrolements', [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});
