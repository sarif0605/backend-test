<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListItem\ListItemCreateRequest;
use App\Http\Requests\ListItem\ListItemUpdateRequest;
use App\Http\Resources\ListItem\ListItemResource;
use App\Http\Resources\ListItem\ListItemResourceById;
use App\Models\ListItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;

class ListItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'create', 'update', 'delete', 'toggleStatus']]);
    }

    public function index(Request $request)
    {
        $query = ListItems::query();
        if ($request->has('notes_id')) {
            $query->where('notes_id', $request->notes_id);
        }
        $listItems = $query->with('children')->get();
        return (new ListItemResource($listItems))->response()->setStatusCode(201);
    }
    public function show($id): JsonResponse
    {
        $listItem = ListItems::with('children')->find($id);
        if (!$listItem) {
            return response()->json([
                "message" => "List Item dengan ID $id tidak ditemukan"
            ], 404);
        }
        return (new ListItemResourceById($listItem))->response()->setStatusCode(201);
    }

    public function store(ListItemCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $list = new ListItems($data);
        $list->save();
        return (new ListItemResource($list))->response()->setStatusCode(201);
    }

    public function update(string $id, ListItemUpdateRequest $request): JsonResponse
    {
        $listItem = ListItems::find($id);
        if (!$listItem) {
            return response()->json([
                "message" => "List Item dengan ID $id tidak ditemukan"
            ], 404);
        }
        $data = $request->validated();
        $listItem->update($data);
        return (new ListItemResource($listItem))->response()->setStatusCode(201);
    }

    public function destroy(string $id)
    {
        $list = ListItems::find($id);
        if (!$list) {
            return response()->json([
                'message' => 'List Item dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        $list->delete();
        return response()->json([
            'message' => 'List Item berhasil dihapus',
        ], 200);
    }

    public function toggleStatus($id)
    {
        $listItem = ListItems::findOrFail($id);
        $listItem->is_completed = !$listItem->is_completed;
        $listItem->save();
        return response()->json($listItem, Response::HTTP_OK);
    }
}
