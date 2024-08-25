<?php
namespace App;

interface ApiServiceFactoryInterface
{
    public function createService(string $serviceType): ApiServiceInterface;
}
?>