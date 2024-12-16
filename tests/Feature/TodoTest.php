<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Todo;
use PHPUnit\Framework\Attributes\Test;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_all_todos_with_filtering_and_sorting()
    {
        Todo::factory()->create(['title' => 'First todo', 'status' => 'completed']);
        Todo::factory()->create(['title' => 'Second todo', 'status' => 'in progress']);
        Todo::factory()->create(['title' => 'Third todo', 'status' => 'not started']);

        $response = $this->getJson(route('todos.index', [
            'search' => 'todo',
            'status' => 'completed',
            'sort' => 'title',
            'order' => 'asc'
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'First todo']);
    }

    #[Test]
    public function it_can_create_a_todo()
    {
        $data = [
            'title' => 'Test todo',
            'details' => 'Some dummy text',
            'status' => 'not started'
        ];

        $response = $this->postJson(route('todos.store'), $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test todo']);
    }

    #[Test]
    public function it_can_update_a_todo()
    {
        $todo = Todo::factory()->create([
            'status' => 'not started'
        ]);

        $data = [
            'status' => 'completed'
        ];

        $response = $this->putJson(route('todos.update', $todo), $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'completed']);
    }

    #[Test]
    public function it_can_delete_a_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson(route('todos.destroy', $todo));

        $response->assertStatus(204);
    }
}
