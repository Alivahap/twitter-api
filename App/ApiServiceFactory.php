<?php
namespace App;

class ApiServiceFactory implements ApiServiceFactoryInterface
{
    private string $bearerToken;
    private string $baseUri;

    public function __construct(string $bearerToken, string $baseUri)
    {
        $this->bearerToken = $bearerToken;
        $this->baseUri = $baseUri;
    }

    public function createService(string $serviceType): ApiServiceInterface
    {
        switch ($serviceType) {
            case 'user_search':
                return new UserSearchService($this->bearerToken, $this->baseUri);
            case 'tweet_fetch':
                return new TweetFetchService($this->bearerToken, $this->baseUri);
            default:
                throw new \InvalidArgumentException("Invalid service type");
        }
    }
}

?>