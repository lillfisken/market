<?php
/**
 * Created by PhpStorm.
 * User: Oskar
 * Date: 2015-09-27
 * Time: 23:07
 */

namespace market\ViewComposers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use market\Conversation;
use market\helper\time;
use market\Message;
use market\models\watchedEvent;

class menu {

    protected $time;

    public function __construct()
    {
        $this->time = new time();
    }

    public function compose(View $view)
    {
        if(Auth::check())
        {
            $userId = Auth::id();
//            $unread = DB::table('conversations')
//                ->join('messages', function($join) use ($userId) {
//                $join->on(
//                    'conversations.id', '=', 'messages.conversationId');
//                })
//                ->where('conversations.user1', '=', $userId)
//                ->orWhere('conversations.user2', '=', $userId)
//                ->where('messages.read', '=', '0')
//                ->where('messages.senderId', '!=', $userId)
//                ->distinct()
////                ->get();
//                ->count();
//
//              $unread2 = DB::table('conversations')
//                ->join('messages', function($join) use ($userId) {
//                $join->on(
//                    'conversations.id', '=', 'messages.conversationId')
//                    ->where('conversations.user1', '=', $userId)
//                    ->orWhere('conversations.user2', '=', $userId);
//                })
//                ->where('messages.read', '=', '0')
//                ->where('messages.senderId', '!=', $userId)
////                ->distinct()
////                ->get();
//                ->count();

            $unread3 = DB::table('conversations')
                ->join('messages', function($join) use ($userId) {
                    $join
                        ->on('conversations.id', '=', 'messages.conversationId')
                        ->where('messages.read', '=', '0')
                        ->where('messages.senderId', '!=', $userId);
                })

                ->where('conversations.user1', '=', $userId)
                ->orWhere('conversations.user2', '=', $userId)
                ->count();


//            dd(
//                $unread,
//                $unread2,
//                $unread3,
//                $userId
//            );

            $watched = DB::table('watcheds')->join('watched_events', function($join){
                $join->on('watcheds.id', '=', 'watched_events.watched');
            })
            ->where('watched_events.read', 0)
            ->count();
//            $watched = 3;
//            dd($unread, $watched);
//            $watched = watchedEvent::where('user', Auth::id())->where('read', 0)->count();

//            dd(DB::table('messages')
//                ->join('conversations', function($join){
//                    $join->on('conversations.id', '=', 'messages.conversationId')
//                        ->where('conversations.user1', '=', Auth::id())
//                        ->orWhere('conversations.user2', '=', Auth::id());
////                    ->where('messages.read', '=', '0');
//                })
//                ->where('messages.read', '=', '0')
//                ->where('messages.senderId', '!=', Auth::id())
//                ->distinct()
////                ->count('messages.id')
//                ->toSql(),
//
//                DB::table('messages')
//                    ->join('conversations', function($join){
//                        $join->on('conversations.id', '=', 'messages.conversationId')
//                            ->where('conversations.user1', '=', Auth::id())
//                            ->orWhere('conversations.user2', '=', Auth::id());
////                    ->where('messages.read', '=', '0');
//                    })
//                    ->where('messages.read', '=', '0')
//                    ->where('messages.senderId', '!=', Auth::id())
//                    ->distinct()
//                ->get(),
//
//                'Auth: ' .  Auth::id(),
//                'Unread: ' . $unread);

            $view->with('unreadMessagesCount', $unread3);
            $view->with('username', Auth::user()->username);
            $view->with('watchedCount', $watched);

//            $view->with('time', $this->time->getTimeString());
//            $view->with('unixTime', $this->time->getTimeUnix());

            //TODO: Watched
        }

    }

}