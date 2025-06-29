<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_with_valid_data()
    {
        // Given that a logged-in user is on the task creation page
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'Testing',
            'section' => 'Innovation',
            'complete' => 0
        ];

        // When the user submits the form with valid data
        $response = $this->post('/tasks', $taskData);

        // Then the task is created and the user is redirected to the home page
        $this->assertDatabaseHas('tasks', [
            'name' => 'Testing',
            'section' => 'Innovation',
            'complete' => 0,
            'user_id' => $user->id
        ]);

        $response->assertRedirect(route('tasks.index'));

        $indexResponse = $this->get(route('tasks.index'));
        $indexResponse->assertSee('Testing');
    }

    public function test_task_creation_requires_name()
    {
        // Given that a logged-in user leaves the name field blank
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => '',
            'section' => 'Innovation',
            'complete' => 0
        ];

        // When the user submits the form
        $response = $this->post('/tasks', $taskData);

        // Then they receive an error explaining that the name field is required
        $response->assertSessionHasErrors(['name']);
    }

    public function test_task_creation_requires_section()
    {
        // Given that a logged-in user leaves the section field blank
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'Testing',
            'section' => '',
            'complete' => 0
        ];

        // When the user submits the form
        $response = $this->post('/tasks', $taskData);

        // Then they receive an error explaining that the section field is required
        $response->assertSessionHasErrors(['section']);
    }

    public function test_user_can_only_see_their_own_tasks()
    {
        // Given that there are two users with their own tasks
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task1 = Task::factory()->create([
            'user_id' => $user1->id,
            'name' => 'User 1 Task'
        ]);

        $task2 = Task::factory()->create([
            'user_id' => $user2->id,
            'name' => 'User 2 Task'
        ]);

        // When user 1 views their home page
        $response = $this->actingAs($user1)->get(route('tasks.index'));

        // Then they only see their own tasks
        $response->assertSee('User 1 Task');
        $response->assertDontSee('User 2 Task');
    }

    public function test_task_name_max_length()
    {
        // Given that a user enters a task name longer than 255 characters
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => str_repeat('a', 256),
            'section' => 'Innovation',
        ];

        // When the user submits the form
        $response = $this->post('/tasks', $taskData);

        // Then they will receive an error explaining that the task name is too long
        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tasks', [
            'section' => 'Innovation',
            'user_id' => $user->id,
        ]);
    }

    public function test_task_name_min_length()
    {
        // Given that a user enters a task name shorter than 3 characters
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'name' => 'a',
            'section' => 'Innovation',
        ];

        // When the user submits the form
        $response = $this->post('/tasks', $taskData);

        // Then they will receive an error explaining that the task name is too short
        $response->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('tasks', [
            'section' => 'Innovation',
            'user_id' => $user->id,
        ]);
    }

    public function test_multiple_tasks_can_be_created_in_same_section()
    {
        // Given that a user is logged in
        $user = User::factory()->create();
        $this->actingAs($user);

        // When they create multiple tasks within a single section
        $this->post('/tasks', [
            'name' => 'Security',
            'section' => 'Innovation',
        ]);

        $this->post('/tasks', [
            'name' => 'Testing',
            'section' => 'Innovation',
        ]);

        // Then both tasks are created
        $this->assertDatabaseHas('tasks', [
            'name' => 'Security',
            'section' => 'Innovation',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Testing',
            'section' => 'Innovation',
            'user_id' => $user->id,
        ]);

        $this->assertEquals(2, Task::where('user_id', $user->id)->count());
    }
}
