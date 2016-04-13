<?php

/**
 * Class MailHog_API
 */
class MailHog_API {
    private $server_address;

    /**
     * MailHog_API constructor.
     * @param string $server_address
     */
    public function __construct($server_address){
        $this->server_address = rtrim($server_address, "/");
    }

    /**
     * @return MailHog_MailCollection
     */
    public function get_messages() {
        $response = wp_remote_get($this->make_request_url('/api/v2/messages'));
        $json = wp_remote_retrieve_body($response);
        $data = json_decode( $json, true );
        require_once 'class-mailhog-mailcollection.php';
        return MailHog_MailCollection::make($data);
    }

    /**
     * @param $path
     * @return string
     */
    private function make_request_url($path) {
        return $this->server_address . $path;
    }
}