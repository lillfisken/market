@if(isset($market) && $market->contactQuestions)
    <div id="market-forum" class="layout">
        <h1 class="market-title" >Frågor</h1>

        @if(isset($preview) && $preview == true)
            <h4>Förhandsgranskning</h4>
        @else

            {{--@if( $market->marketQuestions() != null )--}}
            @if( $market->marketQuestions()->count() > 0 )

            {{--List questions and answers--}}
            <?php $count = 1; ?>

                @foreach($market->marketQuestions as $question)
                    <div class="stripe dark-border-bottom inner-content">
                        <div class="dark-border-bottom flex-container flex-space-between">
                            <h4 class="inline">{{ $question->user->username or 'null' }}</h4> <small class="align-right">{{ $question->created_at }}</small>
                        </div>
                        <div>
                            {!! $question->message !!}
                        </div>
                    </div>

                @endforeach
            @else
                <h2>Inga frågor ställda än </h2>
                <p>Var först med en fråga till säljaren!</p>
            @endif

            @if($market->preview)
                <p>Förhandsgranskning</p>
            @elseif(Auth::check())
                    {{--Form for sending question/answers--}}
            <!-- Form start here -->
                {!! Form::open(array('route' => 'markets.question', 'method' => 'post')) !!}
                    {!! Form::hidden('market', $market->id) !!}
                    {!! $errors->first('message', '<div class="help-block">:message</div>') !!}
                    {!! Form::textarea('message', null , ['class' => "form-input okgbb" ] ) !!}
                    <br/>
                    {!! Form::submit('Skicka', array('class' => 'btn')) !!}
                    {{--{!! Form::submit('Förhandsgranska', array('class' => 'btn-right')); !!}--}}
                {!! Form::close() !!}
            @else
                <a href="{{ route('accounts.login') }}" class="btn">Logga in för att skriva en kommentar.</a>
            @endif
        @endif

    </div>
@endif