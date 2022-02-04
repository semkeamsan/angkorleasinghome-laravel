<?php
namespace Modules\Api\Controllers;

use App\User;
use Socialite;
use Validator;
use App\UserMeta;
use Matrix\Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Modules\User\Events\SendMailUserRegistered;
use App\Http\Controllers\Auth\ForgotPasswordController;

class AuthSocialController extends Controller
{

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

    public function socialLogin($provider)
    {
        $this->initConfigs($provider);
        return Socialite::with($provider)->redirect();
    }

    protected function initConfigs($provider)
    {
        switch($provider){
            case "facebook":
            case "google":
            case "twitter":
                config()->set([
                    'services.'.$provider.'.client_id'=>setting_item($provider.'_client_id'),
                    'services.'.$provider.'.client_secret'=>setting_item($provider.'_client_secret'),
                    'services.'.$provider.'.redirect'=>'/api/social-callback/'.$provider,
                ]);
            break;
        }
    }

    public function socialCallBack($provider)
    {
        try {
            $this->initConfigs($provider);

            $user = Socialite::driver($provider)->user();

            if (empty($user)) {
                return $this->sendError(__('Can not authorize'));
            }

            $existUser = User::getUserBySocialId($provider, $user->getId());

            if (empty($existUser)) {

                $meta = UserMeta::query()->where('name', 'social_' . $provider . '_id')->where('val', $user->getId())->first();
                if (!empty($meta)) {
                    $meta->delete();
                }

                if (empty($user->getEmail())) {
                    return $this->sendError(__('Can not get email address from your social account'));
                }

                $userByEmail = User::query()->where('email', $user->getEmail())->first();

                if (!empty($userByEmail)) {
                    $token = auth('api')->login($userByEmail);
                    return $this->respondWithToken($token);
                }

                // Create New User
                $realUser = new User();
                $realUser->email = $user->getEmail();
                $realUser->password = Hash::make(uniqid() . time());
                $realUser->name = $user->getName();
                $realUser->first_name = $user->getName();
                $realUser->status = 'publish';

                $realUser->save();

                $realUser->addMeta('social_' . $provider . '_id', $user->getId());
                $realUser->addMeta('social_' . $provider . '_email', $user->getEmail());
                $realUser->addMeta('social_' . $provider . '_name', $user->getName());
                $realUser->addMeta('social_' . $provider . '_avatar', $user->getAvatar());
                $realUser->addMeta('social_meta_avatar', $user->getAvatar());

                $realUser->assignRole('customer');

                try {
                    event(new SendMailUserRegistered($realUser));
                } catch (Exception $exception) {
                    Log::warning("SendMailUserRegistered: " . $exception->getMessage());
                }

                // Login with user

                $token = auth('api')->login($realUser);
                return $this->respondWithToken($token);

            } else {

                if ($existUser->deleted == 1) {
                    return $this->sendError(__('User blocked'));
                }
                if (in_array($existUser->status, ['blocked'])) {
                    return $this->sendError(__('Your account has been blocked'));
                }
                $token = auth('api')->login($existUser);
                return $this->respondWithToken($token);
            }
        }catch (\Exception $exception)
        {
         return  $message = $exception->getMessage();
            if(empty($message) and request()->get('error_message')) $message = request()->get('error_message');
            if(empty($message)) $message = $exception->getCode();
            return $this->sendError(__('Can not authorize'));
        }
    }
}
