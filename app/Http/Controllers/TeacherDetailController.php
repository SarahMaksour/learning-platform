<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

use function Laravel\Prompts\text;

class TeacherDetailController extends Controller
{
public function storeDetails(Request $request){
   $user= Auth::user();

   if($user->role !== 'teacher')
    return response()->json(['error' => 'Unauthorized'], 403);

  $request->validate(
    [
        'image' => 'nullable|string' ,
        'specialization' => 'required|string' ,
        'bio' => 'nullable|string' 
     
    ]
    );
    UserDetail::updateOrCreate(
    ['user_id' => $user->id],
    [
        'user_id' => $user->id,
        'image' => $request->image,
       'specialization' =>$request->specialization,
        'bio' =>$request->bio,
    ]

    );
    return response()->json(['message' => 'Profile details saved successfully',],201);

    
}
}
