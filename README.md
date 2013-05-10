GamesLT RSS
==========

Šio projektėlio idėja - sukurti papildomus RSS srautus [url=http://games.lt]Games.lt[/url] tinklalapiui, nes paprasčiausiai dabartiniams savininkams tai nėra įdomu, bet vartotojams taip.

Duomenys saugomi MySQL duomenų bazėje, kurios prisijungimai nurodomi include/constants.php failiuke. Visos reikiamos lentelės sukūriamos, rašant duomenis pirmą kartą.

Pagal nutylėjimą vykdomas RSS'o generavimas iš duomenų. Norint pasiimti duomenis reikia kreiptis per naršyklę į index.php?action=fetch .

Licenzija? Public Domain :)

Šiek tiek apie katalogus. libraries kataloge sudėtos visos bibliotekos iš trečių šalių. Joms galioja atskiros licenzijos, todėl pasidomėkite jomis patys. 
interfaces/ skirtas PHP interfeisų aprašymams. include/ - visi failai, kurie nėra aprašyti klasėmis ir nėra index.php :) fetchers/ - visi duomenų pasiėmėjai iš skirtingų šaltinių (paskui, jų gauti duomenis įrašomi į lentelę). class/ - kelios naudojamos klasės. action/ - veiksmai, kurios gali atlikti šis įrankis (kol kas yra rss bei fetch; pirmas parodo antras pasiima).
