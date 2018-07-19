<?php

namespace App\Repositories;


use App\Project;
use App\ProjectsLike;
use Illuminate\Support\Facades\Auth;

class ProjectsRepo implements Repository
{

    public function findById(int $id)
    {
        return Project::find($id);
    }

    public function findAll()
    {
        return Project::where('status', 'SHOW')->get();
    }

    public function findBySlug(string $slug) {
        return Project::where('status', 'SHOW')->where('slug', '=', $slug)->first();
    }

    public function findProjectLikeByProjectIdAndUserId(int $projectId, int $userId) {
        return ProjectsLike::where('project_id', '=', $projectId)
            ->where('user_id', '=', $userId)
            ->first();
    }

    public function findProjectLikeByProjectId($projectId) {
        return Project::find($projectId)->likes();
    }

    public function findProjectDislikeByProjectId($projectId) {
        return Project::find($projectId)->dislikes();
    }
}