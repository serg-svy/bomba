<?php

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\ClientErrorResponseException;

class Elasticsearch{

    public $client;
    public $settings = [];

    public function __construct(){
        $this->client = ClientBuilder::create()->build();
        $this->settings['product'] = ['type' => 'product', 'index' => 'app_products'];
        $this->settings['category'] = ['type' => 'category', 'index' => 'app_categories'];
    }

    public function index($params, $index = 'product') {
        try {
            $params['index'] = $this->settings[$index]['index'];
            $params['type'] = $this->settings[$index]['type'];
            return $response = $this->client->index($params);
        } catch (Exception $e) {
            return "Error";
        }
    }

    public function search($params) {
        return $response = $this->client->search($params);
    }

    public function info() {

    }

    public function delete($index = 'product') {
        try {
            return $this->client->delete([
                'index' => $this->settings[$index]['index']
            ]);
        } catch (ClientErrorResponseException $e) {
            if ($e->getCode() === 404) {
                return "Not found";
            }
        }
    }
}
