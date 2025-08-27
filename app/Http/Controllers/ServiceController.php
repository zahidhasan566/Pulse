<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Settings\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('ServiceName', 'like', '%' . $request->search . '%')
                ->orWhere('ServiceDetails', 'like', '%' . $request->search . '%');
        }

        $services = $query->select('ServiceID','ServiceName','ServiceAmount','ServiceDetails')->orderBy('ServiceID', 'desc')->paginate(10);

        return response()->json($services);
    }

    /**
     * Store a newly created partner
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ServiceName' => 'required',
            'ServiceAmount' => 'required',
            'ServiceClaimAmount' => 'required',
            'ServiceDetails' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = new Service();
        $data->ServiceName = $request->ServiceName;
        $data->ServiceAmount = $request->ServiceAmount;
        $data->ServiceClaimAmount = $request->ServiceClaimAmount;
        $data->ServiceDetails = $request->ServiceDetails;


        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified partner
     */
    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $service
        ]);
    }

    /**
     * Update the specified partner
     */
    public function update(Request $request)
    {
        $data = Service::find($request->ServiceID);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ServiceName' => 'required',
            'ServiceAmount' => 'required',
            'ServiceDetails' => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data->ServiceName = $request->ServiceName;
        $data->ServiceAmount = $request->ServiceAmount;
        $data->ServiceClaimAmount = $request->ServiceClaimAmount;
        $data->ServiceDetails = $request->ServiceDetails;

        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => $data
        ]);
    }

    /**
     * Remove the specified partner (Admin only)
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (auth()->user()->RoleID !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Service not found'
            ], 404);
        }
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully'
        ]);
    }
}
