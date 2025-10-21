<?php

namespace App\Services\dashboard;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class UserService
{
    /**
     * For create a new user
     * 
     * @param array $data To Create the user
     */
    public function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole($data['role']);

        return $user;
    }

    /**
     * For update a user
     * 
     * @param array $data To Update the user
     * @param mixed $user To know which user will be updated which it may a model object or id
     */
    public function update(array $data, $user)
    {
        if (is_string($user))
            $user = User::findOrFail($user);

        $user->update($data);

        return $user;
    }

    /**
     *  Delete the specified user
     * 
     *  @param var $user The user to delete which it may a model object or id
     *  @return bool|null True if the user was deleted, false otherwise
     */
    public function delete($user)
    {

        return $user->delete();
    }

    /**
     * Get paginated users with applied filters
     *
     * This function retrieves a paginated list of users from the User model
     * applying filters based on the request data parameter
     * The filters include:
     * - Full Name
     * - Email
     * - Role
     *
     * The function returns the filtered results paginated with 10 items per page
     *
     * @param array $data The incoming data containing filter parameters
     * @return LengthAwarePaginator The paginated list of filtered users
     */
    public function getAllUsersAfterFiltering(array $data)
    {
        // To define how many rows per page
        $entries_number = $data['entries_number'] ?? 10;

        $q = User::query();

        $q->when(isset($data['name']), function ($query) use ($data) {
            $query->SearchFullName($data['name']);
        });

        $q->when(isset($data['email']), function ($query) use ($data) {
            $query->SearchEmail($data['email']);
        });

        $q->when(isset($data['role']) && $data['role'] !== 'All', function ($query) use ($data) {
            $query->role($data['role']);
        });

        return $q->paginate($entries_number)->appends(request()->except('page'));
    }


    /**
     * To get all trashed users with filtering and paginated data
     * 
     * @param Request $request To Apply the filtering if it's found
     * @param int $entries_number To know how many records per page
     * @return LengthAwarePaginator
     */
    public function getAllTrashedUsersAfterFiltering(Request $request, $entries_number)
    {
        $q = User::onlyTrashed();

        $q->when(
            $request->filled('name'),
            function ($query) use ($request) {
                $query->SearchFullName($request->name);
            }
        );

        $q->when(
            $request->filled('role') && $request->role !== 'All',
            function ($query) use ($request) {
                $query->role($request->role);
            }
        );

        return $q->paginate($entries_number)->appends(request()->except('page'));
    }

    /**
     * Get paginated Trainers with applied filters
     *
     * This function retrieves a paginated list of Trainers from the User model
     * applying filters based on the request data parameter
     * The filters include:
     * - Full Name
     * - Email
     *
     * The function returns the filtered results paginated with 10 items per page
     *
     * @param array $data The incoming data containing filter parameters
     * @return LengthAwarePaginator The paginated list of filtered users
     */
    public function getAllTrainersAfterFiltering(array $data)
    {
        // To define how many rows per page
        $entries_number = $data['entries_number'] ?? 10;

        $q = User::whereHas('roles', function ($query) {
            $query->where('name', 'trainer');
        });

        $q->when(isset($data['name']), function ($query) use ($data) {
            $query->SearchFullName($data['name']);
        });

        $q->when(isset($data['email']), function ($query) use ($data) {
            $query->SearchEmail($data['email']);
        });

        $q->when(isset($data['role']) && $data['role'] !== 'All', function ($query) use ($data) {
            $query->role($data['role']);
        });

        return $q->paginate($entries_number)->appends(request()->except('page'));
    }


    /**
     * For delete a user permenently
     * 
     * @param string $id Search the user by id
     */
    public function forceDelete(string $id)
    {
        $user = User::withTrashed()->find($id);
        return $user->forceDelete();
    }

    /**
     * To restore a user
     * 
     * @param string $id Search the user by id
     */
    public function restore(string $id)
    {
        return User::withTrashed()->find($id)->restore();
    }

    /**
     * Show User
     */
    public function showApi(string $id)
    {
        return User::findOrFail($id);
    }
}
