<?php

namespace App\Http\Controllers;

use App\Helper\QuotesHelper;
use App\Http\Requests\UserFirstLoginRequest;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Imports\UsersImportSync;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ApiResponder;
    /**
     * Get all students data
     */
    #[Group('Dashboard')]
    public function getStudents()
    {
        $role = Auth::user()->role;
        $role = $role == 'teacher' ? 'mentor' : 'counselor';
        $students = User::with(['room', 'mentor', 'lastmoodres'])->whereRole('student')->where($role . '_id', Auth::id())->get();
        return $this->success(UserResource::collection($students));
    }

    /**
     * Get all users data at one school
     */
    #[Group('User')]
    public function getUsers()
    {
        $users = User::whereSchoolId(Auth::user()->school_id)->get();
        return $this->success(UserResource::collection($users));
    }
    /**
     * Get user data by username
     */
    #[Group('User')]
    public function getUserDetail(string $username)
    {
        $user = User::with(['room', 'mentor'])->where('username', $username)->first();
        return $this->success(new UserResource($user));
    }
    /**
     * Get template for bulk create
     */
    #[Group('User')]
    public function getTemplate()
    {
        return $this->success(["link" => env('APP_URL') . "/templates/Template%20Siswa.xlsx"]);
    }
    /**
     * Update user profile on first login
     */
    #[Group('User')]
    public function profile(UserFirstLoginRequest $request)
    {
        Auth::user()->update($request->all());
        return $this->success(new UserResource(Auth::user()), 'Success update user profile');
    }

    /**
     * Update user profile
     */
    #[Group('User')]
    public function editProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            "username" => "string|unique:users,username,{$user->id}",
            "phone" => "string|unique:users,phone,{$user->id}"
        ]);
        Auth::user()->update($request->all());
        return $this->success(new UserResource(Auth::user()), 'Success update user profile');
    }

    /**
     * Create bulk user with excel
     */
    #[Group('User')]
    public function bulkCreate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);
        $file = $request->file('file');
        if ($file->getSize() > 30 * 1024) {
            Excel::import(new UsersImport(Auth::user()->school_id), $file);
            return $this->success(null, "Your data will insert async");
        } else {
            $import = new UsersImportSync(Auth::user()->school_id);
            Excel::import($import, $file);
            $data = $import->getInsertedUsers();
            $count = $data->count();
            return $this->success(compact(['data', 'count']));
        }
    }

    /**
     * Get quotes of the day by mood status
     * @param string $type secure or insecure
     */
    #[Group('Quotes')]
    public function quotesOfTheDay(string $type)
    {
        $quotes = QuotesHelper::random($type);
        return $this->success(compact('quotes'));
    }

    /**
     * Get user count by type
     */
    #[Group('Dashboard')]
    public function getUserCount(string $type)
    {
        Gate::authorize('dashboard-data');
        $role = Auth::user()->role;
        if ($type == 'student') {
            if ($role == 'headteacher') {
                # code...
                $count = User::whereRole('student')->whereSchoolId(Auth::user()->school_id)->count();
            } else {
                $role = $role == 'teacher' ? 'mentor' : 'counselor';
                $count = User::whereRole('student')->where($role . '_id', Auth::id())->count();
            }
        } else {
            $count = User::whereRole($type)->whereSchoolId(Auth::user()->school_id)->count();
        }
        return $this->success(["count" => (int) $count]);
    }
}
