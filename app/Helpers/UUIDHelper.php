<?php

namespace App\Helpers;

use Webpatser\Uuid\Uuid;

class UUIDHelper {

	public static function generate(){
		return str_replace('-', '$', (string) Uuid::generate(5, str_random(60), Uuid::NS_DNS));
	}

}