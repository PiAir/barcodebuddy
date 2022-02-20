<?php

require __DIR__ .'/../../vendor/autoload.php';

$request = new HTTP_Request2();
$observer = new HTTP_Request2_Observer_Log();
$request->attach($observer);
$request->setUrl('https://mobileapi.jumbo.com/v12/search?q=8718452117406');
$request->setMethod(HTTP_Request2::METHOD_GET);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Host' => 'mobileapi.jumbo.com',
    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0'
));
$request->setBody('');
try {
  $response = $request->send();
  if ($response->getStatus() == 200) {
    echo $response->getBody();
  }
  else {
    echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
    $response->getReasonPhrase();
  }
}
catch(HTTP_Request2_Exception $e) {
  echo 'Error: ' . $e->getMessage();
}

?>