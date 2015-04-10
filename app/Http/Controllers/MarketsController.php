<?php namespace market\Http\Controllers;

use Illuminate\Routing\Controller as ControllerMarket;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use market\helper\debug;
use market\helper\marketType;
use market\helper\marketEndReason;
use market\helper\marketCRUD;
use market\helper\text;
use market\Http\Requests\CreateUpdateQuestionRequest;
use market\Http\Requests\MarketCreateUpdateRequest;
use market\Market;
use market\MarketQuestions;
use Illuminate\Http\Request;
use Input;
use DB;
use Session;
use Chromabits\Purifier\Contracts\Purifier;
use HTMLPurifier_Config;

use Intervention\Image\ImageManagerStatic as Image;

class MarketsController extends ControllerMarket {
	
	/*
	|--------------------------------------------------------------------------
	| Market Controller
	|--------------------------------------------------------------------------
	|
	| Controller methods are called when a request enters the application
	| with their assigned URI. The URI a method responds to may be set
	| via simple annotations.
	|
	*/

    /**
     * @var Purifier
     */
    protected $purifier;

    /**
     * Construct an instance of MyClass
     *
     * @param Purifier $purifier
     */
    public function __construct(Purifier $purifier) {
        // Inject dependencies
        $this->purifier = $purifier;
    }

	
	/**
	 * Display a listing of the resource.
	 * GET /markets
	 *
	 * @return Response
	 */
	public function index()
	{
        echo '<script>console.log("Market index  -----------------------")</script>';
        debug::logConsole('Echoing thru debug');

        if(Auth::check())
		{
			//TODO::Sort non blocked markets for user
			//Get all markets from db

			$temp = Market::select()->with('User')->get();

			//Set menu for each market
			foreach ($temp as $market)
			{
				marketCRUD::addMarketMenu($market);
			}

			//echo("<script>console.log('MarketsController->index');</script>");
		}
		else {
			//Get all markets from db
			$temp = Market::all();
			//$temp = dd($temp);
		}

		//dd($market);

		return view('markets.index', ['markets' => $temp]);
	}

	public function filter()
	{
		//TODO:: Sanitize
		// Begining of building db query
		$query = Market::select('*');

		// Remove deleted markets from query if box checked
		if(Input::has('ended'))
		{
			$query->withTrashed();
		}

//		// Add type af markets to query depending on which boxes are ticked
		$query->where(function($query) {


			if (Input::has('saljes')) {
				$query->orWhere('type', '=', 'saljes');
			}

            foreach(marketType::getAllTypes() as $key => $val)
            {
                if(Input::has('t' . $key))
                {
                    $query->orWhere('marketType', '=', $key);
                }
            }
//
//
//
//			if (Input::has('kopes')) {
//				$query->orWhere('type', '=', 'kopes');
//			}
//
//			if (Input::has('bytes')) {
//				$query->orWhere('type', '=', 'bytes');
//			}
//
//			if (Input::has('skankes')) {
//				$query->orWhere('type', '=', 'skankes');
//			}
//
//			if (Input::has('samkop')) {
//				$query->orWhere('type', '=', 'samkop');
//			}
//
//			if (Input::has('tjanst_erbjudes')) {
//				$query->orWhere('type', '=', 'tjanst_erbjudes');
//			}
//
//			if (Input::has('tjanst_sökes')) {
//				$query->orWhere('type', '=', 'tjanst_sökes');
//			}
//
//			if (Input::has('anstallning')) {
//				$query->orWhere('type', '=', 'anstallning');
//			}
//
//			if (Input::has('tips')) {
//				$query->orWhere('type', '=', 'tips');
//			}
		});

		if (Input::has('hiddenAds')) {
			//TODO: Check for hidden markets
		}

		if (Input::has('hiddenSellers')) {
			//TODO: Check for hidden sellers
		}

        //Query the db
		$temp = $query->get();

		// add a menu to each market if user is logged in
		if(Auth::check())
		{
			foreach ($temp as $market)
			{
				marketCRUD::addMarketMenu($market);
			}
		}

        //Why is this here?
		Input::flash();
		return view('markets.index', ['markets' => $temp]);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /markets/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('markets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /markets
	 *
	 * @return Response
	 */
	public function store(MarketCreateUpdateRequest $request)
	{
        debug::logConsole('MarketsController -> store -------------------------------------------');

        $input = Input::all();

        $input = text::purifyMarketInput($input, $this->purifier);

        if(Input::get('publishBB'))
        {
            debug::logConsole('Marketscontroller -> store -> publishBB');
            $input = text::marketFromBbToHtml($input);
            return marketCRUD::save($input);
//            return marketCRUD::save(Input::all());
        }
        elseif(Input::get('publishHTML'))
        {
            debug::logConsole('Marketscontroller -> store -> publishHTML');
//            $input = text::marketFromHtmlToBB($input);
            return marketCRUD::save($input);
        }
        elseif(Input::get('preview'))
        {
            debug::logConsole('Marketscontroller -> store -> preview');
            $input = text::marketFromBbToHtml($input);
            return marketCRUD::preview($input, URL::route('markets.store'), 'POST');
        }
        elseif(Input::get('edit'))
        {
            debug::logConsole('Marketscontroller -> store -> edit');
            $input = text::marketFromHtmlToBB($input);
            return marketCRUD::editPreview($input);
        }
        else
        {
            dd('error');
        }
    }

	/**
	 * Display the specified resource.
	 * GET /markets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($market)
	{
		//dd($market);
		//echo("<script>console.log('Markets controller -> show');</script>");
		$temp = Market::withTrashed()->with(['user.markets', 'marketQuestions.user'])->find($market);
		marketCRUD::addMarketMenu($temp);
		//$tempCount = $temp->getUserMarketsCount;
		//echo("<script>console.log('Count: " .$tempCount."');</script>");
//		dd($temp);

		return view('markets.show', ['market' => $temp]);
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /markets/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($market)
	{
		$temp = Market::find($market);
        $temp = text::marketFromHtmlToBB($temp);

        //dd($temp);

        //dd($temp);

		return view('markets.edit', ['market' => $temp]);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /markets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update( $id , MarketCreateUpdateRequest $request)
	{
        $input = Input::all();
        $input = text::purifyMarketInput($input, $this->purifier);

        if(Input::get('publishBB'))
        {
            debug::logConsole('MarketsController -> Update -> publishBB ---------------------------------');
            $input = text::marketFromBbToHtml($input);
            marketCRUD::update($id, $input);
            return redirect()->route('markets.show', $id);
        }
        elseif(Input::get('publishHTML'))
        {
            debug::logConsole('MarketsController -> Update -> publishHTML ---------------------------------');
            $input = text::marketFromHtmlToBB($input);
            marketCRUD::update($id, $input);
            return redirect()->route('markets.show', $id);
        }
        elseif(Input::get('preview'))
        {
            debug::logConsole('Marketscontroller -> update -> preview');
            $input = text::marketFromBbToHtml($input);
            return marketCRUD::preview($input, URL::route('markets.update', array($id)), 'Patch');
        }
        elseif(Input::get('edit'))
        {
//            dd('Edit update preview');
            $temp = new Market($input);
            $temp['id'] = $id;
            //dd($temp, $id);
            $temp = text::marketFromHtmlToBB($temp);

            return view('markets.edit', ['market' => $temp]);
        }
        else
        {
            dd('error');
        }
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /markets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($market, Request $request)
	{
        Session::put('uri', Session::get('_previous'));

        $reasons = marketEndReason::getAllTypes();
//        $reasons = ['Varan såld' => 'Varan såld', 'Övrigt' => 'Övrigt'];

		return view('markets.delete', ['markets' => $market, 'reasons' => $reasons]);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /markets/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		$id = Input::get('market');
		$market = Market::where('id', '=', $id)->firstorfail();
        //dd(Input::all());

		//TODO: Add deletion reason to db
        $market['endReason'] = Input::get('reason');
		$market['deleted_at'] = new \DateTime();
        $market->save();

		$uri = Session::get('uri');
		//dd($uri);

		if(isset($uri))
		{
			//dd($uri);
			if(isset($uri['url']))
			{
				//dd($uri['url']);
				return redirect($uri['url']);
			}
			return redirect()->route('markets.index');
			//return URL::to($uri);

			//return redirect('markets/public');
		}
		else
		{
			dd('redirect route');
			return redirect()->route('markets.index');
		}

	}

    public function search()
    {
        $search = Input::get('s');
        if(isset($search)){

            //inspired by:
            //http://stackoverflow.com/questions/19612180/creating-search-functionality-with-laravel-4

			$query = Market::where('title', 'LIKE', '%' . $search . '%')
				->orWhere('description', 'LIKE', '%' . $search . '%');

            $result = $query->get();

			//Set menu for each market
			foreach ($result as $market)
			{
				marketCRUD::addMarketMenu($market);
			}

            return view('markets.index', ['markets' => $result]);
        }


        return 'Searchterm is missing';
    }

	public function question(CreateUpdateQuestionRequest $request)
	{
		//TODO::Add validation, questionRequest
        //TODO:: Sanitize
        debug::logConsole('MarketsController->question post');

        $input = $request->all();
        $input = text::purifyQuestionInput($input, $this->purifier);
//        $input = text::purifyQuestionInput($input);
        $input = text::questionFromBBToHTML($input);

 		$question = new MarketQuestions;

		$question->createdByUser = Auth::id();
		$question->market = $input['market'];
		$question->message = $input['message'];

		$question->save();

		return Redirect::back();
 	}

    public function sendPm($toUser, $title)
    {
        return view('account.message.new', ['reciever' => $toUser, 'title' => 'Angående: ' . $title]);
    }

	protected function filterMarket()
	{

	}

}