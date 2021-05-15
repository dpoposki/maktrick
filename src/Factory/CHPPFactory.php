<?php

namespace App\Factory;

use PHT\PHT;

class CHPPFactory
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return PHT
     */
    public function build(): PHT
    {
        return new PHT([
            'CONSUMER_KEY' => $this->config['consumer_key'],
            'CONSUMER_SECRET' => $this->config['consumer_secret'],
            'OAUTH_TOKEN' => $this->config['oauth_token'],
            'OAUTH_TOKEN_SECRET' => $this->config['oauth_token_secret'],
            'CACHE' => 'none'
        ]);
    }
}
