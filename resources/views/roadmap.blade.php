@extends('layout/site')

@section('title')
	Roadmap
@stop

@section('content')
    <div class="inner-content">
        <h1>Roadmap</h1>
        <p>Tänkt utveckling för sidan, preliminär</p>
        <hr/>
        <p>
            Login<br />
            -> OAuth (facebook, twitter, google, linkedin)<br/>
            -> PhpBB<br/>
            -> Redirekt efter login tll önskad sida<br/>
            -> Mail vid registrering mailview<br/>
            -> felmeddelanden, mail, användarnamn finns redan<br/>
            -> Översättning i email för lösenordsåterställning<br/>
            -> CSRF validiation error efter felinloggning<br/>
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
            -> Mail, request, verify user exist and has allowed e-mail<br/>
            -> Mail, purify<br/>
            -> E-mail vid nytt pm, skapa mailview<br/>
        </p>
        <hr />
        <p>
            STOR designöversyn<br />
            -> Fixa HTAccess för att bli av med "index.php", även market/public<br />
            -> Sidvisning sökresultat<br />
            -> Spara kopia av annons i separat db-table vid updates<br />
            -> Visa senaste ändring/antal ändringar av annons<br />
            -> Valideringar av annonsmodel (inkluderar filtyp img vid uppladdning) (requests)<br />
            -> Less/Elixir etc för att bundla/minifiera script och css
            -> Kompatipel med IE6/7/8 etc (modernizr?)<br/>
            -> Responsiv<br/>
            -> BB Code responsiv<br/>
            -> Meddelandefält, design???<br/>
            -> Notifiering vid nytt meddelande<br/>
            -> Klocka i menyraden med servertiden...<br/>
            -> Förhandsvisning marketQuestion<br/>
            -> Publik profilsida (obs script injection)<br/>
            -> Lägg till meddelande 'inställningar sparade' efter uppdatering av inställningar<br/>
            -> Lightbox okg
            -> Visa forumkopplingar i profil<br/>
            -> CSRF Token mismatch vid inloggning efter ett misslyckat<br/>
            -> Divs inner content för snyggare visning<br/>
            -> Styling chackboxes???<br/>
            -> Aktiveringslänk för att bekräfta mail vid registrering<br/>
            -> Visningsalternativ (spara i cockies)<br/>
            -> Aktiva annonser, marketmenu blir bara större och större</br>
            -> Menyn döljs av bilder vid visning av enskild annons (Sparatn?)<br/>
        </p>
        <hr/>
        <p>
            Annonser<br />
            -> Fixa annonsvisningmeny vid lång annons<br/>
            -> Liten annons, fyll ut hela fönstret så att hela menyer etc visas<br/>
            -> Använd Font-awsame<br/>
            -> Validation, minst ett kontaktsätt måste vara ifyllt samt godkänt i profilen vid skapande/ändrande av annons<br/>
            -> Paginering
        </p>
        <hr />
        <p>
            STOR Säkerhetsgenomgång<br/>
            -> Logga ip för annons, inlägg, pm etc.<br/>
            -> Historik för annonser<br/>
            -> HtAccess, Blockera mååånga filer
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
    </div>
@stop