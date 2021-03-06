<?php namespace market\models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use market\Commands\sendMailAuctionEnded;
use market\core\mail\auctionEnded;
use market\helper\mailer;
use market\helper\watched as watchedHelper;
use market\models\traits\marketScopes;
use market\models\traits\marketTrait;
use Sofa\Eloquence\Eloquence;

class Market extends Model {

	use SoftDeletes, Eloquence;
//	use marketTrait;
	use marketScopes;

	protected $dates = ['deleted_at'];
	protected $softDelete = true;

	protected $table = 'markets';

	protected $fillable = [
		'createdByUser',
		'title', 
		'description', 
		'price',
		'extra_price_info',
		'number_of_items',
        'marketType',

//        Contact options
        'contactMail',
        'contactPhone',
        'contactPm',
        'contactQuestions',

		'image1_small',
		'image1_thumb',
		'image1_std',
		'image1_full',
		'image2_thumb',
		'image2_std',
		'image2_full',
		'image3_thumb',
		'image3_std',
		'image3_full',
		'image4_thumb',
		'image4_std',
		'image4_full',
		'image5_thumb',
		'image5_std',
		'image5_full',
		'image6_thumb',
		'image6_std',
		'image6_full',

		'created_at',
		'update_at',
		'deleted_at',
        'end_at',
	];

	protected $searchableColumns = ['title' => 20, 'description' => 5];

	protected $with = [
		'user',
		'unreadEventsForUser',
		'watchedByUser',
		'bids',
        'marketBlockedByUser',
	];

    public function getDates()
    {
        return [
			'created_at',
			'updated_at',
			'deleted_at',
			'end_at'
		];
    }

	//region relations

	/*
	 * Add navigation for user connected with market
	*/
	public function user()
	{
		return $this->belongsTo('market\models\User', 'createdByUser');
	}

    public function bids()
    {
        return $this->hasMany('market\models\Bid', 'auctionId', 'id');
//        return $this->hasMany('market\MarketQuestions', 'market', 'id');

    }

	public function marketQuestions()
	{
		return $this->hasMany('market\models\MarketQuestions', 'market', 'id');
	}

    public function watchedByUser()
    {
        return $this->hasOne('market\models\watchedMarketsByUser', 'market', 'id')
            ->where('user', Auth::id());
    }

	public function events()
	{
		return $this->hasMany('market\models\eventMarket', 'market', 'id');
	}

    public function eventsForUser()
    {
        return $this->hasMany('market\models\eventUser', 'marketId', 'id')
            ->where('userId', Auth::id())
            ->where('read', null);
    }

    public function unreadEventsForUser()
    {
        return $this->hasMany('market\models\eventUser', 'marketId', 'id')
            ->where('userId', Auth::id())
            ->where('read', null);
    }

	public function blocked()
	{
		return $this->hasMany('market\models\blockedMarket', 'marketId', 'id');
	}

	public function marketBlockedByUser()
	{
        return $this->hasOne('market\models\blockedMarket', 'marketId', 'id')
				->where('userId', Auth::id());
	}

	public function marketUserBlockedByUser()
	{
		return $this->hasOne('market\models\blockedUser', 'blockedUserId', 'createdByUser')
			->where('blockingUserId', Auth::id());
	}

	public function blockedByUsers()
	{
		return $this->hasMany('market\models\blockedUser', 'blockedUserId', 'createdByUser');
	}

    public function userUnreadEvents()
    {
        return $this->hasMany('market\models\eventUser', 'marketId', 'id')
            ->where('read', null)
            ->with('event');
//            ->where('user', Auth::id());
    }

    public function userAllEvents()
    {
        return $this->hasMany('market\models\eventUser', 'marketId', 'id')
            ->with('event');
//            ->where('user', Auth::id());
    }

	//endregion

	public function setEndAtAttribute($value)
	{
		//http://laravel.io/forum/11-27-2014-handling-dates-with-form-models
		$this->attributes['end_at'] = Carbon::createFromFormat('Y/m/d H:i', $value);
	}

	//region Scope

	public function scopeWithoutBlockedSellers($query)
	{
		return $query->whereDoesntHave('user.blockedUsers', function($query) {
			$query->where('blockingUserId', '=', Auth::id());
		});
	}

	public function scopeWithoutBlockedMarkets($query)
	{
		return $query->has('blocked', '<', 1);
	}

	public function scopeBlockedSellerByUser($query)
	{
		return $query->whereDoesntHave('blockedByUsers', function($query) {
			$query->where('blockingUserId', '=', Auth::id());
		});
	}

	//endregion

    public function getEndReasonName()
    {
		if(isset($this->endReason))
		{
			$reasons = config('market.endReasons');
			return $reasons[$this->endReason];
		}
        return 'N/A';
    }

	public function getRouteBase()
	{
        return config('market.routeBases')[$this->marketType];
	}
}
