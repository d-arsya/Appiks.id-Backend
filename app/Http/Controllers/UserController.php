<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserFirstLoginRequest;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Imports\UsersImportSync;
use App\Models\Room;
use App\Models\User;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use ApiResponder;
    /**
     * Get all students data
     */
    #[Group('User')]
    public function getStudents()
    {
        $role = Auth::user()->role;
        $role = $role == 'teacher' ? 'mentor' : 'counselor';
        $students = User::with(['room', 'mentor', 'lastmoodres'])->whereRole('student')->where($role . '_id', Auth::id())->get();
        return $this->success(UserResource::collection($students));
    }
    /**
     * Get latest 3 user
     */
    #[Group('User')]
    public function getLatestUser()
    {
        $users = Auth::user()->school->users()
            ->latest()
            ->limit(3)
            ->get();
        return $this->success(UserResource::collection($users));
    }
    /**
     * Get user count created today
     */
    #[Group('User')]
    public function getTodayUser()
    {
        $users = Auth::user()->school->users()->whereDate('created_at', now())->count();
        return $this->success(["count" => (int)$users]);
    }
    /**
     * Create new user at the school
     */
    #[Group('User')]
    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->all());
        return $this->success(new UserResource($user));
    }
    /**
     * Delete user
     */
    #[Group('User')]
    public function destroy(User $user)
    {
        Gate::allowIf(function (User $auth) use ($user) {
            if ($auth->role == 'admin' && !in_array($user->role, ['super', 'admin'])) {
                return $auth->school_id == $user->school_id;
            } else if ($auth->role == 'super') {
                return $user->role == 'admin';
            }
            return false;
        });
        $user->delete();
        return $this->delete();
    }

    /**
     * Get all users data at one school
     */
    #[Group('User')]
    public function getUsers()
    {
        $users = User::with(['room', 'mentor'])->whereSchoolId(Auth::user()->school_id)->get();
        return $this->success(UserResource::collection($users));
    }
    /**
     * Get all users data at one school by its type
     */
    #[Group('User')]
    public function getUsersByType(string $type)
    {
        $users = User::whereRole($type)->whereSchoolId(Auth::user()->school_id)->get();
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
     * Edit user data (by admin)
     * 
     * Kalau yang diedit adalah siswa maka butuh room_id (berupa 8 karakter kode kelas) dan mentor_id (berupa NIP Guru Wali). Selebihnya tidak
     */
    #[Group('User')]
    public function edit(Request $request, User $user)
    {
        Gate::allowIf(function (User $auth) use ($user) {
            return $auth->role == 'admin' && $auth->school_id == $user->school_id && !in_array($user->role, ['admin', 'super']);
        });
        if ($user->role == 'student') {
            $data = $request->validate([
                "username" => "string|unique:users,username,{$user->id}",
                "phone"    => "string|unique:users,phone,{$user->id}",
                "identifier"    => "string|unique:users,identifier,{$user->id}",
                "room_id"    => "string|exists:rooms,code",
                'mentor_id' => [
                    'string',
                    Rule::exists('users', 'identifier')->where(function ($query) {
                        $query->where('role', 'teacher');
                    }),
                ],
                "name"    => "string",
                "password" => "nullable|string|min:8", // optional password
            ]);
            $data["room_id"] = Room::whereCode($data["room_id"])->pluck('id')[0];
            $data["mentor_id"] = User::whereIdentifier($data["mentor_id"])->pluck('id')[0];
        } else if (in_array($user->role, ['teacher', 'headteacher', 'counselor'])) {
            $data = $request->validate([
                "username" => "string|unique:users,username,{$user->id}",
                "phone"    => "string|unique:users,phone,{$user->id}",
                "identifier"    => "string|unique:users,identifier,{$user->id}",
                "name"    => "string",
                "password" => "nullable|string|min:8", // optional password
            ]);
        }

        // If password provided, hash it. Otherwise remove it from $data.
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $this->success(new UserResource($user), 'Success update user profile');
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
     * Create student bulk with excel
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
}
