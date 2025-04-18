<?php
namespace App\Services;

use App\Models\Review;

class CourseReview{
    public function getCourseReview($course_id){
       
     $totalReviews=Review::where('course_id',$course_id)->count();
     $averageRating=Review::where('course_id',$course_id)->avg('rating');  
     $ratingcount=Review::where('course_id',$course_id)
      ->selectRaw('rating, COUNT(*) as count')
      ->groupBy('rating')
      ->pluck('count','rating');
      $ratings=[];
      for($i=5;$i>=1;$i--){
      $count=$ratingcount[$i] ?? 0 ;
      $percentage= $totalReviews > 0 ? round(($count/$totalReviews)*100) : 0 ;
      $ratings[$i]=$percentage . '%';
      }
      $comments= Review::with('user')
      ->where('course_id', $course_id)
      ->whereNotNull('comment')
      ->get()
     ->map(function($review){
        return[
        'comment'=>$review->comment ,
        'username'=>$review->user->name ,
        ];
         });

            return [
            'averageRating' => round($averageRating, 1),
            'totalReviews' => $totalReviews,
            'ratings' => $ratings,
            'comments'=>$comments,
        ];

    }
}