<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProjectsLike extends Model
{
    public function save(array $options = [])
    {
        if (!$this->user && Auth::user()) {
            $this->user_id = Auth::user()->id;
        }
        return parent::save();
    }
}