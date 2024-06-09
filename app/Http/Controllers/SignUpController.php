<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SignUp;
use App\Traits\ApiResponser;

class SignUpController extends Controller {

    use ApiResponser;

    private $request;

    public function __construct(Request $request){
    $this->request = $request;
    }

    public function getUsers(){
    $users = SignUp::all();
    return response()->json($users, 200);
    }

    //GET USERS

    public function index()
    {
    $users = SignUp::all();
    return $this->successResponse($users);

    }

    public function add(Request $request ){
        $rules = [
        'email' => 'required|max:50',
        'name' => 'required|max:20',
        'password' => 'required|max:20',
        ];
        $this->validate($request,$rules);
        $user = SignUp::create($request->all());
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function show($id)
    {
    $user = SignUp::findOrFail($id);
    return $this->successResponse($user);
    }

    public function update(Request $request,$id)
    {
    $rules = [
        'email' => 'required|max:20',
        'name' => 'required|max:20',
        'password' => 'required|max:20',
    ];
    $this->validate($request, $rules);
    $user = SignUp::findOrFail($id);

    $user->fill($request->all());
    // if no changes happen
    if ($user->isClean()) {
    return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    $user->save();
    return $this->successResponse($user);
    }

    public function delete($id)
    {
    $user = SignUp::findOrFail($id);
    $user->delete();
    return $this->successResponse($user);
    }

}