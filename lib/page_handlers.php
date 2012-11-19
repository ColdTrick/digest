<?php

	function digest_page_handler($page){
	
		switch($page[0]){
			case "test":
				include(dirname(dirname(__FILE__)) . "/pages/test.php");
				break;
			case "show":
				include(dirname(dirname(__FILE__)) . "/pages/show.php");
				break;
			case "unsubscribe":
				include(dirname(dirname(__FILE__)) . "/procedures/unsubscribe.php");
				break;
			case "user":
			default:
				if(!empty($page[1])){
					set_input("username", $page[1]);
				}
				include(dirname(dirname(__FILE__)) . "/pages/usersettings.php");
				break;
		}
	
		return true;
	}