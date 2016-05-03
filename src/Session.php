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

    public function __get($property)
    {
        if ($property == "id") {
            return $this->id;
        }
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
