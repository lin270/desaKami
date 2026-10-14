<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /** Admin boleh mengubah semua berita; editor hanya berita miliknya. */
    public function update(User $user, Post $post): bool
    {
        return $user->isAdmin() || $post->author_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);
    }
}
