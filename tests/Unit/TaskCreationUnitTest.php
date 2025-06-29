<?php

namespace Tests\Unit;

use App\Http\Controllers\TaskController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class TaskCreationUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_controller_store_method_saves_tasks_with_correct_data()
    {
        // User submits task with valid data
        $user = User::factory()->create();
        $this->actingAs($user);

        $controller = new TaskController();
        $request = Request::create('/tasks', 'POST', [
            'section' => 'Innovation',
            'name' => 'Test Task',
            'complete' => 1
        ]);

        // When the store method is called
        $controller->store($request);

        // Then the task is saved with correct data
        $this->assertDatabaseHas('tasks', [
            'section' => 'Innovation',
            'name' => 'Test Task',
            'complete' => 1,
            'user_id' => $user->id
        ]);

        $task = Task::where('name', 'Test Task')->first();
        $this->assertEquals('Test Task', $task->name);
        $this->assertEquals('Innovation', $task->section);
        $this->assertEquals(1, $task->complete);
        $this->assertEquals($user->id, $task->user_id);
    }

    public function test_blank_task_name_gets_validation_error()
    {
        // Given there is a task without a name
        $taskData = [
            'name' => '',
            'section' => 'Innovation',
            'complete' => 0
        ];

        // When it attempts to validate
        $validator = validator($taskData, [
            'name' => 'required|string|min:3|max:255',
            'section' => 'required|string',
            'complete' => 'nullable|boolean'
        ]);

        // Then the validation for the name field should fail
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertContains('The name field is required.', $validator->errors()->get('name'));
    }

    public function test_blank_section_gets_validation_error()
    {
        // Given that there is a task without a section
        $taskData = [
            'name' => 'Test Task',
            'section' => '',
            'complete' => 0
        ];

        // When it attempts to validate
        $validator = validator($taskData, [
            'name' => 'required|string|min:3|max:255',
            'section' => 'required|string',
            'complete' => 'nullable|boolean'
        ]);

        // Then the validation for the section field should fail
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('section', $validator->errors()->toArray());
    }

    public function test_task_name_max_length_validation_works()
    {
        // Given that the task name is too long
        $taskData = [
            'name' => str_repeat('a', 256),
            'section' => 'Innovation',
            'complete' => 0
        ];

        // When it attempts to validate
        $validator = validator($taskData, [
            'name' => 'required|string|min:3|max:255',
            'section' => 'required|string',
            'complete' => 'nullable|boolean'
        ]);

        // Then the validation should fail
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_task_name_min_length_validation_works()
    {
        // Given that the task name is too long
        $taskData = [
            'name' => 'aa',
            'section' => 'Innovation',
            'complete' => 0
        ];

        // When it attempts to validate
        $validator = validator($taskData, [
            'name' => 'required|string|min:3|max:255',
            'section' => 'required|string',
            'complete' => 'nullable|boolean'
        ]);

        // Then the validation should fail
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_valid_task_data_passes_validation()
    {
        // Given that all task fields are valid
        $taskData = [
            'name' => 'Test Task',
            'section' => 'Innovation',
            'complete' => 1
        ];

        // When it attempts to validate
        $validator = validator($taskData, [
            'name' => 'required|string|min:3|max:255',
            'section' => 'required|string',
            'complete' => 'nullable|boolean'
        ]);

        // Then the validation should succeed
        $this->assertFalse($validator->fails());
        $this->assertEmpty($validator->errors()->toArray());
    }

    public function test_task_belongs_to_user_relationship_works()
    {
        // Given that there is a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // When checking the relationship
        $taskUser = $task->user;

        // Then the relationship should work correctly
        $this->assertInstanceOf(User::class, $taskUser);
        $this->assertEquals($user->id, $taskUser->id);
        $this->assertEquals($user->name, $taskUser->name);
    }

    public function test_user_has_many_tasks_relationship_works()
    {
        // Given that there is a user with multiple tasks
        $user = User::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user->id]);
        $task2 = Task::factory()->create(['user_id' => $user->id]);
        $task3 = Task::factory()->create(['user_id' => $user->id]);

        // When checking the relationship
        $userTasks = $user->tasks;

        // Then the relationship should work correctly
        $this->assertCount(3, $userTasks);
        $this->assertTrue($userTasks->contains($task1));
        $this->assertTrue($userTasks->contains($task2));
        $this->assertTrue($userTasks->contains($task3));
    }
}
