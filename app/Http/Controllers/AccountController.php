<?php namespace market\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use market\core\market\marketPrepare;
use market\core\market\marketType;
use market\core\session\sessionUrl;
use market\helper\debug;
use market\helper\markets\common;
use market\Http\Requests;
use market\Http\Controllers\Controller;

use Illuminate\Contracts\Auth\Authenticator;
use Input;
use market\Http\Requests\passwordRequest;
use market\Http\Requests\registerRequest;
use market\Http\Requests\UserSettingsRequest;
use market\models\blockedUser;
use market\models\Market;
use market\models\phpBBUsers;
use market\models\watched;
use market\models\watchedEvent;
use Redirect;
use market\models\User;
use Illuminate\Http\Request;
use DB;
use Hash;
use market\helper\text;
use market\helper\marketMenu;
use Symfony\Component\HttpFoundation\Response;
use Zjango\Laracurl\Facades\Laracurl;

class AccountController extends Controller
{
    protected $marketCommon;
    protected $marketPrepare;

    public function __construct(marketType $marketType, marketPrepare $marketPrepare)
    {
        $this->marketCommon = $marketType;
        $this->marketPrepare = $marketPrepare;
    }

    //region Profile

    /* Show user profile
     *
     * get 'profile/{user}'
     * route 'accounts.profile'
     * middleware 'auth'
     *
     * @var user
     * @return
    */
    public function show($user, marketType $marketType, Request $request)
    {
//        dd(config('market.paginationNr'));

        //TODO::Change to show public userprofile
        //TODO: Multiple pagination

        $marketListType = $request->get('markets');

        if($marketListType == 'ended')
        {
            $markets = Market::where('createdByUser', $user->id)
                ->onlyTrashed()
                ->paginate(5);
//                ->paginate(config('market.paginationNr', 20));

            $marketsName = 'Avslutade annonser';
        }
        else
        {
            //Active as default
            $markets = Market::where('createdByUser', $user->id)
                ->paginate(config('market.paginationNr', 20));
            $marketsName = 'Aktiva annonser';
        }
        //TODO: set pagination base
        $markets->setPath(route('accounts.profile', $user->username));
        $markets->appends(['markets' => $marketListType ? $marketListType : 'active']);

        foreach($markets as $market)
        {
            marketMenu::addMarketMenu($market);
            $this->marketCommon->setRouteBase($market);
        }

        $phpBBUsers = phpBBUsers::where('user', $user->id)->get();
        $phpBB = [];
        foreach($phpBBUsers as $phpBBUser)
        {
            $phpBB[] = [
                'forumName' => $this->getForumByKey($phpBBUser->forumKey)['displayName'],
                'username' => $phpBBUser->username,
                'url' => $phpBBUser->url,
            ];
        }

        return view(
            'account.userProfile',
            [
                'user' => $user,
                'markets' => $markets,
                'marketsName' => $marketsName,
                'phpBBs' => $phpBB,
                'marketCommon' => $marketType,
            ]
        );
    }

    protected function getForumByKey($key)
    {
        //TODO: Move this to helper
        $forums = Config::get('phpBBforums');

        foreach($forums as $forum)
        {
            if($forum['key'] == $key)
            {
                return $forum;
            }
        }

        // We couldn't match the forumId to any forum
        return new Response('No forum', 400);
    }

    //endregion

    //region Market listings

    /* Show user profile
             *
             * get 'profile/active/{user}'
             * route 'accounts.active'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function active()
    {
        //TODO: Move to core
        $markets = Market::where('createdByUser', Auth::id())
            //TODO: Eager load
            ->paginate(config('market.paginationNr'));
        $markets->setPath(route('accounts.active'));

        return view('account.markets.active', [
            'markets' => $markets,
            'marketCommon' => $this->marketCommon,
        ]);
    }

    /* Show user profile
             *
             * get 'profile/trashed/{user}'
             * route 'accounts.trashed'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function trashed()
    {
        //TODO: Move to core
        $markets = Market::onlyTrashed()->where('createdByUser', Auth::id())
            ->paginate(config('market.paginationNr'));
        $markets->setPath(route('accounts.trashed'));

        $this->marketPrepare->addStuff($markets);

        return view('account.markets.trashed', [
            'markets' => $markets,
            'marketCommon' => $this->marketCommon,
        ]);
    }

    /* Show user profile
             *
             * get 'profile/blockedmarked/{user}'
             * route 'accounts.blockedmarked'
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function blockedmarket(marketType $marketType)
    {
        $markets = Market::has('blocked')
            ->paginate(config('market.paginationNr'));
        $markets->setPath(route('accounts.blockedmarket'));

        $this->marketPrepare->addStuff($markets);

        return view('account.markets.blockedMarkets', [
            'markets' => $markets,
            'marketCommon' => $marketType,
        ]);
    }

    /* Show user profile
             *
             * get 'profile/blockedseller/{user}'
             * route 'accounts.blockedseller''
             * middleware 'auth'
             *
             * @var user
             * @return
            */
    public function blockedseller()
    {
        $users = User::whereHas('blockedUsers', function($query) {
                $query->where('blockingUserId', '=', Auth::id());
            })
            ->paginate(config('market.paginationNr'));
        $users->setPath(route('accounts.blockedmarket'));
//        $users = User::has('blockedUsers')
//        ->get();

        $blockedUsers = blockedUser::where('blockingUserId', Auth::id())
            ->with('blockedUser')
            ->paginate(config('market.paginationNr'));
        $blockedUsers->setPath(route('accounts.blockedmarket'));

//        dd('AccountController@blockedseller', $users, $blockedUsers);
        return view('account.markets.blockedSellers', ['blockedUsers' => $blockedUsers]);
    }

    //endregion

    //region Watched

    /* Show user profile
         *
         * get 'profile/watched/{user}'
         * route accounts.watched'
         * middleware 'auth'
         *
         * @var user
         * @return
        */
    public function watched()
    {
        $watched = watched::getAllMarketIdsWatchedByUserId(Auth::id());
        $markets = Market::whereIn('id', $watched)
            ->with('watched.unreadEvents')
            ->paginate(config('market.paginationNr'));
        $markets->setPath(route('accounts.watched'));

//        foreach($markets as $market)
//        {
//            $events = [];
//            $read = [];
//            foreach($market->watched[0]->unreadEvents as $event)
//            {
//                $events[] = $event;
//                $read[] = $event->id;
//            }
//            $market['events'] = $events;
//        }

//        //TODO: update events to read = 1
//        if(!empty($read))
//        {
//            //Delete read events
//            watchedEvent::whereIn('id', $read)->update(['read' => 1]);
//        }

//        marketMenu::addMarketMenuToMarkets($markets);
        $this->marketPrepare->addStuff($markets);

        return view('account.markets.watched', [
            'markets' => $markets,
            'marketCommon' => $this->marketCommon,
        ]);
    }

    public function watchMarket($marketId)
    {
        $user = Auth::id();
//        $previousUrl = URL::previous();
        $market = Market::find($marketId);

        if($market)
        {
            return view('account.watched.watchConfirm')
                ->with('user', $user)
                ->with('market', $market);
        }
        else
        {
            abort(404);
        }
    }

    public function watchMarketPost(Request $request)
    {
        if($request->get('yes') && $request->get('marketId'))
        {
//            $markets = watched::where('user', Auth::id())->get();
            $watched = new watched([
                'userId' => Auth::id(),
                'market' => $request->get('marketId')
            ]);

            $watched->save();

            return redirect()->route('accounts.watched', [Auth::user()->username]);
        }
        else
        {
            abort(404);
        }

        dd($request->get('yes'), $request->get('marketId'), $request->all());
//        dd(Auth::user()->username);
    }

    public function unwatchMarket($marketId, sessionUrl $sessionUrl)
    {
        $market = Market::find($marketId);

        if($market)
        {
            $sessionUrl->setPreviousUrl();
            return view('confirm')
                ->with('title', 'Avblockera ' . $market->title . '?')
                ->with('callbackRoute', 'accounts.unwatchMarketPost')
                ->with('text', 'Vill du sluta bevaka "' . $market->title . '"?')
                ->with('hidden', $marketId)
                ;
        }
        else
        {
            abort(404);
        }
    }

    public function unwatchMarketPost(Request $request, sessionUrl $sessionUrl)
    {
        if($request->get('yes') && $request->get('hidden'))
        {
            $watched = watched::where('market', $request->get('hidden'))
                    ->where('userId', Auth::id())
                    ->first();

            if($watched)
            {
                $watched->delete();
            }
        }
        return $sessionUrl->redirectToPreviousUrlOrDefault();
    }

    //endregion

    //region Settings

    public function auth()
    {
        //Show user a page to manage external logins
        $user = User::where('id', Auth::id())->with('phpBBUsers')->get()->first();
        $forums = Config::get('phpBBforums');
        $forumsUser = $user->phpBBUsers;
        $phpBBNonRegistered = [];
        $phpBBRegistered = [];

        foreach($forums as $forum)
        {
            $match = false;
            foreach($forumsUser as $forumUser)
            {
                if($forum['key'] == $forumUser['forumKey'])
                {
                    $forum['username'] = $forumUser['username'];
                    array_push($phpBBRegistered, $forum);
                    $match = true;
                    break 1;
                }
            }
            if(!$match)
            {
                array_push($phpBBNonRegistered, $forum);
            }
        }
        return view('account.settings.oAuth', ['user' => $user,
            'phpBBRegistered' => $phpBBRegistered,
            'phpBBNonRegistered' => $phpBBNonRegistered]);
    }

    //endregion

}

