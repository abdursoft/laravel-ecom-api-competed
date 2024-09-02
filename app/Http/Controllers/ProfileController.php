<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Create or Update user profile
     */
    public function store(Request $request){
        if($request->hasFile('profile')){
            $validate = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'profile' => 'file|mimes:jpg,png,webp'
            ]);
        }else{
            $validate = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile' => 'required',
                'address' => 'required'
            ]);
        }

        if($validate->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Profile couldn\'t saved'
            ],400);
        }

        try {
            $exist = Profile::where('user_id',$request->header('id'))->first();
            if($exist){
                Profile::where('user_id',$request->header('id'))->update([
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'mobile' => $request->input('mobile'),
                        'address' => $request->input('address'),
                        'profile' => $request->hasFile('profile') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('profile')) : $exist->profile
                    ]
                );
                $request->hasFile('profile') ? Storage::disk('public')->delete($exist->profile) : null;
            }else{
                Profile::create([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'mobile' => $request->input('mobile'),
                    'address' => $request->input('address'),
                    'profile' => $request->hasFile('profile') ? Storage::disk('public')->put("uploads/" . date( 'Y' ) . "/" . date( 'F' ) . "/" . date( 'd' ), $request->file('profile')) : ""
                    ]
                );
            }

            return response()->json([
                'status' => 'succss',
                'message' => 'Profile successfully saved'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Profile couldn\'t saved',
                'error' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Show the profile details
     */
    public function show(Request $request){
        return Profile::where('user_id',$request->header('id'))->first();
    }

    /**
     * Destroy the profile information
     */
    public function destroy(Request $request){
        try {
            $profile = Profile::where('user_id',$request->header('id'))->first();

            $profile->profile != null ? Storage::disk('public')->delete($profile->profile) : null;
            $profile->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile successfully removed'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Profile not found or internal error',
                'error' => $th->getMessage()
            ],400);
        }
    }
}
