@extends('layout/site')

@section('title')
	Roadmap
@stop

@section('content')
	<h1>Roadmap</h1>
	<p>Tänkt utveckling för sidan, preliminär</p>
	<hr/>
	<p>
		Annonser<br />
		-> Fixa annonsvisningmeny vid lång annons<br/>
        -> Liten annons, fyll ut hela fönstret så att hela menyer etc visas<br/>
        -> Använd Font-awsame<br/>
        -> Validation, minst ett kontaktsätt måste vara ifyllt samt godkänt i profilen<br/>
        -> Paginering
	</p>
    <hr />
    <p>
        Sida för användaren att ändra sina uppgifter<br />
    </p>
    <hr/>
    <p>
        Login<br />
        -> Glömt lösenord <br />
        -> Allmänna varningsmeddelanden, meddelanden etc<br/>
        -> Redirect back<br/>
        -> OAuth (facebook, twitter, google, linkedin)<br/>
        -> PhpBB<br/>
        -> Redirekt efter login tll önskad sida<br/>
        -> Mail vid registrering <br/>
    </p>
    <hr />
    <p>
        Meddelanden<br/>
        -> Visning antal ny pm i menyrad, helper?<br/>
        -> Visning i inkorgen, konversationspartner, antal nya Pm etc<br/>
        -> Paginering både inbox och meddelanden<br/>
        -> Inboxen, sortera på nysate först.<br/>
        -> Ta bort markeringara för nytt inlägg i show<br/>
        -> Maila användaren vid händelser såsom registrering, glömt lösenord, nytt meddelande etc.<br/>
        -> Inställningar i användarprofilen för notifieringar<br/>
        -> Mail, request, verify user exist and has allowed e-mail
        -> Mail, purify
    </p>
	<hr />
	<p>
		STOR designöversyn<br />
        -> SEO friendly paths<br />
		-> Regex? priser 1:- istället för 1.00, ta bort decimalerna<br />
		-> Radbrytningar i text från db, spara radbrytningar etc i db<br />
		-> Fixa HTAccess för att bli av med "index.php", även market/public<br />
		-> HTML input encoding to prevent attacks<br />
		-> Sidvisning sökresultat<br />
		-> Spara kopia av annons i separat db-table vid updates<br />
		-> Visa senaste ändring/antal ändringar av annons<br />
		-> Valideringar av annonsmodel (inkluderar filtyp img vid uppladdning) (requests)<br />
        -> Less/Elixir etc för att bundla/minifiera script och css
		-> Error messages in forms<br />
		-> Datumvisning, använd plugin typ Carbon???<br/>
        -> Kompatipel med IE6/7/8 etc<br/>
        -> Responsiv<br/>
        -> BB Code responsiv<br/>
        -> Meddelandefält, design???<br/>
        -> Notifiering vid nytt meddelande<br/>
        -> Klocka i menyraden med servertiden...<br/>
        -> Förhandsvisning marketQuestion<br/>
        -> Market validation, minst ett kontaktsätt måste vara valt
    </p>
    <hr/>
    <p>
	    STOR Säkerhetsgenomgång<br/>
        -> Logga ip för annons, inlägg, pm etc.<br/>
        -> Historik för annonser<br/>
	</p>
	<hr/>
	<p>
        Fler annonstyper<br />
        -> Auktion <br />
        -> -> Auktionsscript <br />
        -> -> Ingen editering efter första köp/bud, bara tillägg<br/>
        -> -> +5 min vid auktion, obligatoriskt<br/>
        -> Samköp <br />
        -> -> ??? <br />
        -> Tjänst/jobb <br />
        -> -> Sökes/erbjudes/tipsas/etc <br />
        -> Auktion Eller Annons, inte båda
    </p>
    <hr />
    <p>
        Filter/sökfunktion<br />
        -> Avancerad sökning ??? <br />
        -> Visa dolda annonser/säljare <br />
        -> Sidvisning sökresultat, antal per sida etc<br />
        -> Olika visningsalternativ<br />
        -> Möjlighet att söka med flera ord som inte behöver finnas i föld<br />
    </p>
    <hr />
	<p>
		Review-system<br />
		-> Visa samlat betyg för säljare<br />
		-> Lägg till omdöme för köpt vara/allmänt?
	</p>
	<hr/>
	<p>
		Admin sida<br />
		-> Svartlista användare<br/>
		-> Radera annonser helt (Flytta till dold db tabell)<br/>
		-> Återställa radera annonser (från dold db)<br />
        -> Ändra annonser<br/>
	</p>
	<hr/>
	<p>
		Performance<br />
		-> Eager loading where applicable??<br />
		-> Kolla antal sql frågor vid respektive fråga
	</p>
    <hr/>
	<p>
		API för externa verktyg att ladda upp och hantera annonser.<br/>
	</p>
    <hr/>
    <p>
        Footer, Using:<br/>
        -> Laravel<br/>
        -> Intervention/Image<br/>
        -> hammerjs/hammer.js<br/>
        -> mews/purifier<br/>
        -> jbbcode<br/>
    </p>
	
@stop