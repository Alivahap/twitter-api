<?php

// This code is not working becasuse of Twitter Accessing Mode
namespace App;

class TweetFetchService extends AbstractApiService
{
    public function execute(array $parameters): object
    {
        $userId = $parameters['user_id'];
        $url = "users/{$userId}/tweets";
        return $this->sendRequest('GET', $url);
    }
}

?>