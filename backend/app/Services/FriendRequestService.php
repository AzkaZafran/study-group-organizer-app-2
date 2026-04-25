<?php

namespace App\Services;

use App\Models\FriendRequests;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class FriendRequestService {
    public function searchUser($username, $page, $size) {
        $user = Auth::user();

        if(!$user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $auth_id = $user->id;

        /** @var \Illuminate\Support\Collection<int, User> */
        $users = User::query()
                    ->where('id', '!=', $auth_id)
                    ->where('username', 'like', "%{$username}%") // filter search
                    ->where('is_verified', true)
                    ->with([
                        'sentRequests' => fn ($q) => $q->where('id_penerima', $auth_id),
                        'receivedRequests' => fn ($q) => $q->where('id_pengirim', $auth_id),
                    ])
                    ->paginate(perPage: $size, page:$page)
                    ->getCollection();

        $users->map(function ($user) {
            if ($user->sentRequests->isNotEmpty()) {
                $user->friend_status = $user->sentRequests->first()->status;
            } elseif ($user->receivedRequests->isNotEmpty()) {
                $user->friend_status = $user->receivedRequests->first()->status;
            } else {
                $user->friend_status = 'open invite';
            }

            return $user;
        });

        return $users;
    }
}