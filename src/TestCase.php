<?php

namespace Jason;

use BadMethodCallException;
use GuzzleHttp\Client;
use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    private $client;

    private $session;

    private $pid;

    public function setUp()
    {
        parent::setUp();

        $address = sprintf(
            "http://%s:%s",
            getenv("JASON_THE_PHANTOM_HOST"),
            getenv("JASON_THE_PHANTOM_PORT")
        );

        if (!$address) {
            $address = $this->getJasonAddress();
        }

        if (!$this->exists($address)) {
            $path = getenv("JASON_THE_PHANTOM_PATH");

            if (!$path) {
                $path = $this->getBasePath();
            }

            $name = "jason-the-phantom";

            $script = sprintf(
                "%s/vendor/%s/phpunit/node_modules/%s/src/server.js",
                $path, $name, $name
            );

            $command = sprintf(
                "node %s > /dev/null & echo $!",
                $script
            );

            exec($command, $output);
            sleep(1);

            $this->pid = $output[0];
        }
    }

    private function exists($address) {
        $response = @file_get_contents($address);

        if (strpos($response, "Everything is ok!") !== false) {
            return true;
        }

        return false;
    }

    public function tearDown()
    {
        if ($this->pid) {
            $command = sprintf(
                "kill %s",
                $this->pid
            );

            exec($command);
        }

        parent::tearDown();
    }

    protected function visit($address)
    {
        $jasonAddress = sprintf(
            "http://%s:%s",
            getenv("JASON_THE_PHANTOM_HOST"),
            getenv("JASON_THE_PHANTOM_PORT")
        );

        if (!$jasonAddress) {
            $jasonAddress = $this->getJasonAddress();
        }

        if (!$this->client) {
            $this->client = $this->createClient($jasonAddress);
        }

        if (!$this->session) {
            $this->session = $this->createSession($this->client);
        }

        return $this->createPage($this->client, $this->session)->visit($address);
    }

    private function createClient($address)
    {
        return new Client(["base_uri" => $address]);
    }

    private function createSession(Client $client)
    {
        return Session::create($client);
    }

    private function createPage(Client $client, Session $session)
    {
        return Page::create($client, $session);
    }

    protected function getJasonAddress()
    {
        throw new BadMethodCallException("getJasonAddress not implemented");
    }

    protected function getBasePath()
    {
        throw new BadMethodCallException("getBasePath not implemented");
    }
}
