<?php

namespace App\Services;

use App\Jobs\SendActiveEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Models\User;

class UserService
{
    /** @var User */
    protected $user;

    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }


    /**
     * Get all users.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->user->get();
    }

    /**
     * Get user by id
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->user->findOrFail($id);
    }

    /**
     * Update user
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $user = $this->user->find($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $password = $data['password'];
            $user->password = app('hash')->make($password);
            $user->update();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }

        return $user;

    }

    /**
     * Create task
     *
     * @param array $data
     * @return mixed
     */
    public function save(array $data)
    {
        $user = new $this->user;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $password = $data['password'];
        $user->password = app('hash')->make($password);
        $user->save();

        //Send email when register successfully
        if ($user->id) {
            dispatch(new SendActiveEmail($user));
        }
        return $user;
    }

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data)
    {
        if (!$authorized = Auth::attempt($data)) {
            $code = 404;
            $message = 'User is not authorized';
            $token = '';
        } else {
            $token = $this->respondWithToken($authorized);
            $code = 200;
            $message = 'User logged in successfully.';
        }

        return [
            'code' => $code,
            'message' => $message,
            'token' => $token
        ];
    }

    /**
     * Delete user by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
