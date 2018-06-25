<?php

namespace App\Traits;

use App\Models\Comment;

trait CanComment
{

    /**
     * @param $commentable
     * @param string $commentText
     * @param int $rate
     * @return $this
     */
    public function comment($listing, $commentText = '', $rate = 0)
    {
        $comment = new Comment([
            'comment'        => $commentText,
            'rate'           => ($listing->getCanBeRated()) ? $rate : null,
            'approved'       => ($listing->mustBeApproved() && ! $this->isAdmin()) ? false : true,
            'commenter_id'   => $this->id,
            'seller_id'   	 => $listing->user_id,
        ]);

        $listing->comments()->save($comment);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
   /* public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }*/
}
