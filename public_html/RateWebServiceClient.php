<?php
// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0
//echo "hello!";
ini_set("display_errors","1");

require_once($_SERVER['DOCUMENT_ROOT'].'/fedex/library/fedex-common.php');

global $state;
global $surcharge;

function convertToPounds($w){
 $w=$w*.0685714286;
 return $w;
 
}

$shippingMethods=array(
		0=>'GROUND_HOME_DELIVERY',
		1=>'FEDEX_EXPRESS_SAVER',
		2=>'FEDEX_2_DAY',
		3=>'STANDARD_OVERNIGHT'
		);
/*		
$shippingMethods=array('Ground Shipping (1-5 Business Days)'=>'GROUND_HOME_DELIVERY',
		'3 Day Shipping'=>'FEDEX_EXPRESS_SAVER',
		'2 Day Express' =>'FEDEX_2_DAY',
		'Next Day Air'=>'STANDARD_OVERNIGHT');
*/
		
$defaultCharges=array(
		'Ground Shipping (1-5 Business Days)'=>4,
		'3 Day Shipping'=>10,
		'2 Day Express' =>27,
		'Next Day Air'=>53
		);

$defaultsurcharge=array(
		4,
		10,
		27,
		53
		);

function convertTransitTimes($t){
	
	$find=array(
			"ONE",
			"TWO",
			"THREE",
			"FOUR",
			"FIVE",
			"SIX",
			"SEVEN",
			"EIGHT",
			"NINE",
			"TEN"
			);
	$replace=array(1,2,3,4,5,6,7,8,9,10);
	
	$t=substr($t,0,-5);
	$t=str_replace($find, $replace,$t);
	
	return $t;
}


$zipcodes=array(
'99500-99999'=>'AK',
'35000-36999'=>'AL',
'71600-72999'=>'AR',
'85000-86599'=>'AZ',
'90000-96199'=>'CA',
'80000-81699'=>'CO',
'06800-06999'=>'CT',
'20001-20599'=>'DC',
'19700-19999'=>'DE',
'32100-34999'=>'FL',
'30000-31999'=>'GA',
'96700-96899'=>'HI',
'50000-52899'=>'IA',
'83200-83899'=>'ID',
'60000-62999'=>'IL',
'83200-83899'=>'IO',
'46000-47999'=>'IN',
'66000-64799'=>'KS',
'40000-42799'=>'KY',
'70000-71499'=>'LA',
'01000-02799'=>'MA',
'20600-21999'=>'MD',
'03000-04999'=>'ME',
'48000-49799'=>'MI',
'55000-56799'=>'MN',
'63000-65899'=>'MO',
'38600-39599'=>'MS',
'59000-59999'=>'MT',
'27000-28999'=>'NC',
'58000-58899'=>'ND',
'68000-69399'=>'NE',
'03000-03899'=>'NH',
'07000-08999'=>'NJ',
'87000-88499'=>'NM',
'89000-89899'=>'NV',
'10000-14999'=>'NY',
'43000-45899'=>'OH',
'73000-74999'=>'OK',
'97000-97999'=>'OR',
'15000-16999'=>'PA',
'00600-00799'=>'PR',
'02800-02999'=>'RI',
'29000-29999'=>'SC',
'57000-57799'=>'SD',
'37000-35899'=>'TN',
'75000-79999'=>'TX',
'84000-84799'=>'UT',
'20040-24658'=>'VA',
'5001-5907'=>'VT',
'98001-99403'=>'WA',
'53001-54990'=>'WI',
'24701-26886'=>'WV',
'82001-83128'=>'WY');



function getCountryFromState($s){

}

function getStateFromZip($z){
	global $zipcodes;
	foreach($zipcodes as $k=>$v){
		$range=explode("-", $k);
		if($z>=$range[0] && $z<=$range[1]){
			return $v;
			exit();
		}
	}
}
	
$newline = "<br />";
//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = $_SERVER['DOCUMENT_ROOT']."/fedex/wsdl/RateService_v14.wsdl";

ini_set("soap.wsdl_cache_enabled", "0");
 
$client = new SoapClient($path_to_wsdl, array('trace' => 1));
// Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' =>array(
		'Key' => getProperty('key'), 
		'Password' => getProperty('password')
	)
); 

$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), 
	'MeterNumber' => getProperty('meter')
);

$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v14 using PHP ***');

$request['Version'] = array(
	'ServiceId' => 'crs', 
	'Major' => '14', 
	'Intermediate' => '0', 
	'Minor' => '0'
);

$request['ReturnTransitAndCommit'] = true;
$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = date('c');

$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING';
// valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...

$request['RequestedShipment']['TotalInsuredValue']=array(
	'Amount'=>1000,
	'Currency'=>'USD'
);

extract($_GET);
extract($_POST);


$weight=convertToPounds($weight);

$request['RequestedShipment']['Shipper'] = addShipper();
$request['RequestedShipment']['Recipient'] = addRecipient($zip);
$request['RequestedShipment']['ShippingChargesPayment'] = addShippingChargesPayment();
$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['RequestedPackageLineItems'] = addPackageLineItem1($weight);

try {
	if(setEndpoint('changeEndpoint')){
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
//echo "Package weight (lbs): $weight<br>
 //Destination: $zip, $state:<br>";

$shipSelect='<select name="shippingMethod" class="medium-input" id="shipping-method">';
$i=0;
foreach($shippingMethods as $k=>$v){

$request['RequestedShipment']['ServiceType'] = $v;

$response = $client -> getRates($request);
        
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR'){  	
    	$rateReply = $response -> RateReplyDetails;
    	 //'<table border="1">';
        //echo '<tr><td>Service Type</td><td>Amount</td><td>Delivery Date</td></tr><tr>';
    	//$serviceType = '<td>'.$rateReply -> ServiceType . '</td>';
        $amount=number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,0,".",",");
        $surcharge[$i]=$amount;
        //$options.="<option value='$amount'>$k - $$amount.00 ";
        
       // $amount = '<td>$' . number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") . '</td>';
        if(array_key_exists('DeliveryTimestamp',$rateReply)){
        	$deliveryDate= 'Estimated delivery date: ' .$rateReply->DeliveryTimestamp;
        }else if(array_key_exists('TransitTime',$rateReply)){
         
         $deliveryDate=$rateReply->TransitTime;
         $deliveryDate=convertTransitTimes($deliveryDate);
         $deliveryDate=date("m/d/Y",mktime(0,0,0,date("m"),date("d")+1+$deliveryDate,date("Y")));
         $deliveryDate= 'Estimated delivery date: '. $deliveryDate;
        	
        }else {
        	$deliveryDate='<td>&nbsp;</td>';
        }
        //echo $serviceType . $amount. $deliveryDate;
        //echo '</tr>';
        //echo '</table>';
        
        //printSuccess($client, $response);
       // $options.=$deliveryDate."</option>";
	   $options.="</option>";
    }else{
    	//get error code
    	 $errorCode= $response->Notifications->Code;
    	 //echo "Request failed, error $errorCode";
		// echo $errors->Code;
    	 //printError($client, $response);
    	$surcharge[$i]=$defaultsurcharge[$i];
    	//$options.="<option value='$defaultCharges[$k]'>Default $k - $$defaultCharges[$k].00</option>\n";
       // printError($client, $response);
    } 
    
  $i++;
  
   
 writeToLog($client);    // Write to log file   

}
 
	//$shipSelect.=$options.'</select>';	
 //print_r($surcharge);
 //echo $shipSelect;
 //print_r($surcharge);
 

} catch (SoapFault $exception) {
  // printFault($exception, $client);        
}



function addShipper(){
	$shipper = array(
		'Contact' => array(
			'PersonName' => 'Duncan Cox',
			'CompanyName' => 'As You Like It Silver Shop',
			'PhoneNumber' => '5048976915'
		),
		'Address' => array(
			'StreetLines' => array('3033 Magazine St'),
			'City' => 'New Orleans',
			'StateOrProvinceCode' => 'LA',
			'PostalCode' => '70115',
			'CountryCode' => 'US'
		)
	);
	return $shipper;
}

function addRecipient($zipcode=''){

global $state;

$state=getStateFromZip($zipcode);

	$recipient = array(
		'Contact' => array(
			'PersonName' => 'Recipient Name',
			'CompanyName' => 'Company Name',
			'PhoneNumber' => '9012637906'
		),
		
		'Address' => array(
			'StreetLines' => array(''),
			'City' => '',
			'StateOrProvinceCode' => $state,
			'PostalCode' => $zipcode,
			'CountryCode' => 'US',
			'Residential' => true
		)
	);
	
	return $recipient;	                                    
}

function addShippingChargesPayment(){
	$shippingChargesPayment = array(
		'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
		'Payor' => array(
			'ResponsibleParty' => array(
				'AccountNumber' => getProperty('billaccount'),
				'CountryCode' => 'US'
			)
		)
	);
	return $shippingChargesPayment;
}

function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75'
	);
	return $labelSpecification;
}

function addSpecialServices(){
	$specialServices = array(
		'SpecialServiceTypes' => array('COD'),
		'CodDetail' => array(
			'CodCollectionAmount' => array(
				'Currency' => 'USD', 
				'Amount' => 150
			),
			'CollectionType' => 'ANY' // ANY, GUARANTEED_FUNDS
		)
	);
	return $specialServices; 
}

function addPackageLineItem1($weight=1){
	$packageLineItem = array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => $weight,
			'Units' => 'LB'
		),
		'Dimensions' => array(
			'Length' => 5,
			'Width' => 5,
			'Height' => 5,
			'Units' => 'IN'
		)
	);
	return $packageLineItem;
}
?>