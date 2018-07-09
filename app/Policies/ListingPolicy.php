<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the listing.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listing  $listing
     * @return mixed
     */
    public function view(User $user, Listing $listing)
    {
        //
    }

    /**
     * Determine whether the user can create listings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the listing.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listing  $listing
     * @return mixed
     */
    public function update(User $user, Listing $listing)
    {
        return $user->id === $listing->user_id || $user->can('edit listing');
    }

    /**
     * Determine whether the user can delete the listing.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Listing  $listing
     * @return mixed
     */
    public function delete(User $user, Listing $listing)
    {
        return $user->id === $listing->user_id || $user->can('edit listing');
    }
}
