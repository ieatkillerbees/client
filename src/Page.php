<?php

namespace Jason;

use GuzzleHttp\Client;

class Page
{
    private $client;

    private $session;

    private $id;

    public function __construct(Client $client, Session $session, $id)
    {
        $this->client = $client;
        $this->session = $session;
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function returned()
    {
        return $this->view()->returned;
    }

    public function address()
    {
        return $this->view()->address;
    }

    public function status()
    {
        return $this->view()->status;
    }

    public function body()
    {
        return $this->view()->body;
    }

    public function width($width = null)
    {
        if ($width) {
            if ($height = $this->height()) {
                return $this->resize($width, $height);
            }
        }

        return $this->view()->width;
    }

    public function height($height = null)
    {
        if ($height) {
            if ($width = $this->width()) {
                return $this->resize($width, $height);
            }
        }

        return $this->view()->height;
    }

    public function left($left = null)
    {
        if ($left) {
            if ($top = $this->top()) {
                return $this->scroll($left, $top);
            }
        }

        return $this->view()->left;
    }

    public function top($top = null)
    {
        if ($top) {
            if ($left = $this->left()) {
                return $this->scroll($left, $top);
            }
        }

        return $this->view()->top;
    }

    public function zoom($zoom = null)
    {
        if ($zoom) {
            return $this->_zoom($zoom);
        }

        return $this->view()->zoom;
    }

    private function view()
    {
        $url = sprintf(
            "/session/%s/page/%s",
            $this->session->id(), $this->id
        );

        $response = $this->client->request(
            "GET", $url
        );

        $json = json_decode(
            $response->getBody()
        );

        return $json->page;
    }

    public function visit($address)
    {
        $url = sprintf(
            "/session/%s/page/%s/visit",
            $this->session->id(), $this->id
        );

        $this->client->request(
            "POST", $url, [
                "form_params" => [
                    "address" => $address,
                ],
            ]
        );

        return $this;
    }

    public function run($script)
    {
        $url = sprintf(
            "/session/%s/page/%s/run",
            $this->session->id(), $this->id
        );

        $this->client->request(
            "POST", $url, [
                "form_params" => [
                    "script" => $script,
                ],
            ]
        );

        return $this;
    }

    public function resize($width, $height)
    {
        $url = sprintf(
            "/session/%s/page/%s/resize",
            $this->session->id(), $this->id
        );

        $this->client->request(
            "POST", $url, [
                "form_params" => [
                    "width" => $width,
                    "height" => $height,
                ],
            ]
        );

        return $this;
    }

    public function scroll($left, $top)
    {
        $url = sprintf(
            "/session/%s/page/%s/scroll",
            $this->session->id(), $this->id
        );

        $this->client->request(
            "POST", $url, [
                "form_params" => [
                    "left" => $left,
                    "top" => $top,
                ],
            ]
        );

        return $this;
    }

    private function _zoom($zoom)
    {
        $url = sprintf(
            "/session/%s/page/%s/zoom",
            $this->session->id(), $this->id
        );

        $this->client->request(
            "POST", $url, [
                "form_params" => [
                    "zoom" => $zoom,
                ],
            ]
        );

        return $this;
    }

    public function capture()
    {
        $url = sprintf(
            "/session/%s/page/%s/capture",
            $this->session->id(), $this->id
        );

        $response = $this->client->request(
            "POST", $url
        );

        $json = json_decode(
            $response->getBody()
        );

        return $json->data;
    }

    public static function create(Client $client, Session $session)
    {
        $url = sprintf(
            "/session/%s/page",
            $session->id()
        );

        $response = $client->request(
            "POST", $url
        );

        $json = json_decode(
            $response->getBody()
        );

        return new static($client, $session, $json->page->id);
    }
}
