<?php

namespace Jason;

use GuzzleHttp\Client;

class Session
{
    private $client;

    private $session;

    private $id;

    public function __construct(Client $client, $id)
    {
        $this->client = $client;
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function close()
    {
        $url = sprintf(
            "/session/%s/close",
            $this->id
        );

        $this->client->request(
            "POST", $url
        );

        return $this;
    }

    public static function create(Client $client)
    {
        $url = sprintf(
            "/session"
        );

        $response = $client->request(
            "POST", $url
        );

        $json = json_decode(
            $response->getBody()
        );

        return new static($client, $json->session->id);
    }
}
