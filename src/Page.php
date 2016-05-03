<?php

namespace Jason;

use GuzzleHttp\Client;

class Page
{
    private $client;

    private $session;

    private $id;

    private $properties = [
        "returned",
        "address",
        "status",
        "body",
    ];

    public function __construct(Client $client, Session $session, $id)
    {
        $this->client = $client;
        $this->session = $session;
        $this->id = $id;
    }

    public function __get($property)
    {
        if ($property == "id") {
            return $this->id;
        }

        if (in_array($property, $this->properties)) {
            return $this->view()->$property;
        }
    }

    private function view()
    {
        $response = $this->client->request(
            "GET", sprintf("http://localhost:4321/session/%s/page/%s", $this->session->id, $this->id)
        );

        $json = json_decode(
            $response->getBody()
        );

        return $json->page;
    }

    public function visit($address)
    {
        $response = $this->client->request(
            "POST", sprintf("http://localhost:4321/session/%s/page/%s/visit", $this->session->id, $this->id), [
                "form_params" => [
                    "address" => $address,
                ],
            ]
        );

        return $this;
    }

    public function run($script)
    {
        $response = $this->client->request(
            "POST", sprintf("http://localhost:4321/session/%s/page/%s/run", $this->session->id, $this->id), [
                "form_params" => [
                    "script" => $script,
                ],
            ]
        );

        return $this;
    }

    public static function create(Client $client, Session $session)
    {
        $response = $client->request(
            "POST", sprintf("http://localhost:4321/session/%s/page", $session->id)
        );

        $json = json_decode(
            $response->getBody()
        );

        return new static($client, $session, $json->page->id);
    }
}
