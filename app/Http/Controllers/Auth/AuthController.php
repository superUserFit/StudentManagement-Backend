<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\common\Helpers;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $data = $request->all();

        DB::beginTransaction();
        try {
            // Check if the user already exists
            $existingUser = User::where('email', $data['email'])->first();

            if(!empty($existingUser)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'User with the following email already exist.'
                ]);
            }

            $data['password'] = Hash::make($data['password']);

            $User = new User;
            $User = Helpers::saveData($User, $data);

            $otp = Helpers::generateOTP($User->email, 5);
            $content['title'] = 'OTP Verification';
            $content['code'] = $otp->token;

            Helpers::SendMail($User->email, $User, $content);
            DB::commit();

            return [
                'status' => 200,
                'data' => $User->id
            ];
        } catch(\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }

    public function login(Request $request) {
        $data = $request->all();

        $User = User::where('email', $data['email'])->first();

        if(!empty($User)) {
            throw new \Exception('User Not Found.');
        }

        $isPasswordMatch = Hash::check($data['password'], $User->password);

        if(!$isPasswordMatch) {
            throw new \Exception('Password Not Match');
        }

        unset($User->password);

        return response()->json(['user' => $User], 200);
    }
}
