<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gender;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $genders = Gender::pluck('gender', 'gender_id');
        $roles = Role::pluck('role', 'role_id');

        return view('user.index', compact('genders', 'roles'));
    }

    public function data()
    {
        $users = User::with('role', 'gender')
            ->select(['user_id', 'name', 'email', 'username', 'contact_number'])
            ->get();

        return datatables()
            ->of($users)
            ->addIndexColumn()
            ->addColumn('select_all', function ($user) {
                return '<input type="checkbox" name="user_id[]" value="' . $user->user_id . '">';
            })
            ->addColumn('role', function (User $user) {
                return $user->role ? $user->role->role_name : '-';
            })
            ->addColumn('gender', function (User $user) {
                return $user->gender ? $user->gender->gender_name : '-';
            })
            ->addColumn('user_image', function (User $user) {

                $userImagePath = public_path('storage/user_image/' . $user->user_image);
                
                if (file_exists($userImagePath)) {

                    return $user->user_image;
                } else { 
                    return 'default-user.png';
                }       

            })
            ->addColumn('action', function ($user) {
                return '<div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('users.update', $user->user_id) . '`)" class="btn btn-xs btn-primary btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`' . route('users.destroy', $user->user_id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>';
            })
            ->rawColumns(['select_all', 'role', 'gender', 'product_image', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer',
            'gender_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact_number' => 'nullable|string|max:20',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        if ($request->hasFile('user_image')) {
            $filenameWithExtension = $request->file('user_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('user_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            $request->file('user_image')->storeAs('public/user_image', $filenameToStore);
            $validated['user_image'] = $filenameToStore;
        } else {
            $validated['user_image'] = 'default-user.png';
        }
        
        User::create($validated);

        return redirect('/users');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->user_image_url = asset('storage/user_image/' . $user->user_image);

        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|integer',
            'gender_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:8',
            'contact_number' => 'nullable|string|max:20',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // if ($request->hasFile('user_image')) {
        //     $image = $request->file('user_image');
        //     $imageName = time() . '.' . $image->getClientOriginalExtension();
            
        //     // Store the image in the storage/app/public directory
        //     $path = $request->file('user_image')->storeAs('public/user_image', $imageName);
            
        //     // Update the validated data with the new image name
        //     $validated['user_image'] = $imageName;
        // } else {
        //     // If no new image is uploaded, keep the existing image path
        //     $validated['user_image'] = $user->user_image; 
        // }

        if ($request->hasFile('user_image')) {
            $filenameWithExtension = $request->file('user_image');
            $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $extension = $request->file('user_image')->getClientOriginalExtension();
            $filenameToStore = $filename . '_' . time() . '.' . $extension;
            
            $request->file('user_image')->storeAs('public/user_image', $filenameToStore);
            
            $validatedData['user_image'] = $filenameToStore;
        }
        else {
            $validatedData['user_image'] = $user->user_image;
        }

        $user->update($validated);

        // Return appropriate response based on request type (JSON or redirect)
        if ($request->expectsJson()) {
            return response()->json(['message' => 'User updated successfully']);
        } else {
            return redirect()->route('users.index');
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->user_id;

        if (!empty($selectedIds)) {
            User::whereIn('user_id', $selectedIds)->delete();
            return response()->json(['message' => 'Selected users deleted successfully'], 200);
            return redirect('/users');
        } else {
            return response()->json(['error' => 'No users selected'], 400);
        }
    }
}