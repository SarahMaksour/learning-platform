<?php

namespace App\Services;

use App\Models\Course;

class SearchService
{
 public function getsearch(string $query)
 {

        return Course::where('title', 'like', "%$query%")
                     ->orWhere('description', 'like', "%$query%")
                     ->get();
    }



}