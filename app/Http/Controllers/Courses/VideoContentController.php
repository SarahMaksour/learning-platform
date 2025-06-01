<?php

namespace App\Http\Controllers\Courses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\VideoContentService;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\videoContentResource;

class VideoContentController extends Controller
{
    protected $videoService;
    public function __construct(VideoContentService $videoService){
        $this->videoService=$videoService;
    }
public function show($id)
    {
        $content = $this->videoService->getVideoContent($id);
        return new videoContentResource($content);
    }

    public function storeComment(StoreCommentRequest $request, $id)
    {
        $this->videoService->addComment($id, auth()->id(), $request->message);
        return response()->json(['message' => 'add comment successfully'], 201);
    }

    public function storeReply(StoreCommentRequest $request, $commentId)
    {
        $this->videoService->addReply($commentId, auth()->id(), $request->message);
        return response()->json(['message' => 'add reply successfully'], 201);
    }
}
