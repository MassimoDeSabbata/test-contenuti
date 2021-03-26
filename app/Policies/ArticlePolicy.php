<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use App\Services\UserAuthService;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    use HandlesAuthorization;


    public function __construct()
    {
        $this->service = new UserAuthService;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function view(?User $user, Article $article)
    {

        /**
         * If an article is published anyone can see it.
         */
        if ($article->status === config('contents.statuses.ONLINE')) {
            return Response::allow();
        }

        /**
         * If the artiche is draft and the user is a gurest, can not see the article.
         */
        if (!$this->service->userIsAuthenticated()) {
            return Response::deny('This article is not public.');
        }

        /**
         * If the user is a writer but not an editor he can see only his own article
         */
        if (!$this->service->userIsWriterOnly()) {
            return ($article->author_id === $user->id)
                ? Response::allow()
                : Response::deny('This article is not yours.');
        }

        /**
         * If the user is also an editor can see any article
         */
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        /**
         * Only logghed users can create articles, users that have at least the writer role.
         */
        return $this->service->userIsWriter();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        /**
         * If the article us published no one can update it.
         */
        if ($article->status === config('contents.statuses.ONLINE')) {
            return Response::deny('This article is published and can not be updated.');
        }

        /**
         * If the artiche is draft and the user is a gurest, he can not update it
         */
        if (!$this->service->userIsAuthenticated()) {
            return Response::deny('This article is not public.');
        }

        /**
         * If the artiche is draft and the user is a writer but not an editor, he can not update only if he is the author.
         */
        if (!$this->service->userIsWriterOnly()) {
            return ($article->author_id === $user->id)
                ? Response::allow()
                : Response::deny('This article is not yours.');
        }

        /**
         * If the user is a editor
         */
        if ($this->service->userIsEditor()) {


            /**
             * If he is the author can update
             */
            if ($article->author_id === $user->id) {
                return Response::allow();
            }

            /**
             * If the author is not him but the author is another editor can NOT update
             */
            $author = $article->author;
            if ($author->hasRole('editor')){
                return Response::deny('This article is not yours.');
            }

            /**
             * If the author is not him but the author is a writer can update
             */
            return Response::allow();
        }

        /**
         * There should not exist another default case, but in case that happend deny.
         */
        return Response::deny('Who are you and how you got here??');
    }



    /**
     * Determine whether the user can publish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function publish(User $user, Article $article)
    {
        /**
         * If the article us published no one can update it.
         */
        if ($article->status === config('contents.statuses.ONLINE')) {
            return Response::deny('This post is already published.');
        }

        /**
         * If the artiche is draft and the user is a gurest, he can not update it
         */
        if (!$this->service->userIsAuthenticated()) {
            return Response::deny('hehe you wish you could read this.');
        }

        /**
         * If the artiche is draft and the user is a writer but not an editor, he can not update only if he is the author.
         */
        if (!$this->service->userIsWriterOnly()) {
            return Response::deny('You are not allowed to publish articles.');
        }

        /**
         * If the user is a editor
         */
        if ($this->service->userIsEditor()) {


            /**
             * If he is the author can update
             */
            if ($article->author_id === $user->id) {
                return Response::allow();
            }

            /**
             * If the author is not him but the author is another editor can NOT update
             */
            $author = $article->author;
            if ($author->hasRole('editor')){
                return Response::deny('This article is not yours.');
            }

            /**
             * If the author is not him but the author is a writer can update
             */
            return Response::allow();
        }

        /**
         * There should not exist another default case, but in case that happend deny.
         */
        return Response::deny('Who are you and how you got here??');
    }
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function restore(User $user, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function forceDelete(User $user, Article $article)
    {
        //
    }
}
