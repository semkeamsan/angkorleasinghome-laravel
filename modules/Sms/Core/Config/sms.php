<?php
	return[
		'default' => env('SMS_DRIVER', ''),
		'nexmo'=>[
			'url'=>'https://rest.nexmo.com/sms/json',
			'from'=>env('SMS_NEXMO_FROM','Angkor Leasing'),
			'key'=>env('SMS_NEXMO_KEY',''),
			'secret'=>env('SMS_NEXMO_SECRET',''),
		],
		'twilio'=>[
			'url'=>'https://api.twilio.com',
			'from'=>env('SMS_TWILIO_FROM'),
			'sid'=>env('SMS_TWILIO_ACCOUNTSID',''),
			'token'=>env('SMS_TWILIO_TOKEN',''),
		],
	];
