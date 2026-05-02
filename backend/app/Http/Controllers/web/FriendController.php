<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FriendRequestService;
use App\Services\UserService;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    protected $friendRequestService;
    public function __construct(FriendRequestService $friendRequestService) {
        $this->friendRequestService = $friendRequestService;
    }

    public function search(Request $request) {
        if ($request->all() === []) {
            return view('addFriend');
        }

        $data = [
            'username' => $request->input('username', ''),
            'page' => (int) $request->input('page', 1),
            'size' => (int) $request->input('size', 10)
        ];

        try {
            $results = $this->friendRequestService->searchUser($data['username'], $data['page'], $data['size']);

            $users_data = collect();
            foreach ($results['users'] as $user) {
                $users_data->push([
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'status' => $user->friend_status
                ]);
            };

            $data = [
                'users' => $users_data,
                'has_more_pages' => $results['has_more_pages'],
                'last_page' => $results['last_page'],
                'on_first_page' => $results['on_first_page'],
                'page' => $data['page'],
                'size' => $data['size']
            ];

            return view('addFriend', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login')->withErrors([
                    'message' => 'Pengguna belum terautentikasi.'
                ]),
                default => back()->withErrors([
                    'message' => 'Something went wrong.'
                ])
            };
        }
        
    }

    public function rejectFriendRequest($id_request) {
        try {
            $this->friendRequestService->rejectFriendRequest($id_request);
            return back();
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'FRIEND_REQUEST_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Friend Request Tidak Dapat Ditemukan.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function acceptFriendRequest($id_request) {
        try {
            $this->friendRequestService->acceptFriendRequest($id_request);
            return back();
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'FRIEND_REQUEST_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'Friend Request Tidak Dapat Ditemukan.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function friendRequest() {
        try {
            $friend_requests_with_user = $this->friendRequestService->friendRequest();
            $friend_requests_data = collect();
            foreach ($friend_requests_with_user as $friend_request) {
                $friend_requests_data->push([
                    'id_request' => $friend_request->id_request,
                    'username' => $friend_request->username,
                    'email' => $friend_request->email
                ]);
            }
            $data = [
                'friend_requests' => $friend_requests_data
            ];

            return view('friendRequest', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function sendFriendRequest($id_target) {
        try {
            $this->friendRequestService->sendFriendRequest($id_target);
            return back();
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                'USER_NOT_FOUND' => view('errors.error', [
                    'title' => '404 Not Found',
                    'description' => 'User Tidak Dapat Ditemukan.'
                ]),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }

    public function friends() {
        try {
            $friends = $this->friendRequestService->friends();

            $data = [
                'friends' => $friends
            ];

            return view('friendList', ['data' => $data]);
        } catch (\Exception $e) {
            return match ($e->getMessage()) {
                'USER_NOT_AUTHENTICATED' => redirect('/login'),
                default => view('errors.error', [
                    'title' => '500 Internal Server Error',
                    'description' => 'Something went wrong.'
                ])
            };
        }
    }
}
