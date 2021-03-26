<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\UserAuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    protected $service;


    public function __construct()
    {
        $this->service = new UserAuthService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $queryBuilder = Article::with(['category', 'author'])->sortable(['created_at' => 'desc']);

        /**
         * Add category filter if there is a category in the request.
         */
        if ($request->query('category')) {
            $queryBuilder->where('category_id', $request->query('category'));
        }

        /**
         * If the user is not autenticated return ony the published ones
         */
        if (!$this->service->userIsAuthenticated()) {
            $queryBuilder->where('status', config('contents.statuses.ONLINE'));
            return ArticleResource::collection($queryBuilder->paginate(10)->withQueryString());
        }


        /**
         * Writers only sees their own articles
         */
        if ($this->service->userIsWriterOnly()) {
            $queryBuilder->where('author_id', auth()->user()->id);
        }


        return ArticleResource::collection($queryBuilder->paginate(10)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        /**
         * Authorization via policy on model, ArticlePolicy
         */
        $this->authorize('create', [Article::class]);

        $data = $request->validated();

        /**
         * Sets author as current user and status Draft.
         */
        $data['author_id'] = auth()->user()->id;
        $data['status'] = config('contents.statuses.DRAFT');


        return Article::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {

        /**
         * Authorization via policy on model,
         */
        $this->authorize('view', $article);

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        /**
         * Data is validated via UpdateArticleRequest
         */

        /**
         * Authorization via policy on model, ArticlePolicy
         */
        $this->authorize('update', $article);

        $article->update($request->all());

        return new ArticleResource(
            $article
        );
    }

    /**
     * Publish the model, setting it on the Online Status.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function publish(Article $article)
    {

        /**
         * Authorization via policy on model, ArticlePolicy
         */
        $this->authorize('publish', $article);

        /**
         * Uppdates status to Online and publishedOn to current date.
         */

        $article->update([
            'status' => config('contents.statuses.ONLINE'),
            'publishedOn' => Carbon::now()
        ]);

        return new ArticleResource(
            $article
        );
    }

}
