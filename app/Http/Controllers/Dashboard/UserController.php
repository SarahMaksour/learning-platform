<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\dashboard\UserService;
use App\Http\Requests\dashboard\UserFilterRequest;

class UserController extends Controller
{
    protected $userService;

    /**
     * UserController constructor
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        // Apply the auth middleware to ensure the user is authenticated
        $this->middleware(['auth']);

        // Inject the UserService to handle user-related logic
        $this->userService = $userService;
    }

   /* public function index()
    {
        $users = User::all();
        return view('new-dashboard.users.list_users', [
            'users' => $users,
        ]);
    }*/
           public function index(UserFilterRequest $request)
    {
        $validated = $request->validated();
        $users = $this->userService->getAllUsersAfterFiltering($validated);

        return view('new-dashboard.users.list_users', [
            'users' => $users,
        ]);
    }
    public function show(User $user)
{
    // بيانات أساسية
    $userDetails = $user->details; // علاقة user_details
    $role = $user->role;

    // الاشتراكات / الكورسات المشترك فيها
    $subscriptions = $user->enrollments()->with('course')->get();

    // تقييمات المستخدم للكورسات
    $serviceRatings = $user->reviews()->with('course')->get();

    // تقييمات الطلاب للمدرب (لو teacher)
    $userRatings = [];
    if ($role == 'teacher') {
        $userRatings = $user->courses()->with(['reviews.user'])->get()->pluck('reviews')->flatten();
    }

    // كورسات المستخدم
    $myCourses = [];
    if ($role == 'student') {
        $myCourses = $user->enrollments()->with('course')->get();
    } else {
        $myCourses = $user->courses()->with('enrollments')->get();
    }

    // شهادات المستخدم
    $certificates = $user->certificates()->with('course')->get();

    return view('new-dashboard.users.view', compact(
        'user',
        'userDetails',
        'subscriptions',
        'serviceRatings',
        'userRatings',
        'myCourses',
        'certificates'
    ));
}


    public function trashedUsers(Request $request)
    {
        $entries_number = $request->input('entries_number', 10);
        $users = $this->userService->getAllTrashedUsersAfterFiltering($request, $entries_number);

        return view('new-dashboard.users.trashed_users', [
            'users' => $users,
            'entries_number' => $entries_number,
        ]);
    }
       public function forceDelete(string $id)
    {
        // get the url for the previous page to redirect the user
        $redirect = request()->query('redirect', route('users.index'));

        $user = $this->userService->forceDelete($id);

        // using the method from FlashMessageHelper to alert the user about success or faild
 
        flashMessage($user, 'User Deleted successfully.', 'Failed to Delete user.');

        return redirect($redirect);
    }
       public function restore(string $id)
    {
        $user = $this->userService->restore($id);

        // using the method from FlashMessageHelper to alert the user about success or faild
      //  flashMessage($user, 'User Restored successfully.', 'Failed to Restore user.');

           function flashMessage($condition, $successMessage, $errorMessage) {
            if ($condition) {
                session()->flash('success', $successMessage);
            } else {
                session()->flash('error', $errorMessage);
            }
        }
           flashMessage($user, 'User Restored successfully.', 'Failed to Restore user.');

        return redirect()->route('users.trashed');
    }
}
