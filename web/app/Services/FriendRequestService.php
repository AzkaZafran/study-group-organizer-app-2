<?php

namespace App\Services;

use App\Models\FriendRequests;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FriendRequestService {
    public function searchUser($username, $page, $size) {
        $user = Auth::user();

        if(!$user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $auth_id = $user->id;

        $users_pagination = User::query()
                    ->where('id', '!=', $auth_id)
                    ->where('username', 'like', "%{$username}%") // filter search
                    ->where('is_verified', true)
                    ->with([
                        'sentRequests' => fn ($q) => $q->where('id_penerima', $auth_id),
                        'receivedRequests' => fn ($q) => $q->where('id_pengirim', $auth_id),
                    ])
                    ->paginate(perPage: $size, page:$page);

        $on_first_page = $users_pagination->onFirstPage();
        $last_page = $users_pagination->lastPage();
        $has_more_pages = $users_pagination->hasMorePages();
        /** @var \Illuminate\Support\Collection<int, User> */
        $users = $users_pagination->getCollection();

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

        /** @var array{users: Collection<int, User>, on_first_page: bool, last_page: int, has_more_pages: bool} */
        $data = [
            'users' => $users,
            'on_first_page' => $on_first_page,
            'last_page' => $last_page,
            'has_more_pages' => $has_more_pages
        ];

        return $data;
    }

    public function rejectFriendRequest($id_request): bool {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $friend_request_data = FriendRequests::find($id_request);
        
        if (!$friend_request_data) {
            throw new Exception('FRIEND_REQUEST_NOT_FOUND');
        }

        $id_pengirim = $friend_request_data->id_pengirim;
        $id_penerima = $friend_request_data->id_penerima;

        if ($friend_request_data->status === "mutual") {
            throw new Exception('USER_ALREADY_MUTUAL');
        } else if (FriendRequests::where('id_pengirim', $id_pengirim)->where('id_penerima', $id_penerima)->where('status', 'mutual')->first()) {
            $friend_request_data->delete();
            throw new Exception('USER_ALREADY_MUTUAL');
        }

        $status = $friend_request_data->delete();
        return $status;
    }

    public function acceptFriendRequest($id_request): bool {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $friend_request_data = FriendRequests::find($id_request);
        
        if (!$friend_request_data) {
            throw new Exception('FRIEND_REQUEST_NOT_FOUND');
        }

        $id_pengirim = $friend_request_data->id_pengirim;
        $id_penerima = $friend_request_data->id_penerima;

        if ($friend_request_data->status === "mutual") {
            throw new Exception('USER_ALREADY_MUTUAL');
        } else if (FriendRequests::where('id_pengirim', $id_pengirim)->where('id_penerima', $id_penerima)->where('status', 'mutual')->first()) {
            $friend_request_data->delete();
            throw new Exception('USER_ALREADY_MUTUAL');
        }

        $friend_request_data->status = 'mutual';
        if (!$friend_request_data->save()) {
            return false;
        }

        $receiver_mutual_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $friend_request_data->id_pengirim,
            'status' => 'mutual'
        ];

        if (!FriendRequests::create($receiver_mutual_data)) {
            return false;
        }

        return true;
    }

    public function friendRequest() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $friend_requests_with_user = $auth_user->hasMany(FriendRequests::class, 'id_penerima')
                                                ->where('status', 'pending')
                                                ->with('userPengirim')
                                                ->get();
        
        $friend_requests_with_user->map(function ($friend_request) {
            $friend_request->username = $friend_request->userPengirim->username;
            $friend_request->email = $friend_request->userPengirim->email;

            return $friend_request;
        });

        return $friend_requests_with_user;
    }

    public function sendFriendRequest($id_target): bool {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $target_user = User::find($id_target);

        if(!$target_user) {
            throw new Exception('USER_NOT_FOUND');
        }

        $friend_request_data = [
            'id_pengirim' => $auth_user->id,
            'id_penerima' => $target_user->id
        ];

        if (!FriendRequests::create($friend_request_data)) {
            return false;
        }

        return true;
    }

    public function friends() {
        $auth_user = Auth::user();

        if(!$auth_user) {
            throw new Exception('USER_NOT_AUTHENTICATED');
        }

        $auth_user_friends = $auth_user->sentRequests()
            ->where('status', 'mutual')
            ->with('userPenerima:id,username,email') // select specific columns
            ->get()
            ->map(function ($friend_request) {
                return [
                    'username' => $friend_request->userPenerima->username,
                    'email' => $friend_request->userPenerima->email,
                ];
            });

        return $auth_user_friends;
    }
}