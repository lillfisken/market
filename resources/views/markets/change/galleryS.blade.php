<div class="gallery-box-item-s">

    <div class="flex-row">
        <h4 class="flex-left">
            <a href="{{ route('giveaway.show', $market->id) }}" class="market-list-container">
                {{ str_limit($market->title , 30) }}
            </a>
        </h4>
        <div class="flex-right">
            @include('markets.base._marketmenu')
        </div>
    </div>

    <img src="{{ $market->image1_thumb }}" class="flex-row"/>

    <a href="{{ route('giveaway.show', $market->id) }}" class="">
        <div class="flex-row">
                <div class="">
                    <h5>{{ $marketCommon->getMarketTypeName($market->marketType) }}</h5>
                    @if(isset($market->deleted_at))
                        <div>Avslutad {{ $market->deleted_at }}</div>
                    @endif
                </div>
                <div class="">
                    <p>
                        {{ $market->user->username or 'Unable to get username'}}<br/>
                    </p>
                </div>
        </div>
    </a>

</div>