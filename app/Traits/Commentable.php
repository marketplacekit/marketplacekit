<?php

namespace App\Traits;

use App\Models\Comment;

trait Commentable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return bool
     */
    public function getCanBeRated()
    {
        return (isset($this->canBeRated)) ? $this->canBeRated : false;
    }

    /**
     * @return bool
     */
    public function mustBeApproved()
    {
        return (isset($this->mustBeApproved)) ? $this->mustBeApproved : false;
    }

    /**
     * @return mixed
     */
    public function totalCommentCount()
    {
        return ($this->mustBeApproved()) ? $this->comments()->where('approved', true)->count() : $this->comments()->count();
    }

    /**
     * @return float
     */
    public function averageRate()
    {
        return ($this->getCanBeRated()) ? $this->comments()->where('approved', true)->avg('rate') : 0;
    }
}
