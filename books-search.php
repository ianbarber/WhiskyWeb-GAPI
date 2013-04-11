<?php
require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_BooksService.php';

$client = new Google_Client();
$client->setDeveloperKey('AIzaSyAlimP1duVdbwWoJRnb7IEs1mMuKiED52U');
$books = new Google_BooksService($client);

$volumes = $books->volumes->listVolumes("whiskies");
foreach($volumes['items'] as $result) {
  print "Search Result: {$result['volumeInfo']['title']}\n";
}