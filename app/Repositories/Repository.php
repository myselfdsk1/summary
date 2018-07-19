<?php

namespace App\Repositories;


interface Repository
{
    public function findById(int $id);

    public function findAll();
}