<?php
	require_once('vendor/autoload.php');
	require_once('config/db.php');
	require_once('lib/pdo_db.php');
	require_once('models/Customer.php');

	\Stripe\Stripe::setApiKey('sk_test_F7eqnqU5v0VYykwxn0SdObEm');

// Sanitize Post Array
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

$first_name = $POST['first_name'];
$last_name = $POST['last_name'];
$email = $POST['email'];
$token = $POST['stripeToken'];

// Create Customer In Stripe
$customer = \Stripe\Customer::create(array(
	"email" => $email,
	"source" => $token
));

// Charge Customer
$charge = \Stripe\Charge::create(array(
	"amount" => 5000,
	"currency" => "usd",
	"description" => "Intro to React Course",
	"customer" => $customer -> id
));

// Customer Data
$customerData = [
	'id' => $charge->customer,
	'first_name' => $first_name,
	'last_name' => $last_name,
	'email' => $email
];

// Instantiate Customer
$customer = new Customer();

// Add Customer To DB
$customer->addCustomer($customerData);


// Redirect To Success Page
header('Location: success.php?tid='.$charge->id.'&product='.$charge->description);