<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialAccount;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Laravel\Socialite\One\User as SocialiteOneUser;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function __construct()
    {
        $this->middleware('session');
    }


    public function redirectToProvider()
    {
        $redirectResponse = Socialite::driver('twitter')->redirect();

        return $redirectResponse->getTargetUrl();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            return response()->json($this->getCredentialsByTwitter());
        } catch (InvalidArgumentException $e) {
            return $this->errorJsonResponse('Twitterでの認証に失敗しました。');
        } catch (EmailAlreadyExistsException $e) {
            return $this->errorJsonResponse(
                "{$e->getEmail()} は既に使用されているEメールアドレスです。"
            );
        }
    }
    /**
     * @return array
     */
    protected function getCredentialsByTwitter(): array
    {
        $twitterUser = Socialite::driver('twitter')->user();
        $socialAccount = SocialAccount::firstOrNew([
            'provider'   => 'twitter',
            'account_id' => $twitterUser->getId(),
        ]);
        $user = $this->resolveUser($socialAccount, $twitterUser);
        return [
            'user'         => $user,
            'access_token' => $user->createToken(null, ['*'])->accessToken,
        ];
    }
    /**
     * @param  SocialAccount  $socialAccount
     * @param  SocialiteOneUser  $twitterUser
     * @return User
     */
    protected function resolveUser(SocialAccount $socialAccount, SocialiteOneUser $twitterUser): User
    {
        if ($socialAccount->exists) {
            return User::find($socialAccount->getAttribute('id'));
        }
        $createdUser = User::create([
            'name'         => $twitterUser->getName(),
            'email'        => null,
            'password'     => null,
            'twitter_id'   => $twitterUser->getNickname(),
            'avatar'   => $twitterUser->getAvatar(),
        ]);
        $socialAccount->setAttribute('id', $createdUser->id);
        $socialAccount->save();
        return $createdUser;
    }
    /**
     * @param  string  $message
     * @return JsonResponse
     */
    protected function errorJsonResponse(string $message): JsonResponse
    {
        return response()->json(compact('message'), 400);
    }

}
