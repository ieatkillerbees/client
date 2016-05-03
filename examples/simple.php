<?php

require __DIR__ . "/../vendor/autoload.php";

use GuzzleHttp\Client;
use Jason\Session;
use Jason\Page;

$client = new Client();

$session = Session::create($client);

$page = Page::create($client, $session);

print "body: " . $page
    ->visit("http://assertchris.io")
    ->run("function() { document.write('hello world'); return 'success'; }")
    ->body;

print "\n";

print "returned: " . $page->returned;
