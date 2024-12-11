<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\NotesCreateRequest;
use App\Http\Requests\Notes\NotesUpdateRequest;
use App\Http\Resources\Notes\NotesResource;
use App\Http\Resources\Notes\NotesResourceById;
use App\Http\Resources\Notes\NotesResourceCollection;
use App\Models\Notes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'create', 'update', 'delete']]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $notes = Notes::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        $notesResponse = NotesResource::collection($notes);
        return (new NotesResourceCollection($notesResponse))->response()->setStatusCode(201);
    }
    public function show($id): JsonResponse
    {
        $notes = Notes::with('user')->find($id);
        if (!$notes) {
            return response()->json([
                "message" => "Notes dengan ID $id tidak ditemukan"
            ], 404);
        }
        return (new NotesResourceById($notes))->response()->setStatusCode(201);
    }

    public function store(NotesCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $notes = new Notes($data);
        $notes->save();
        return (new NotesResource($notes))->response()->setStatusCode(201);
    }

    public function update(string $id, NotesUpdateRequest $request): JsonResponse
    {
        $notes = Notes::find($id);
        if (!$notes) {
            return response()->json([
                "message" => "Notes dengan ID $id tidak ditemukan"
            ], 404);
        }
        $data = $request->validated();
        $notes->update($data);
        return (new NotesResource($notes))->response()->setStatusCode(201);
    }

    public function destroy(string $id)
    {
        $notes = Notes::find($id);
        if (!$notes) {
            return response()->json([
                'message' => 'Notes dengan ID '. $id.'tidak ditemukan',
            ], 404);
        }
        $notes->delete();
        return response()->json([
            'message' => 'Notes berhasil dihapus',
        ], 200);
    }
}
