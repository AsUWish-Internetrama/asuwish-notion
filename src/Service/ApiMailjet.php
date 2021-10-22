<?php

namespace App\Service;

use \Mailjet\Resources;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ApiMailjet 
{
    /** 
     * @var Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface
     */
    private $_params;

    public function __construct(
        ContainerBagInterface $params
    ) {
        $this->_params = $params;
    }

    public function getClient()
    {
        return new \Mailjet\Client(
            $this->_params->get('api_key_mailjet'),
            $this->_params->get('api_secret_mailjet'),
            true,
            ['version' => 'v3.1']
        );
    }

    public function sendEmail($email)
    {
        return true;
    }
}