<?php

namespace App\Services;

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
        $this->validateUser($data);
        try {
            $user = $this->user->find($id);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $password = $data['password'];
            $user->password = app('hash')->make($password);
            $user->update();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException($e->getMessage());
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
        $this->validateUser($data);
        try {
            $user = new $this->user;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $password = $data['password'];
            $user->password = app('hash')->make($password);
            $user->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException($e->getMessage());
        }

        return $user;
    }

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data)
    {
        //validate data
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors()->first());
            throw new InvalidArgumentException($validator->errors()->first());
        }
        //end validate

        $input = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
        if (!$authorized = Auth::attempt($input)) {
            $code = 404;
            $output = [
                'code' => $code,
                'message' => 'User is not authorized'
            ];
        } else {
            $token = $this->respondWithToken($authorized);
            $code = 200;
            $output = [
                'code' => $code,
                'message' => 'User logged in successfully.',
                'token' => $token
            ];
        }
        return $output;
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
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Validate data
     *
     * @param array $data
     * @return void
     */
    protected function validateUser(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            Log::info($validator->errors()->first());
            throw new InvalidArgumentException($validator->errors()->first());
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
