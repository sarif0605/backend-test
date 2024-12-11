<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListItem\ListItemCreateRequest;
use App\Http\Requests\ListItem\ListItemUpdateRequest;
use App\Http\Resources\ListItem\ListItemResource;
use App\Http\Resources\ListItem\ListItemResourceById;
use App\Http\Resources\ListItem\ListItemResourceCollection;
use App\Models\ListItems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show', 'create', 'update', 'delete']);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        $listItems = ListItems::with('children', 'parent', 'notes')->paginate($perPage);
        return new ListItemResourceCollection($listItems);
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

    public function store(ListItemCreateRequest $request)
    {
        $data = $request->validated();

        // Menyimpan item utama
        $listItem = new ListItems($data);
        $listItem->save();
        if (isset($data['sub_items'])) {
            $this->storeSubItems($listItem, $data['sub_items']);
        }

        return response()->json([
            'data' => new ListItemResource($listItem),
            'status' => 'success',
            'message' => 'Item list created successfully',
        ], 201);
    }

    protected function storeSubItems(ListItems $parentItem, array $subItems)
    {
        foreach ($subItems as $subItemData) {
            $subItemData['notes_id'] = $parentItem->notes_id;
            $subItemData['parent_id'] = $parentItem->id;
            $subItem = new ListItems($subItemData);
            $subItem->save();
            if (isset($subItemData['sub_items'])) {
                $this->storeSubItems($subItem, $subItemData['sub_items']);
            }
        }
    }

    public function update(ListItemUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $listItem = ListItems::findOrFail($id);
        $listItem->update($data);
        return response()->json([
            'data' => new ListItemResource($listItem),
            'status' => 'success',
            'message' => 'Item list updated successfully',
        ]);
    }

    public function updateSubItems(ListItemUpdateRequest $request, $id)
    {
        $listItem = ListItems::findOrFail($id);
        $data = $request->validated();
        if (isset($data['sub_items'])) {
            $this->updateSubItemsRecursive($listItem, $data['sub_items']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sub-items updated successfully',
        ]);
    }

    protected function updateSubItemsRecursive(ListItems $parentItem, array $subItems)
    {
        foreach ($subItems as $subItemData) {
            $subItemData['parent_id'] = $parentItem->id;
            $subItem = ListItems::updateOrCreate(
                ['id' => $subItemData['id'] ?? null],
                $subItemData
            );
            if (isset($subItemData['sub_items'])) {
                $this->updateSubItemsRecursive($subItem, $subItemData['sub_items']);
            }
        }
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
