<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Http\Controllers
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function authenticate(Request $request): Response
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {
            $apikey = base64_encode(str_random(40));
            User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);;

            return new JsonResponse(['status' => 'success', 'api_key' => $apikey]);
        }

        return new JsonResponse(['status' => 'fail'], 401);
    }
}