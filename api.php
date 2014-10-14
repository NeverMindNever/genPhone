<?php
require_once('/usr/bin/vendor/autoload.php');
require_once('countries.php');
use \libphonenumber\PhoneNumberType;

function genPhoneNumberByCountry($country)
{
	
	//Create an instance of libphoneNumberUtils
	$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
	//Generate an example of the selected country
	$exampleNumber=$phoneUtil->getExampleNumber($country);
	//Find the country code it will be used to construct random numbers
	$countryCode = $phoneUtil->getCountryCodeForRegion($country);
	//Find the length of the example number to be able to construct
	$significantNumber = $phoneUtil->getNationalSignificantNumber($exampleNumber);
	
	do {
		//Generate A random number in the field of the example number using the power of the number 10
		$power=strlen($significantNumber);
		$generatedNumberStr="+".$countryCode."".rand(pow(10,$power-1), pow(10,$power));
		//Create an object from the string
		$generatedNumber=$phoneUtil->parse($generatedNumberStr, $country);
		//Validate that the number is Valid
		$isGeneratedNumberValid = $phoneUtil->isValidNumber($generatedNumber);
	} while((!$phoneUtil->isValidNumber($generatedNumber)) ); //Repeat untill it found a valid number
	return $generatedNumber; //returning the object to manipulate more if needed
}

function genPhoneNumberByCountryAndType($country, $type)
{
	//Create an instance of libphoneNumberUtils
	$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
	$tries=0;
	do {
		$generatedNumber = genPhoneNumberByCountry($country);	
		$tries++;
	} while($phoneUtil->getNumberType($generatedNumber)!=$type and $tries<100 ); //Repeat untill it found the type needed
	if($tries==100)
	return null;
	
	return $generatedNumber; //returning the object to manipulate more if needed
}

?>