<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_PlusService.php';

$client = new Google_Client();
$client->setDeveloperKey('AIzaSyAlimP1duVdbwWoJRnb7IEs1mMuKiED52U');
$plus = new Google_PlusService($client);

$activities = $plus->activities->search("whisky");
foreach($activities['items'] as $result) {
  print ">>> {$result['title']}\n";
}