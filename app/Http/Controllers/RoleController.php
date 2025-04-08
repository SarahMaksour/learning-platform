<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function setRole(Request $request){

    $request->validate([

  'role' => 'required|in:teacher,student',
   
]);
$user = $request->user(); 
$user->role = $request->role;
$user->save();

return response()->json([
    'message' => 'Role set successfully',
    'role' => $user->role,
],200);

}
}
