<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Http\Response; // For HTTP response status codes
use App\Models\SignUp; // The SignUp model
use App\Traits\ApiResponser; // Custom trait for standardized API responses

// Define the SignUpController class, which extends the base Controller class
class SignUpController extends Controller {

    // Use the ApiResponser trait for standardized responses
    use ApiResponser;

    // Property to hold the request instance
    private $request;

    // Constructor method to initialize the request instance
    public function __construct(Request $request) {
        // Assign the passed request instance to the controller's property
        $this->request = $request;
    }

    // Method to handle GET requests for fetching all SignUp records
    public function getUsers() {
        // Retrieve all records from the SignUp model
        $users = SignUp::all();

        // Return the records as a JSON response with a 200 status code
        return response()->json($users, 200);
    }

    // Method to handle GET requests for fetching all SignUp records (alternative method)
    public function index() {
        // Retrieve all records from the SignUp model
        $users = SignUp::all();

        // Return the records as a successful JSON response
        return $this->successResponse($users);
    }

    // Method to handle POST requests for adding a new SignUp record
    public function add(Request $request) {
        // Define validation rules for the request data
        $rules = [
            'email' => 'required|max:50',
            'name' => 'required|max:20',
            'password' => 'required|max:20',
        ];

        // Validate the request data against the rules
        $this->validate($request, $rules);

        // Create a new SignUp record with the validated data
        $user = SignUp::create($request->all());

        // Return the created record as a successful JSON response with a 201 status code
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    // Method to handle GET requests for fetching a specific SignUp record by ID
    public function show($id) {
        // Find the SignUp record by its ID or fail
        $user = SignUp::findOrFail($id);

        // Return the record as a successful JSON response
        return $this->successResponse($user);
    }

    // Method to handle PUT/PATCH requests for updating a specific SignUp record by ID
    public function update(Request $request, $id) {
        // Define validation rules for the request data
        $rules = [
            'email' => 'required|max:50',
            'name' => 'required|max:20',
            'password' => 'required|max:20',
        ];

        // Validate the request data against the rules
        $this->validate($request, $rules);

        // Find the SignUp record by its ID or fail
        $user = SignUp::findOrFail($id);

        // Fill the record with the validated request data
        $user->fill($request->all());

        // Check if any data has changed
        if ($user->isClean()) {
            // Return an error response if no data has changed
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Save the updated record
        $user->save();

        // Return the updated record as a successful JSON response
        return $this->successResponse($user);
    }

    // Method to handle DELETE requests for deleting a specific SignUp record by ID
    public function delete($id) {
        // Find the SignUp record by its ID or fail
        $user = SignUp::findOrFail($id);

        // Delete the record
        $user->delete();

        // Return the deleted record as a successful JSON response
        return $this->successResponse($user);
    }
}
