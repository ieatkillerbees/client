<?php

namespace Jason;

use GuzzleHttp\Client;

class TestCase extends PHPUnit_Framework_TestCase
{
    private $client;

    private $session;

    protected function visit($address)
    {
        $this->initPage();

        return $this->createPage($this->client, $this->session)->visit($address);
    }

    private function initPage()
    {
        if (!$this->client) {
            $this->client = $this->createClient();
        }

        if (!$this->session) {
            $this->session = $this->createSession();
        }
    }

    protected function run($script)
    {
        $this->initPage();

        return $this->createPage($this->client, $this->session)->run($address);
    }

    private function createClient()
    {
        return new Client();
    }

    private function createSession(Client $client)
    {
        return Session::create($client);
    }

    private function createPage(Client $client, Session $session)
    {
        return Page::create($client, $session);
    }
}
