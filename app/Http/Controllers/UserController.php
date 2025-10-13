<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\CreateStudentRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserFirstLoginRequest;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Imports\UsersImportSync;
use App\Models\Room;
use App\Models\User;
use App\Traits\ApiResponder;
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
        $students = User::with(['room', 'mentor', 'lastmoodres'])->whereRole('student')->where($role.'_id', Auth::id())->get();

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

        return $this->success(['count' => (int) $users]);
    }

    /**
     * Create new admin of the school
     */
    #[Group('User')]
    public function adminCreate(CreateAdminRequest $request)
    {
        $user = User::create($request->all());

        return $this->success(new UserResource($user));
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
            if ($auth->role == 'admin' && ! in_array($user->role, ['super', 'admin'])) {
                return $auth->school_id == $user->school_id;
            } elseif ($auth->role == 'super') {
                return $user->role == 'admin';
            }

            return false;
        });
        $copy = $user->toArray();
        $user->delete();

        return $this->delete($copy);
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
     * Get all users data by its type
     *
     * Jika dilakukan oleh Super Admin maka semua data didalam Sistem, selainnya maka hanya di sekolah tersebut
     */
    #[Group('User')]
    public function getUsersByType(string $type)
    {
        Gate::allowIf(function (User $user) {
            return $user->role != 'student';
        });
        if (Auth::user()->role == 'super') {
            $users = User::with('school')->whereRole($type)->get();
        } else {

            $users = User::whereRole($type)->whereSchoolId(Auth::user()->school_id)->get();
        }

        return $this->success(UserResource::collection($users));
    }

    /**
     * Get user data by username
     */
    #[Group('User')]
    public function getUserDetail(string $username)
    {
        $user = User::with(['school', 'room', 'mentor', 'counselor'])->where('username', $username)->first();

        return $this->success(new UserResource($user));
    }

    /**
     * Get template for bulk create
     */
    #[Group('User')]
    public function getTemplate()
    {
        return $this->success(['link' => env('APP_URL').'/templates/Template%20Siswa.xlsx']);
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
     * Kalau yang diedit adalah siswa maka butuh room_id (berupa 8 karakter kode kelas) dan mentor_id (berupa NIP Guru Wali). Jika admin yang diedit maka butuh school_id. Selainnya hanya username, phone, identifier, name, dan password
     */
    #[Group('User')]
    public function edit(Request $request, User $user)
    {
        Gate::allowIf(function (User $auth) use ($user) {
            return ($auth->role == 'admin' && $auth->school_id == $user->school_id) || ($auth->role == 'super');
        });
        if ($user->role == 'student') {
            $data = $request->validate([
                'username' => "string|unique:users,username,{$user->id}",
                'phone' => "string|digits_between:10,15|unique:users,phone,{$user->id}",
                'identifier' => "string|digits:10|unique:users,identifier,{$user->id}",
                'room_id' => 'string|exists:rooms,code',
                'mentor_id' => [
                    'string',
                    Rule::exists('users', 'identifier')->where(function ($query) {
                        $query->where('role', 'teacher');
                    }),
                ],
                'name' => 'string',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
            ]);
            $data['room_id'] = Room::whereCode($data['room_id'])->pluck('id')[0];
            $data['mentor_id'] = User::whereIdentifier($data['mentor_id'])->pluck('id')[0];
        } elseif (in_array($user->role, ['teacher', 'headteacher', 'counselor'])) {
            $data = $request->validate([
                'username' => "string|unique:users,username,{$user->id}",
                'phone' => "string|digits_between:10,15|unique:users,phone,{$user->id}",
                'identifier' => "string|digits_between:16,25|unique:users,identifier,{$user->id}",
                'name' => 'string',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
            ]);
        } elseif ($user->role == 'admin') {
            $data = $request->validate([
                'username' => "string|unique:users,username,{$user->id}",
                'phone' => "string|digits_between:10,15|unique:users,phone,{$user->id}",
                'identifier' => "string|digits_between:16,25|unique:users,identifier,{$user->id}",
                'name' => 'string',
                'school_id' => 'integer|exists:schools,id',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
            ]);
        }

        if (! empty($data['password'])) {
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
            'username' => "string|unique:users,username,{$user->id}",
            'phone' => "string|unique:users,phone,{$user->id}",
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
            'file' => 'required|file|mimes:xlsx',
        ]);
        $file = $request->file('file');
        if ($file->getSize() > 30 * 1024) {
            Excel::import(new UsersImport(Auth::user()->school_id), $file);

            return $this->success(null, 'Your data will insert async');
        } else {
            $import = new UsersImportSync(Auth::user()->school_id);
            Excel::import($import, $file);
            $data = $import->getInsertedUsers();
            $count = $data->count();

            return $this->success(compact(['data', 'count']));
        }
    }

    /**
     * Create single student
     */
    #[Group('User')]
    public function studentCreate(CreateStudentRequest $request)
    {
        $student = User::create($request->all());

        return $this->created(new UserResource($student));
    }
}
