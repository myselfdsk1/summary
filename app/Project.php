<?php

namespace App;


use App\Modifications\ModelWithDocuments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Traits\Translatable;

class Project extends Model implements ModelWithDocuments
{
    use Translatable;

    public function save(array $options = [])
    {
        if (!$this->user && Auth::user()) {
            $this->user_id = Auth::user()->id;
        }
        return parent::save();
    }

    public function documents()
    {
        return $this
            ->hasMany('App\ProjectDocument');
    }

    public function likes()
    {
        return $this->hasMany('App\ProjectsLike', 'project_id')
            ->where('is_like', '=', 1);
    }

    public function dislikes()
    {
        return $this->hasMany('App\ProjectsLike', 'project_id')
            ->where('is_like', '=', 0);
    }

    public function comments()
    {
        return $this
            ->hasMany('App\Comment')->with('user')->orderBy("created_at", "desc");
    }
}