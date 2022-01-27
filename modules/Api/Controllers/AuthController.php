<?php
namespace Modules\Api\Controllers;

use Validator;
use Matrix\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Modules\User\Events\SendMailUserRegistered;
use App\Http\Controllers\Auth\ForgotPasswordController;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','forgetPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status'=>0,'message' => __('Password is not correct'),'status'=>0], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $rules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name'  => [
                'required',
                'string',
                'max:255'
            ],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password'   => [
                'required',
                'string'
            ],
            'term'       => ['required'],
        ];
        $messages = [
            'email.required'      => __('Email is required field'),
            'email.email'         => __('Email invalidate'),
            'password.required'   => __('Password is required field'),
            'first_name.required' => __('The first name is required field'),
            'last_name.required'  => __('The last name is required field'),
            'term.required'       => __('The terms and conditions field is required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        } else {
            $user = \App\User::create([
                'name'       =>$request->input('first_name') .' '.$request->input('last_name'),
                'first_name' => $request->input('first_name'),
                'last_name'  => $request->input('last_name'),
                'email'      => $request->input('email'),
                'password'   => Hash::make($request->input('password')),
                'publish'    => $request->input('publish'),
                'phone'    => $request->input('phone'),
            ]);
            event(new Registered($user));
            try {
                event(new SendMailUserRegistered($user));
            } catch (Exception $exception) {
                Log::warning("SendMailUserRegistered: " . $exception->getMessage());
            }
            $user->assignRole('customer');
            return $this->sendSuccess(__('Register successfully'));
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        if(Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user')==1){
            return $this->sendError(__("You have to verify email first"), ['url' => url('api/email/resend/verify')]);
        }
        $user = request()->user();

        if(!empty($user['avatar_id'])){
            $user['avatar_url'] = get_file_url($user['avatar_id'],'full');
            $user['avatar_thumb_url'] = get_file_url($user['avatar_id']);
        }else{
            $user['avatar_url'] = request()->user()->getAvatarUrl();
            $user['avatar_thumb_url'] = request()->user()->getAvatarUrl();
        }

        return $this->sendSuccess([
            'data'=>$user
        ]);
    }

    public function updateUser(Request $request){
        if(Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user')==1){
            return $this->sendError(__("You have to verify email first"), ['url' => url('api/email/resend/verify')]);
        }
        $user = Auth::user();
        $rules = [
            'first_name' => 'required|max:255',
            'last_name'  => 'required|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
        ];
        $messages = [
            'first_name.required' => __('The first name is required field'),
            'last_name.required'  => __('The last name is required field'),
            'email.required'       => __('The email field is required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }
        $user->fill($request->input());
        $user->birthday = date("Y-m-d", strtotime($user->birthday));
        $user->save();
        return $this->sendSuccess(__('Update successfully'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out','status'=>1]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status'=>1,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function changePassword(Request $request){

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return $this->sendError(__("Your current password does not matches with the password you provided. Please try again."));
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            return $this->sendError(__("New Password cannot be same as your current password. Please choose a different password."));
        }

        $validator = Validator::make($request->all(), [
            'current-password' => 'required',
            'new-password'     => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }


        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return $this->sendSuccess(__('Password changed successfully !'));
    }

    public function forgetPassword(Request $request)
    {

    }

    public function verify(Request $request)
    {
        if(Auth::user() && !Auth::user()->hasVerifiedEmail() && setting_item('enable_verify_email_register_user')==1){
            $request->user()->sendEmailVerificationNotification();
            return $this->sendSuccess(__("A fresh verification link has been sent to your email address."));
        }

        return $this->me();
    }

}
