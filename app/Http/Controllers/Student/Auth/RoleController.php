<?php

namespace App\Http\Controllers\Student\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function setRole(Request $request){

    $request->validate([

  'role' => 'required|in:teacher,student',
   
]);
$user = $request->user(); 
$user->role = $request->role;
$user->save();
 $token = $user->createToken('Auth_token')->plainTextToken;

return response()->json([
    'message' => 'Role set successfully',
    'role' => $user->role,
       'token' => $token

],201);

}  
}
