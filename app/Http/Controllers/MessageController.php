<?php namespace market\Http\Controllers;

use market\Conversation;
use market\Http\Requests;
use market\Http\Controllers\Controller;

use Illuminate\Contracts\Auth\Authenticator;
use Auth;
use Input;
use market\Market;
use market\Message;
use Redirect;
use market\User;
use Illuminate\Http\Request;

//use Illuminate\Contracts\Auth\Guard;
//use Illuminate\Contracts\Auth\Registrar;


class MessageController extends Controller
{

    //region Mail/PM
    /* Show user profile
             *
             * get 'profile/inbox/{user}'
             * route 'accounts.inbox'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function inbox()
    {
        //$conversations = Conversation::with(['messages.sender'])->get();

        $conversations = Conversation::where('user1', Auth::id())->
            orWhere('user2', Auth::id())->with('messages.sender')->get();
//        dd($conversations);
        return view('account.message.inbox', ['conversations' => $conversations]);
    }

    public function show($conversationId)
    {
        //dd($conversationId);
        $messages = Message::where('conversationId', '=', $conversationId)->orderBy('created_at', 'desc')->with(['sender', 'Conversation'])->get();
//        dd($messages);

        return view('account.message.show', ['messages' => $messages]);
    }

    /* Show user profile
             *
             * get 'profile/draft/{user}'
             * route 'accounts.draft'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function draft()
    {
//        dd('AccountController@draft');
        return view('account.message.draft');
    }

    /* Show user profile
             *
             * get 'profile/sent/{user}'
             * route 'accounts.sent'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function sent()
    {
//        dd('AccountController@sent')
        return view('account.message.sent');

    }

    /* Show user profile
             *
             * get 'profile/trash/{user}'
             * route 'accounts.trash'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function trash()
    {
//        dd('AccountController@trash');
        return view('account.message.trash');

    }

    public function newMessage()
    {
        return view('account.message.new');
    }

    //Post,
    public  function sendMessage(Request $request)
    {
//        dd($request->all());
        //dd(Input::all());
        if(Auth::check())
        {
            //TODO: Validation, redirect back at error with message etc.
//            dd(Input::all());

            $message = new Message();

            //New conversation
            $cId = $request->get('conversationId');
            if(!isset($cId))
            {
                $c = new Conversation();
                $c->title = $request->input('title');
                $c->user1 = Auth::id();

                $user2 = User::select('id')->where('username', '=', $request->input('reciever'))->first();
                if(isset($user2))
                {
                    $c->user2 = $user2['id'];
                }
                else
                {
                    return Redirect::back()->withInput()->with(['message' => 'Hittar inte användaren ' . $request->input('reciever')]);
                }
//                $c->user2 = get user id from reciever, if not found, redirect back with message

//                dd($c);
                $c->save();

                $message->conversationId = $c->id;
                $cId= $c->id;

                //get id, add to message
            }
            else
            {
                $message->conversationId = $request->input('conversationId');

            }

            //Create new message from input and save it in db
            $message->senderId = Auth::id();
            $message->message = $request->input('message');

//            dd($message);

            $message->save();


            //Kolla ID på postaren
            //Plocka ut mottagare, en eller flera
            //Kolla om det finns ett konversations ID
            //      Om inte, skapa ett nytt från sista registrerade (extra db fråga)
            //Skapa nytt meddelande med ovanstående parametrar
            //Spara i db
            //      Om lyckats -> redirect, ladda nya meddelanden
            //      Om inte lyckats, redirekt back med felorsak

            return Redirect::route('message.show', $cId)->with('message', 'Meddelande skickat');
        }
        else{
            return redirect(['route'=>'accounts.login']);
        }
    }

    public function deleteMessage()
    {

    }

    public function mail(Request $request)
    {
        $reciever = $request->query('reciever');
        $title = 'Ang: ' . $request->query('title');

        //dd('reciever: ' . $reciever . ', title: ' . $title);
//        dd($request;
        return view('account.message.mail', ['toUser' => $reciever, 'title' => $title]);
    }

    public function mailPost()
    {
        //TODO: Send email
        dd('mailPost not inplemented yet');
    }

    //endregion
}