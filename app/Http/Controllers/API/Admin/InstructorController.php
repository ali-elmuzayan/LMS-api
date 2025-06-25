<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = User::instructor();

         // Check if approval status is specified in the request
    if ($request->has('is_approved')) {
        $isApproved = filter_var($request->input('is_approved'), FILTER_VALIDATE_BOOLEAN);
        $query->where('is_approved', $isApproved);
    }

    // Get the results
    $instructors = $query->get();

    if (count($instructors) > 0) {
        return ApiResponse::sendResponse(
            200,
            'Instructors retrieved successfully',
            UserResource::collection($instructors)
        );
    }

    return ApiResponse::sendResponse(
        200,
        'No instructors found',
        null
    );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
