<label for="show-menu" class="show-menu"><i class="fa fa-bars bars" ></i></label>
<input type="checkbox" id="show-menu" class="menu-bar" role="button">

<ul class="resp-menu">
    <li class="searchTop">
        {!! Form::open(array('route' => 'markets.search', 'method' => 'GET' )) !!}
        {!! Form::text('s', null , ['class' => 'inline'] ) !!}
        {!! Form::submit('Sök', ['class' => 'btnSmall inline']); !!}
        {!! Form::close() !!}
    </li>

    <li><a href="{{ route('markets.index') }}">Alla annonser</a></li>

    @if(Auth::check())
        <li>
            <a href="#">
                {{ $username }}
                @if($watchedCount > 0)
                    <span class="badge">{{ $watchedCount }}</span>
                @endif </a>
            <ul>
                <li><a href="{{ route('accounts.profile', $username) }}">Profil</a></li>
                <li><a href="{{ route('accounts.watched') }}">
                    Bevakade
                    @if($watchedCount > 0)
                        <span class="badge">{{ $watchedCount }}</span>
                    @endif
                </a></li>
                <li><a href="{{ route('accounts.active', @Auth::user()->username) }}">Aktiva
                        <span class="badge">?</span>
                    </a></li>
                <li><a href="{{ route('accounts.trashed', @Auth::user()->username) }}">Avslutade
                        <span class="badge">?</span>
                    </a></li>
                <li><a href="{{ route('accounts.blockedmarket') }}">Blockerade annonser</a></li>
                <li><a href="{{ route('accounts.blockedseller') }}">Blockerade säljare</a></li>
            </ul>
        </li>
        <li>
            {{--<a href="#">PM<div class="circle-text"><div>5</div></div> </a>--}}
            <a href="#">PM
                @if($unreadMessagesCount > 0)
                    <span class="badge">{{ $unreadMessagesCount }}</span>
                @endif
            </a>
            <ul>
                <li><a href="{{ route('message.inbox') }}">Inkorg
                    @if($unreadMessagesCount > 0)
                        <span class="badge">{{ $unreadMessagesCount }}</span>
                    @endif</a></li>
                <li><a href="{{ route('message.new') }}">Nytt PM</a></li>
            </ul>
        </li>

        <li>
            <a href="#">Ny annons</a>
            <ul>
                <li><a href="{{ route('auction.create') }}">Auktion</a></li>
                <li><a href="{{ route('sell.create') }}">Säljes</a></li>
                <li><a href="{{ route('buy.create') }}">Köpes</a></li>
                <li><a href="{{ route('giveaway.create') }}">Skänkes</a></li>
                <li><a href="{{ route('change.create') }}">Bytes</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Inställningar</a>
            <ul>
                <li><a href="{{ route('accounts.settings.settings') }}">Profil</a></li>
                <li><a href="{{ route('accounts.settings.password') }}">Lösenord</a></li>
                <li><a href="{{ route('accounts.settings.oauth') }}">Externa inloggningar</a></li>
            </ul>
        </li>
        <li><a href="{{ route('accounts.logout') }}">Logga ut</a></li>
        @if(Config::get('app.debug') == 'true')
            <li><a href="/market/public/index.php/dev">Dev</a></li>
        @endif
    @else
        <li><a href="{{ route('accounts.login') }}">Logga in</a></li>
    @endif

    <li class="searchBottom">
        {!! Form::open(array('route' => 'markets.search', 'method' => 'GET' )) !!}
            {!! Form::text('s', null , ['class' => 'inline'] ) !!}
            {!! Form::submit('Sök', ['class' => 'btnSmall inline']); !!}
        {!! Form::close() !!}
    </li>

    <li class="text right">
        <span id="time" data-unix="{{ $time->getTimeUnix() }}"> {{ $time->getTimeString() }} </span>
    </li>
    <li class="text right">Admin</li>

</ul>