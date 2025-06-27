<?php
namespace App\Services;

use App\Models\Discussion;
use App\Models\CourseContent;

class VideoContentService{
    public function getVideoContent($id){
        return CourseContent::with('contentable',
        'discussions.user',
        'discussions.replies.user')
        ->findOrFail($id);
    }
    public function addComment($contentId,$userId,$comment){
        $content=CourseContent::findOrFail($contentId);
        return $content->discussions()->create( [
'user_id'=>$userId,
'message'=>$comment

        ]);
    }
    public function addReply($commentId, $userId, $message)
    {
        $parent = Discussion::findOrFail($commentId);
        return Discussion::create([
            'user_id' => $userId,
            'content_id' => $parent->content_id,
            'parent_id' => $parent->id,
            'message' => $message
        ]);
    }
}