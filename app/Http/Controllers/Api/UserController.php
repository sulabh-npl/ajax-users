<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\StoreUserRequest;
use Exception, DB, Storage;

class UserController extends Controller
{
    public function index(Request $request){
        try{
            $users = User::with('role')->when($request->has('role_id'), function($query) use ($request){
                $query->where('role_id', $request->role_id);
            })->get();

            $data['users'] = UserResource::collection($users);
            $data['message'] = 'Users retrieved successfully';

            return $this->successResponse($data, 200);            
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(StoreUserRequest $request){
        try{

            DB::beginTransaction();

            $user = new User();
            $user->fill($request->validated());
            $user->profile_image = $request->file('profile_image')->store('images', 'public');
            $user->save();

            DB::commit();
            
            $data['user'] = new UserResource($user);
            $data['message'] = 'User created successfully';

            return $this->successResponse($data, 201);

        }catch(Exception $e){

            DB::rollBack();
            
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(User $user){
        try{

            DB::transaction(function() use ($user){
                if($user->profile_image){
                    Storage::disk('public')->delete($user->profile_image);
                }
                $user->delete();
            });
            
            $data['message'] = 'User deleted successfully';

            return $this->successResponse($data, 200);

        }catch(Exception $e){
            
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
