<?php
namespace App;
require 'AbstractApiService.php';


class UserSearchService extends AbstractApiService
{
    public function execute(array $parameters): object
    {
        $username = $parameters['username'];
        $fields = $parameters['fields'] ?? [];
        $url = "users/by/username/{$username}?user.fields=" . implode(',', $fields);
        return $this->sendRequest('GET', $url);
    }
}
?>