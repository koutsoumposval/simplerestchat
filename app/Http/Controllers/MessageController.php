<?php
namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController
 * @package App\Http\Controllers
 * @author Chrysovalantis Koutsoumpos <chrysovalantis.koutsoumpos@devmob.com>
 */
class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param string $sender
     * @return Response
     */
    public function showBySender(string $sender): Response
    {
        $messages = Message::where('sender_id', $sender)->where('receiver_id', Auth::user()->id)->get();

        return new JsonResponse(['status' => 'success', 'result' => $messages->toArray()]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $this->validate($request, [
            'message' => 'required',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $message = new Message();
        $message->message = $request->message;
        $message->receiver_id = $request->receiver_id;
        $message->sender_id = Auth::user()->id;

        if (! $message->save()) {
            return new JsonResponse(['status' => 'fail'], 500);
        }

        return new JsonResponse(['status' => 'success', 'result' => $message]);
    }
}