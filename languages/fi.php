<?php 

return [
	'digest' => "Uutiskirje",
	
	// digest intervals
	'digest:interval:none' => "Ei koskaan",
	'digest:interval:default' => "Ryhmän oletusasetus (%s)",
	'digest:interval:daily' => "Kerran päivässä",
	'digest:interval:weekly' => "Kerran viikossa",
	'digest:interval:fortnightly' => "Kahden viikon välein",
	'digest:interval:monthly' => "Kerran kuussa",
	
	'digest:distribution' => "Ajankohta",
	'digest:distribution:distributed' => "Hajautettu",
	'digest:distribution:description' => "Valitse lähetysajankohta. Hajautettu lähetys generoi lähetysajan käyttäjäkohtaisesti, ja estää käyttäjiä määrittämästä ajankohtaa itse.",
	
	'digest:day:sunday' => "Sunnuntai",
	'digest:day:monday' => "Maanantai",
	'digest:day:tuesday' => "Tiistai",
	'digest:day:wednesday' => "Keskiviikko",
	'digest:day:thursday' => "Torstai",
	'digest:day:friday' => "Perjantai",
	'digest:day:saturday' => "Lauantai",
	
	'digest:interval:error' => "Virheellinen aikaväli",
	
	// readable time
	'digest:readable:time:mseconds' => "millisekuntia",
	'digest:readable:time:seconds' => "sekuntia",
	'digest:readable:time:minutes' => "minuuttia",
	'digest:readable:time:hours' => "tuntia",
	
	// menu items
	'digest:page_menu:settings' => "Uutiskirje",
	'digest:page_menu:theme_preview' => "Uutiskirjeen esikatselu",
	'digest:submenu:groupsettings' => "Uutiskirjeen asetukset",
	'admin:statistics:digest' => "Uutiskirjeen tilastot",
	
	// admin settings
	'digest:settings:notice' => "<b>VAROITUS:</b> Uutiskirje saattaa lähettää huomattavia määriä sähköpostia.",
	
	'digest:settings:production' => "Tuotannossa",
	'digest:settings:production:description' => "Uutiskirje saattaa lähettää huomattavia määriä sähköpostia. Tämän asetuksen avulla voit ottaa sen väliaikaisesti pois päältä esimerkiksi testaamisen ajaksi.",
	'digest:settings:production:option' => "Ota uutiskirje käyttöön",
	'digest:settings:production:group_option' => "Ota käyttöön ryhmien uutiskirje",
	
	'digest:settings:interval:title' => "Uutiskirjeen lähetysaikaväli",
	'digest:settings:interval:default' => "Oletusaikaväli",
	'digest:settings:interval:site_default' => "Uutiskirjeen oltusaikaväli",
	'digest:settings:interval:group_default' => "Ryhmien uutiskirjeen oletusaikaväli",
	'digest:settings:interval:description' => "Oletusaikaväliä käytetään, jos käyttäjä ei ole määrittänyt aikaväliä henkilökohtaisista asetuksistaan",
	
	'digest:settings:never:title' => "Passiiviset käyttäjät",
	'digest:settings:never:include' => "Lähetetäänkö uutiskirje käyttäjille, jotka eivät ole koskaan kirjautuneet sivustolle?",
	
	'digest:settings:custom_text:title' => "Räätälöity sisältö",
	'digest:settings:custom_text:description' => "Tämän avulla voit määrittää uutiskirjeille ylä- tai alatunnisteen.",
	'digest:settings:custom_text:site:header' => "Ylätunniste",
	'digest:settings:custom_text:site:footer' => "Alatunniste",
	'digest:settings:custom_text:group:header' => "Ryhmien uutiskirjeen ylätunniste",
	'digest:settings:custom_text:group:footer' => "Ryhmien uutiskirjeen alatunniste",
	
	'digest:settings:multi_core:title' => "Tuki usealle prosessoriytimelle",
	'digest:settings:multi_core:description' => "Suurilla sivustoilla (yli 5000 käyttäjää) on kannattavaa jakaa viestien lähetys usealle prosessoriytimelle. Älä syötä todellista suurempaa arvoa, sillä tämä vain hidastaa uutiskirjeiden lähettämistä.",
	'digest:settings:multi_core:warning' => "Älä muuta tätä asetusta, jos et tiedä mitä useiden ytimien käyttö tarkoittaa, tai sivustollasi on alle 5000 käyttäjää.",
	'digest:settings:multi_core:number' => "Valitse käytettävien prosessoriytimien määrä",
	
	'digest:settings:stats:title' => "Tilastojen asetukset",
	'digest:settings:stats:reset' => "Nollaa kaikki tilastot",
	
	// usersettings
	'digest:usersettings:title' => "Uutiskirjeen asetukset",
	'digest:usersettings:error:user' => "Sinulla ei ole oikeuksia muuttaa tämän käyttäjän uutiskirjeen asetuksia",
	'digest:usersettings:no_settings' => "Käytössä ei ole toimintoja, jotka sisältävät asetuksia.",
	
	'digest:usersettings:site:title' => "Uutiskirje",
	'digest:usersettings:site:description' => "Sivustonlaajuinen uutiskirje kertoo sivuston viimeisimmistä tapahtumista kuten uusista blogeista, ryhmistä ja käyttäjistä.",
	'digest:usersettings:site:setting' => "Kuinka usein haluat vastaanottaa uutiskirjeen",
	
	'digest:usersettings:groups:title' => "Ryhmien uutiskirjeet",
	'digest:usersettings:groups:description' => "Ryhmän uutiskirje kertoo yksittäisen ryhmän viimeisimmät tapahtumat, joita voivat olla esimerkiksi uusi jäsen tai uusi ryhmäkeskustelu.",
	'digest:usersettings:groups:group_header' => "Ryhmä",
	'digest:usersettings:groups:setting_header' => "Aikaväli",
	
	// group settings
	'digest:groupsettings:title' => "Ryhmän uutiskirjeen asetukset",
	'digest:groupsettings:description' => "Kuinka usein haluat ryhmän jäsenten saavan ryhmän toiminnasta kertovan uutiskirjeen? Tämä on oletusasetus, jonka jäsenet voivat halutessaan muuttaa henkilökohtaisista asetuksistaan.",
	'digest:groupsettings:setting' => "Ryhmän uutiskirjeen lähetysväli",
	
	// layout
	'digest:elements:unsubscribe:info' => "Sait tämän sähköpostin, koska olet tilannut uutiskirjeen sivustolta %s.",
	'digest:elements:unsubscribe:settings' => "Voit muuttaa uutiskirjeen asetuksia %stäällä%s.",
	'digest:elements:unsubscribe:unsubscribe' => "Voit perua uutiskirjeen kokonaan klikkaamalla %stästä%s.",

	// show a digest online
	'digest:show:error:input' => "Virheelliset parametrit",
	'digest:show:no_data' => "Aikaväliltä ei löytynyt dataa",

	// message body
	'digest:message:title:site' => "%s: %s lähetettävä uutiskirje",
	'digest:message:title:group' => "%s - %s: %s lähetettävä uutiskirje",

	'digest:elements:online' => "Jos et voi lukea tätä sähköpostia, voit lukea uutiskirjeen myös %sselaimessa%s",
	
	// admin stats
	'digest:admin:stats:site:title' => "Sivuston uutiskirjeen tilastot",
	'digest:admin:stats:site:not_enabled' => "Sivuston uutiskirje ei ole käytössä",
	'digest:admin:stats:general:server_name' => "Uutiskirjeen lähettänyt palvelin",
	'digest:admin:stats:general:ts_start_cron' => "CRON-aloitusaika",
	'digest:admin:stats:general:mts_start_digest' => "Uutiskirjeen valmisteluun käytetty aika",
	'digest:admin:stats:general:peak_memory_start' => "Muistin käyttö ennen uutiskirjeen lähettämistä",
	'digest:admin:stats:general:peak_memory_end' => "Muistin käyttö uutiskirjeen lähettämisen jälkeen",
	'digest:admin:stats:general:mts_end_digest' => "Uutiskirjeen lähettämiseen kulunut aika",

	'digest:admin:stats:site:general:mts_user_selection_done' => "Käyttäjien hakemiseen käytetty aika",
	'digest:admin:stats:total_time' => "Kokonaisaika",
	'digest:admin:stats:total_memory' => "Muistivuodon määrä",
	'digest:admin:stats:not_collected' => "Tilastoja ei ole vielä kerätty",
	
	'digest:admin:stats:groups' => "Käsitellyt ryhmät",
	'digest:admin:stats:users' => "Käsitellyt käyttäjät",
	'digest:admin:stats:mails' => "Sähköpostien määrä",
	'digest:admin:stats:distribution:fortnightly' => "Joka toisen viikon %s",
	'digest:admin:stats:distribution:monthly' => "Kuun %s. päivä",
	
	'digest:admin:stats:group:title' => "Ryhmien uutiskirjeen tilatot",
	'digest:admin:stats:group:not_enabled' => "Ryhmien uutiskirje ei ole käytössä",
	'digest:admin:stats:group:general:mts_group_selection_done' => "Ryhmien hakemiseen käytetty aika",
	'digest:admin:stats:group:general:total_time_user_selection' => "Käyttäjien hakemiseen käytetty kokonaisaika",
	
	// register 
	'digest:register:enable' => "Haluan vastaanottaa sivuston uutiskirjeen",
	
	// unsubscribe
	'digest:unsubscribe:error:input' => "Pyyntö uutiskirjeen perumiseksi sisälsi virheellisiä tietoja",
	'digest:unsubscribe:error:code' => "Uutiskirjeen perumisessa tarvittava koodi oli virheellinen",
	'digest:unsubscribe:error:save' => "Uutiskirjeen perumisessa tapahtui tuntematon virhe",
	'digest:unsubscribe:success' => "Uutiskirjeen tilaus peruttu",
	
	// actions
	// update usersettings
	'digest:action:update:usersettings:error:unknown' => "Uutiskirjeen asetusten tallentamisessa tapahtui virhe",
	'digest:action:update:usersettings:success' => "Uutiskirjeen asetukset tallennettu",
	
	// update groupsettings
	'digest:action:update:groupsettings:error:save' => "Ryhmän uutiskirjeen asetusten tallentamisessa tapahtui virhe",
	'digest:action:update:groupsettings:success' => "Ryhmän uutiskirjeen asetukset tallennettu",
	
	// reset stats
	'digest:action:reset_stats:success' => "Tilastot nollattu",
	
	// send digest mail
	'digest:mail:plaintext:description' => "Sähköpostiohjelmasi pitää tukea HTML-sisältöä lukeaksesi uutiskirjeen.

Voit lukea uutiskirjeen myös selaimella osoitteessa: %s.",

	// command line script
	'digest:cli:error:secret' => "Koodi on virheellinen, joten uutiskirjettä ei voida lähettää",
];

