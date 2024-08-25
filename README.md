to use twitter API First define libarary,


use App\ApiServiceFactory;

Then, You must have added some settings in your file. it's enough for auth.

$bearerToken = 'type_bearerToken';

$baseUri = 'https://api.x.com/2/';

$factory = new ApiServiceFactory($bearerToken, $baseUri);


**Twiter User Lookup**

$service = $factory->createService('user_search');

$user = $service->execute(['username' => $username, 'fields' => ['description']]);
