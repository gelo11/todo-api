<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase; // Очищает БД после каждого теста

    public function test_can_list_all_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data')
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'title',
                             'description',
                             'status',
                             'created_at',
                             'updated_at'
                         ]
                     ]
                 ]);
    }

    public function test_can_create_task_with_valid_data()
    {
        $data = [
            'title' => 'Купить молоко',
            'description' => 'Купить 2 литра молока',
            'status' => 1,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'title',
                         'description',
                         'status',
                         'created_at',
                         'updated_at'
                     ]
                 ])
                 ->assertJson([
                     'data' => [
                         'title' => 'Купить молоко',
                         'description' => 'Купить 2 литра молока',
                         'status' => 1,
                     ]
                 ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Купить молоко',
            'description' => 'Купить 2 литра молока',
            'status' => 1,
        ]);
    }

    public function test_cannot_create_task_with_empty_title()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '',
            'description' => 'test',
            'status' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_cannot_create_task_with_html_in_title()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '<script>alert(1)</script>',
            'description' => 'test',
            'status' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_description_is_automatically_cleaned_of_html_tags()
    {
        $data = [
            'title' => 'Test',
            'description' => '<p>Описание с тегами</p><script>evil()</script>',
            'status' => 2,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test',
            'description' => 'Описание с тегамиevil()', // HTML удалён!
            'status' => 2,
        ]);
    }

    public function test_cannot_create_task_with_invalid_status()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test',
            'description' => 'test',
            'status' => 999,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }

    public function test_can_get_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'title',
                         'description',
                         'status',
                         'created_at',
                         'updated_at'
                     ]
                 ])
                 ->assertJson([
                     'data' => [
                         'id' => $task->id,
                         'title' => $task->title,
                         'status' => $task->status,
                     ]
                 ]);
    }

    public function test_returns_404_for_nonexistent_task()
    {
        $response = $this->getJson('/api/tasks/999999');

        $response->assertStatus(404);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create([
            'title' => 'Старое название',
            'description' => 'Старое описание',
            'status' => 1,
        ]);

        $updatedData = [
            'title' => 'Новое название',
            'description' => 'Новое описание без тегов',
            'status' => 3,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'title' => 'Новое название',
                         'description' => 'Новое описание без тегов',
                         'status' => 3,
                     ]
                 ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Новое название',
            'description' => 'Новое описание без тегов',
            'status' => 3,
        ]);
    }

    public function test_cannot_update_task_with_empty_title()
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => '',
            'description' => 'test',
            'status' => 1,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_delete_nonexistent_task_returns_404()
    {
        $response = $this->deleteJson('/api/tasks/999999');

        $response->assertStatus(404);
    }
}
