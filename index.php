<?php

include 'api.php';

use \libphonenumber\PhoneNumberType;

//Create and bind the DI to the application
$app = new \Phalcon\Mvc\Micro();

//Generate a phone number from country code
$app->get('/country/{cc}', function($cc) use ($app) {

	$cc 	=	strtoupper($cc);

	if(!Country::isValid($cc))
	{
		$number 	= "invalid";
		$country 	= "invalid";
	}
	else
	{
		if(Country::isValidValue($cc))
		{$cc			= 	Country::getCode($cc);}
		$number		= (string)genPhoneNumberByCountry($cc);
		$country 	=  Country::getName($cc);
	}
	$data = array(
		'cc' 		=> $cc ,
		'country' 	=> $country ,
		'number'	=> $number
	);

    echo json_encode($data);

});

//Generate a phone number from country code with specific Type
$app->get('/country/{cc}/type/{type}', function($cc, $type) use ($app) {

	$cc 	=	strtoupper($cc);
	$type	=	strtoupper($type);  
	
	if(!((Country::isValid($cc)) and PhoneNumberType::isValidName($type)))
	{
		$number 	= "invalid ";
		$country 	= "invalid";
		$type 		= "invalid";
	}
	else
	{
		if(Country::isValidValue($cc))
		{$cc			= 	Country::getCode($cc);}
		$number		= (string)genPhoneNumberByCountryAndType($cc,PhoneNumberType::valueOf($type));
		$country 	=  Country::getName($cc);	
	}
	
	$data = array(
		'cc' 		=> $cc ,
		'country' 	=> $country ,
		'number'	=> $number,
		'type'		=> $type
	);

    echo json_encode($data);

});

//return Version number
$app->get('/version', function() use ($app) {

        $data = array(
            'version' => "1.0.0",
        );

    echo json_encode($data);

});


$app->handle();