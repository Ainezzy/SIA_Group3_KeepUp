<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // For handling HTTP requests
use Illuminate\Http\Response; // For HTTP response status codes
use App\Models\SignIn; // The SignIn model
use App\Traits\ApiResponser; // Custom trait for standardized API responses

// Define the SignInController class, which extends the base Controller class
class SignInController extends Controller {
    // Use the ApiResponser trait for standardized responses
    use ApiResponser;

    // Constructor method for any initialization, if needed
    public function __construct() {
        // Initialization if needed
    }

    // Method to handle GET requests for fetching all SignIn records
    public function index() {
        // Retrieve all records from the SignIn model
        $users = SignIn::all();

        // Return the records as a successful JSON response
        return $this->successResponse($users);
    }

    // Method to handle POST requests for adding a new SignIn record
    public function add(Request $request) {
        // Define validation rules for the request data
        $rules = [
            'email' => 'required|email|max:50',
            'password' => 'required|max:20',
        ];

        // Validate the request data against the rules
        $this->validate($request, $rules);

        // Create a new SignIn record with the validated data
        $user = SignIn::create($request->all());

        // Return the created record as a successful JSON response with a 201 status code
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    // Method to handle GET requests for fetching a specific SignIn record by ID
    public function show($id) {
        // Find the SignIn record by its ID or fail
        $user = SignIn::findOrFail($id);

        // Return the record as a successful JSON response
        return $this->successResponse($user);
    }

    // Method to handle PUT/PATCH requests for updating a specific SignIn record by ID
    public function update(Request $request, $id) {
        // Define validation rules for the request data
        $rules = [
            'email' => 'required|email|max:50',
            'password' => 'required|max:20',
        ];

        // Validate the request data against the rules
        $this->validate($request, $rules);

        // Find the SignIn record by its ID or fail
        $user = SignIn::findOrFail($id);

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

    // Method to handle DELETE requests for deleting a specific SignIn record by ID
    public function delete($id) {
        // Find the SignIn record by its ID or fail
        $user = SignIn::findOrFail($id);

        // Delete the record
        $user->delete();

        // Return the deleted record as a successful JSON response
        return $this->successResponse($user);
    }
}
