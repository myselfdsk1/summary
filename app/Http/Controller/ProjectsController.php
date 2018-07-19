<?php

namespace App\Http\Controllers;


use App\Comment;
use App\Project;
use App\ProjectsLike;
use App\Repositories\ProjectsRepo;
use App\Services\AccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    protected $seoTitle = 'Проекты документов';
    protected $level = 3;
    private $projectsRepo;

    /**
     * Конструктор
     * @param ProjectsRepo $projectsRepo репозиторий проектов
     */
    public function __construct(ProjectsRepo $projectsRepo)
    {
        $this->middleware('auth');
        $this->projectsRepo = $projectsRepo;
    }

    /**
     * Страница проектов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!AccessService::isAccess(Auth::user(), $this->level)) {
            return redirect("/"); //TODO replace on 404 page
        }
        $projects = $this->projectsRepo->findAll();
        return $this->render('projects', ['projects' => $projects]);
    }

    /**
     * Выводит детальную информацию о проекте документов
     * @param string $slug уникальное символьное название проекта
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOne($slug)
    {
        if (!AccessService::isAccess(Auth::user(), $this->level)) {
            return redirect("/"); //TODO replace on 404 page
        }
        $project = $this->projectsRepo->findBySlug($slug);
        if (!$project) {
            abort(404);
        }
        $this->seoTitle = $project->title;
        return $this->render('project', ['project' => $project, 'errors' => null]);
    }

    /**
     * Ставим убираем лайки/дизлайки у проектов
     * @param Request $request
     * @param integer $projectId идентификатор проекта
     * @return array
     */
    public function likeProject(Request $request, int $projectId)
    {
        $isLike = $request->post('is_like') === 'true';
        if (!$request->ajax()) {
            abort(404);
        }

        $project = $this->projectsRepo->findById($projectId);
        if (!$project) {
            abort(404);
        }

        $projectLike = $this->projectsRepo->findProjectLikeByProjectIdAndUserId($projectId, Auth::user()->id);

        if (!empty($projectLike)) {
            if ($projectLike->is_like == $isLike) {
                $projectLike->delete();
            }
            else {
                $projectLike->is_like = $isLike;
                $projectLike->update();
            }
        }
        else {
            $projectLike = new ProjectsLike();
            $projectLike->user_id = Auth::user()->id;
            $projectLike->project_id = $projectId;
            $projectLike->is_like = $isLike;
            $projectLike->save();
        }

        return $this->getLikesAndDislikesByProjectId($projectId);
    }

    /**
     * Получаем количество лайков и дизлайков по id проекта
     * @param integer $projectId идентификатор проекта
     * @return array ассоциативный массив лайков и дизлайков проекта
     */
    private function getLikesAndDislikesByProjectId(int $projectId) : array
    {
        return [
            'like' => $this->projectsRepo->findProjectLikeByProjectId($projectId)->count(),
            'dislike' => $this->projectsRepo->findProjectDislikeByProjectId($projectId)->count()
        ];
    }

    /**
     * Метод добавляет новый комментарий к проекту. Метод доступен только по AJAX
     * @param Request $request
     * @param integer $projectId идентификатор проекта
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addComment(Request $request, int $projectId)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        $message = $request->post('message');
        $parentId = $request->post('parent_id');

        $project = $this->projectsRepo->findById($projectId);

        if (!$project) {
            abort(404);
        }

        $comment = new Comment();
        $comment->project_id = $project->id;
        $comment->message = $message;

        if (!empty($parentId)) {
            $comment->parent_id = $parentId;
        }

        if ($comment->validate($request->all())) {
            $comment->save();
        }

        return $this->render('partials.comments',
            [
                'project' => $this->projectsRepo->findById($projectId),
                'errors' => $comment->errors()
            ]
        );
    }
}