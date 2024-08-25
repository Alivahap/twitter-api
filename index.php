<?php
require 'vendor/autoload.php';
require 'App/ApiServiceFactory.php';

use App\ApiServiceFactory;

$bearerToken = 'AAAAAAAAAAAAAAAAAAAAAEkAvgEAAAAAfLo7mVjc47W1b%2BU83tATaQPOVkk%3D8wGtpo5qQHdeekYzEJhWuppB2BwMwYA4zZwOaos4VaC6N1KV1c';
$baseUri = 'https://api.x.com/2/';
$factory = new ApiServiceFactory($bearerToken, $baseUri);

$user = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = htmlspecialchars($_POST['username']);
    try {
        $service = $factory->createService('user_search');
        $user = $service->execute(['username' => $username, 'fields' => ['description']]);
    } catch (\RuntimeException $e) {
        $error = json_decode($e->getMessage(), true)['message'] ?? 'Bir hata oluştu.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter Kullanıcı Arama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Twitter Kullanıcısını Ara</h1>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="username" class="form-label">Twitter Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <button type="submit" class="btn btn-primary">Ara</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php elseif ($user): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($user->data->name ?? 'No name'); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($user->data->description ?? 'No description'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
