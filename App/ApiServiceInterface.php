<?php
namespace App;

interface ApiServiceInterface
{
    public function execute(array $parameters): object;
}
?>