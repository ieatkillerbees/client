<?php

require __DIR__ . "/../vendor/autoload.php";

use GuzzleHttp\Client;
use Jason\Session;
use Jason\Page;

$client = new Client(["base_uri" => "http://localhost:4321"]);

$session = Session::create($client);

$page = Page::create($client, $session);

print "body: " . $page
    ->visit("http://assertchris.io")
    ->run("document.write('hello world'); return 'success';")
    ->body;

print "\n";

print "returned: " . $page->returned;
