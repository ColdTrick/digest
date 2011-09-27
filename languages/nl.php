<?php 

	$english = array(
		'digest' => "Updates",
	
		// init
		'digest:init:plugin_required:html_email_handler' => "Deze plugin benodigd een andere plugin 'html_email_handler', schakel deze eerst in!",
	
		// digest intervals
		'digest:interval:none' => "Uitgeschakeld",
		'digest:interval:default' => "Gebruik de groepsvoorkeur (huidig: %s)",
		'digest:interval:daily' => "Dagelijks",
		'digest:interval:weekly' => "Wekelijks",
		'digest:interval:fortnightly' => "2 wekelijks",
		'digest:interval:monthly' => "Maandelijks",
	
		'digest:interval:error' => "Ongeldige interval",
	
		// readable time
		'digest:readable:time:seconds' => "sec",
		'digest:readable:time:minutes' => "min",
		'digest:readable:time:hours' => "uur",
		
		// menu items
		'digest:submenu:usersettings' => "Update instellingen",
		'digest:submenu:groupsettings' => "Update instellingen",
		'digest:submenu:analysis' => "Updates statistieken",
		
		// admin settings
		'digest:settings:interval:title' => "Updates interval instellingen",
		'digest:settings:interval:site_default' => "De standaard instelling voor de site updates",
		'digest:settings:interval:group_default' => "De standaard instelling voor de groep updates",
		'digest:settings:interval:description' => "Door het instellen van een standaard waarde zullen alle gebruikers die geen frequentie hebben ingesteld een update ontvangen op deze standaard interval.<br /><br /><b>WAARSCHUWING:</b> Dit kan natuurlijk vele mailings versturen.",
		
		'digest:settings:never:title' => "Nooit aangemelde gebruikers",
		'digest:settings:never:include' => "Moeten ook gebruikers die nog nooit zijn aangemeld een update ontvangen?",
		
		// usersettings
		'digest:usersettings:title' => "Persoonlijk Updates instellingen",
		'digest:usersettings:error:user' => "Je hebt geen toegang tot de update instellingen van deze gebruiker",
		
		'digest:usersettings:site:title' => "Site Updates instellingen",
		'digest:usersettings:site:description' => "De Site Update zal je informeren over recente activiteit op site niveau.",
		'digest:usersettings:site:setting' => "Hoe vaak wil je een Site Update ontvangen?",
		
		'digest:usersettings:groups:title' => "Groep Update instellingen",
		'digest:usersettings:groups:description' => "De Groep Update zal je informeren over recente activiteit binnen de geselecteerde groep.",
		'digest:usersettings:groups:group_header' => "Groepsnaam",
		'digest:usersettings:groups:setting_header' => "Frequentie",
		
		// user group setting
		'digest:usersettings:group:setting' => "Je huidige update instelling voor deze groep is",
		'digest:usersettings:group:more' => "Alle updates instellingen",
		
		// group settings
		'digest:groupsettings:title' => "Groep Updates instellingen",
		'digest:groupsettings:description' => "Met welke frequentie wil je dat je groepsleden een update van de activiteit in deze groep ontvangen? Leden kunnen in hun persoonlijk instellingen wel een andere frequentie instellen.",
		'digest:groupsettings:setting' => "Groep Update instelling",
		
		// layout
		'digest:layout:footer:info' => "Deze mail komt van %s omdat je op de hoogte wilt worden gehouden van activiteiten op deze site.",
		'digest:layout:footer:update' => "Pas je aflever instellingen aan",	
		'digest:layout:footer:unsubscribe' => "Klik hier om je direct af te melden van deze update",
	
		// show a digest online
		'digest:show:error:input' => "Incorrecte invoer om de update te bekijken",
		'digest:show:no_data' => "Geen informatie gevonden voor deze interval",
	
		// message body
		'digest:message:title:site' => "%s: %s update",
		'digest:message:title:group' => "%s - %s: %s update",
	
		'digest:message:online' => "Indien je deze mail niet kunt lezen, kun je hem hier online bekijken.",
		
		// admin analysis
		'digest:analysis:title' => "Updates Statistieken",
		
		'digest:analysis:last_run' => "Laatste keer",
		'digest:analysis:current' => "Huidig",
		'digest_analysis:predict' => "Voorspelling",
		
		'digest:analysis:site:title' => "Site Update",
		'digest:analysis:site:members' => "Leden",
		'digest:analysis:site:avg_memory' => "Avg memory",
		'digest:analysis:site:total_memory' => "Total memory",
		'digest:analysis:site:avg_run_time' => "Avg run time",
		'digest:analysis:site:run_time' => "Total run time",
		'digest:analysis:site:send' => "Verzonden updates",
		
		'digest:analysis:group:title' => "Groep Update",
		'digest:analysis:group:count' => "Groepen op site",
		'digest:analysis:group:total_members' => "Totaal # groepsleden",
		'digest:analysis:group:avg_members' => "Avg members per group",
		'digest:analysis:group:avg_members_memory' => "Avg memory per member",
		'digest:analysis:group:total_members_memory' => "Total memory for members",
		'digest:analysis:group:avg_memory' => "Avg memory per group",
		'digest:analysis:group:total_memory' => "Total memory",
		'digest:analysis:group:avg_run_time' => "Avg run time per group",
		'digest:analysis:group:run_time' => "Total run time",
		'digest:analysis:group:send' => "Verzonden updates",
	
		// register
		'digest:register:enable' => "Ik wil een site update ontvangen",
	
		// unsubscribe
		'digest:unsubscribe:error:input' => "Onjuiste invoer om je af te kunnen melden van updates",
		'digest:unsubscribe:error:code' => "De opgegeven validatie code is onjuist",
		'digest:unsubscribe:error:save' => "Er is een onbekende fout opgetreden tijdens het uitschrijven van de udpate",
		'digest:unsubscribe:success' => "Je heb je succesvol afgemeld van de update",
	
		// actions
		// update usersettings
		'digest:action:update:usersettings:error:input' => "Ongeldige invoer voor het opslaan van de updates instellingen",
		'digest:action:update:usersettings:error:user' => "De opgegeven GUID is geen gebruiker",
		'digest:action:update:usersettings:error:unknown' => "Een onbekend probleem is opgetreden tijdens het opslaan van je updates instellingen",
		'digest:action:update:usersettings:success' => "Updates instellingen succesvol aangepast",
		
		// update groupsettings
		'digest:action:update:groupsettings:error:input' => "Ongeldige invoer om de groeps updates aan te passen",
		'digest:action:update:groupsettings:error:entity' => "De opgegeven GUID is geen groep",
		'digest:action:update:groupsettings:error:can_edit' => "Het is je niet toegestaan deze groep te bewerken",
		'digest:action:update:groupsettings:error:save' => "Een onbekend probleem is opgetreden tijdens het opslaan van de groeps updates instellingen",
		'digest:action:update:groupsettings:success' => "Updates instellingen succesvol aangepast",
		
		// send digest mail
		'digest:mail:plaintext:description' => "Je email client ondersteunt geen HTML mails. 
		
		Om de site updates toch te kunnen lezen kun je de volgende link gebruiken: %.",
		'' => "",
		'' => "",
		'' => "",
	
	
	);
	
	add_translation("nl", $english);

?>