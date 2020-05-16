<?php
namespace App\Service;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\HttpBasicLdapFactory;
use Symfony\Component\HttpClient\HttpClient;

class ClientHTTP
{
     /** @var HttpClient */
     private $client;
     /**
      * @param HttpClient $client
      */
     public function __construct(HttpClient $client)
     {
         $this->client = HttpClient::create();
     }

     /**
      * @param string $url
      *
      * @return string
      */
     public function getHttpClient(string  $url) : string
     {
         $response = $this->client->request('GET', $url);
         return $response ;
     }

}