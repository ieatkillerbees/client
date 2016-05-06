<?php

require __DIR__ . "/../vendor/autoload.php";

use Undemanding\Client\Session;
use Undemanding\Client\Page;

$client = new GuzzleHttp\Client(["base_uri" => "http://localhost:4321"]);

$session = Session::create($client);
$page = Page::create($client, $session);

print $page->visit("http://localhost:5432")
    ->run("document.body.innerHTML = 'hello chris'")
    ->resize(1024, 768)
    ->zoom(2)
    ->preview()
    ->body();