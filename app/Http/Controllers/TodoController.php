<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Http\Controllers\Controller;


class TodoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/todos",
     *     tags={"Todo"},
     *     summary="List all todos",
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum={"completed", "in progress", "not started"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum={"desc", "asc"})
     *     ),
     *     @OA\Response(response="200", description="OK", @OA\JsonContent(),),
     *     @OA\Response(response="404", description="Page Not Found"),
     *     @OA\Response(response="422", description="Unprocessable Entity"),
     *     @OA\Response(response="500", description="Internal server error")
     * )
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('details', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->get('order', 'asc'));
        }

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/todos",
     *     tags={"Todo"},
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"title"},
     *                  @OA\Property(property="title",
     *                  type="string", description="Title of todo"),
     *                  @OA\Property(property="status", type="string",
     *                          description="Status of todo", enum={"completed", "in progress", "not started"}),
     *                  @OA\Property(property="details", type="string", description="Details of todo"),
     *              ),
     *         )
     *     ),
     *     @OA\Response(response="201", description="OK", @OA\JsonContent(),),
     *     @OA\Response(response="404", description="Page Not Found", @OA\JsonContent(),),
     *     @OA\Response(response="422", description="Unprocessable Entity", @OA\JsonContent(),),
     *     @OA\Response(response="500", description="Internal server error", @OA\JsonContent(),)
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'in:completed,in progress,not started'
        ]);

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/todos/{todo}",
     *     tags={"Todo"},
     *     summary="Update an existing todo",
     *     @OA\Parameter(
     *         name="todo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of todo"
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent(),
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="title",
     *                  type="string", description="title of todo", example=""),
     *                  @OA\Property(property="status", type="string",
     *                          description="Status of todo", enum={"completed", "in progress", "not started"}, example=""),
     *                  @OA\Property(property="details", type="string", description="Details of todo", example=""),
     *           ),
     *       ),
     *     ),
     *     @OA\Response(response="201", description="OK", @OA\JsonContent(),)
     * )
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'details' => 'nullable|string',
            'status' => 'in:completed,in progress,not started'
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    /**
     * @OA\Delete(
     *     path="/api/todos/{todo}",
     *     tags={"Todo"},
     *     summary="Delete a todo",
     *     @OA\Parameter(
     *         name="todo",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *         description="ID of the todo"
     *     ),
     *     @OA\Response(response="200", description="OK", @OA\JsonContent(),)
     * )
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(null, 204);
    }
}
