<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Roles fetched successfully.',
            'data' => Role::all()
        ]);
    }

    /**
     * Store a newly created role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the role
        $role = Role::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return response()->json([
            'success' => true,
            'message' => 'Role details fetched successfully.',
            'data' => $role
        ]);
    }

    /**
     * Update the specified role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update the role
        $role->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => $role
        ]);
    }

    /**
     * Remove the specified role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ], 200);
    }

    /**
     * Assign permissions to a role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function assignPermissions(Request $request, Role $role)
    {
        // Validate incoming request for permissions
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Sync permissions to the role
        $permissions = Permission::find($request->permissions);
        $role->permissions()->sync($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned successfully.',
            'data' => $role->permissions
        ]);
    }

    /**
     * Get all permissions for a role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function getPermissions(Role $role)
    {
        return response()->json([
            'success' => true,
            'message' => 'Permissions fetched successfully.',
            'data' => $role->permissions
        ]);
    }

    /**
     * Assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function assignRoleToUser(Request $request, User $user)
    {
        // Check if the authenticated user can assign roles
        $this->authorize('assignRole', [Role::class, $user]);

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the validated role
        $validated = $validator->validated();

        // Check if the user already has the role
        $role = Role::where('name', $validated['role'])->first();
        if ($role && $user->hasRole($validated['role'])) {
            return response()->json([
                'success' => false,
                'message' => "User already has the '{$validated['role']}' role.",
            ]);
        }

        // Assign the role to the user
        $user->assignRole($validated['role']);

        return response()->json([
            'success' => true,
            'message' => "Role '{$validated['role']}' assigned to user successfully.",
            'data' => [
                'user_id' => $user->id,
                'roles' => $user->roles->pluck('name')
            ]
        ]);
    }
}
