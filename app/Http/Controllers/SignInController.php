<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SignIn;
use App\Traits\ApiResponser;

class SignInController extends Controller {
    use ApiResponser;

    public function __construct() {
        // Initialization if needed
    }

    public function index() {
        $users = SignIn::all();
        return $this->successResponse($users);
    }

    public function add(Request $request) {
        $rules = [
            'email' => 'required|email|max:50',
            'password' => 'required|max:20',
        ];

        $this->validate($request, $rules);

        $user = SignIn::create($request->all());
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function show($id) {
        $user = SignIn::findOrFail($id);
        return $this->successResponse($user);
    }

    public function update(Request $request, $id) {
        $rules = [
            'email' => 'required|email|max:50',
            'password' => 'required|max:20',
        ];

        $this->validate($request, $rules);
        $user = SignIn::findOrFail($id);

        $user->fill($request->all());

        if ($user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();
        return $this->successResponse($user);
    }

    public function delete($id) {
        $user = SignIn::findOrFail($id);
        $user->delete();
        return $this->successResponse($user);
    }
}
