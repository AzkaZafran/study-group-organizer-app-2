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
            return view('test');
        }

        $data = [
            'username' => $request->input('username', ''),
            'page' => (int) $request->input('page', 1),
            'size' => (int) $request->input('size', 10)
        ];

        try {
            $users = $this->friendRequestService->searchUser($data['username'], $data['page'], $data['size']);

            $users_data = collect();
            foreach ($users as $user) {
                $users_data->push([
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'status' => $user->friend_status
                ]);
            };

            $data = [
                'users' => $users_data,
                'page' => $data['page'],
                'size' => $data['size']
            ];

            return view('test', ['data' => $data]);
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

    public function rejectFriendRequest($id_pengirim) {
        try {
            $this->friendRequestService->rejectFriendRequest($id_pengirim);
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
}
