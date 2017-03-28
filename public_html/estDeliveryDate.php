<?php
session_name('checkout');
session_start();

extract($_POST);

$deliveryMSG=json_decode($_SESSION['estDeliveryDates']);

echo $deliveryMSG[$smethod];

?>