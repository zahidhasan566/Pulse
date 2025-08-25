<?php

namespace App\Http\Controllers;

use App\Models\PackageDetails;
use App\Models\PackageMaster;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Settings\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = PackageMaster::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('PackageName', 'like', '%' . $request->search . '%')
                ->orWhere('PackageDetails', 'like', '%' . $request->search . '%');
        }

        // Retrieve data with relationships
        $data = $query->with(['services', 'partners'])  // Eager load services and partners
        ->select('PackageID', 'PackageName', 'PackageDetails', 'ElementsMeasurement', 'PackageImage')
            ->orderBy('PackageID', 'desc')
            ->paginate(10);

        // Loop through data to add unique service names as comma-separated and partner names as array
        $data->transform(function ($package) {
            // Extract distinct service names and convert to a comma-separated string
            $serviceNames = $package->services->pluck('ServiceName')->unique()->toArray();

            $package->ServicesName = implode(', ', $serviceNames);  // Join the service names into a comma-separated string

            // Extract partner names and keep them as an array
            $packageNames = $package->partners->pluck('PartnerName')->unique()->toArray();  // Keep as array
            $package->PartnersName = implode(', ', $packageNames);  // Keep as array

            return $package;
        });

        return response()->json($data);
    }
    public function getSupportingData(){
        $partner  = Partner::all();
        $service = Service::all();

        return response()->json([
            'success' => true,
            'partners' => $partner,
            'services' => $service,
        ], 201);
    }
    /**
     * Store a newly created partner
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'PackageName' => 'required',
            'ServiceID' => 'required|min:1',  // ServiceID must be an array
            'PartnerID' => 'required|min:1',  // PartnerID must be an array
            'Duration' => 'required|numeric|min:1', // Ensure Duration is a number greater than 0
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create a new package record
        $data = new PackageMaster();
        $data->PackageName = $request->PackageName;
        $data->PackageDetails = $request->PackageDetails;
        $data->Duration = $request->Duration;

        // Calculate ElementsMeasurement by summing the selected services' amounts

        // Decode the ServiceID and PartnerID JSON strings into arrays
        $serviceIDs = json_decode($request->ServiceID, true);
        $partnerIDs = json_decode($request->PartnerID, true);
        $elementsMeasurement = 0;
        foreach ($serviceIDs as $serviceID) {
            $service = Service::find($serviceID);
            if ($service) {
                $elementsMeasurement += $service->ServiceAmount; // Sum the service amounts
            }
        }

        $data->ElementsMeasurement = $elementsMeasurement;

        // Handle package image upload
        if ($request->hasFile('PackageImage')) {
            $logoPath = $request->file('PackageImage')->store('package-image', 'public');
            $data->PackageImage = $logoPath;
        }

        $data->save();

        // Now, insert the combinations of ServiceID and PartnerID into the PackageDetails table
        foreach ($serviceIDs as $serviceID) {
            foreach ($partnerIDs as $partnerID) {
                PackageDetails::create([
                    'PackageID' => $data->PackageID, // Use the saved PackageID
                    'ServiceID' => $serviceID,
                    'PartnerID' => $partnerID
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified partner
     */
    public function show($id)
    {
        $package = PackageMaster::where('PackageID', $id)
            ->first();

        if (!$package) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        // Prepare selected ServiceIDs and PartnerIDs for multiselect in frontend
        $package->selectedServiceIDs = $package->services()
            ->distinct()
            ->pluck('services.ServiceID')  // Specify table alias for ServiceID
            ->toArray();

        $package->selectedPartnerIDs = $package->partners()
            ->distinct()
            ->pluck('partners.PartnerID')  // Specify table alias for PartnerID
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $package
        ]);
    }

    /**
     * Update the specified partner
     */
    public function update(Request $request)
    {
        // Retrieve the package to be updated
        $data = PackageMaster::find($request->PackageID);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'PackageName' => 'required',
            'ServiceID' => 'required|min:1',  // Ensure ServiceID is an array
            'PartnerID' => 'required|min:1',  // Ensure PartnerID is an array
            'Duration' => 'required|numeric|min:1', // Duration should be numeric and greater than 0
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update the package details
        $data->PackageName = $request->PackageName;
        $data->PackageDetails = $request->PackageDetails;
        $data->Duration = $request->Duration;

        $serviceIDs = json_decode($request->ServiceID, true);
        $partnerIDs = json_decode($request->PartnerID, true);
        // Calculate ElementsMeasurement by summing the selected services' amounts
        $elementsMeasurement = 0;
        foreach ($serviceIDs as $serviceID) {
            $service = Service::find($serviceID);
            if ($service) {
                $elementsMeasurement += $service->ServiceAmount; // Sum the service amounts
            }
        }

        $data->ElementsMeasurement = $elementsMeasurement;

        // Handle package image upload if a new image is provided
        if ($request->hasFile('PackageImage')) {
            // Delete old image if it exists
            if ($data->PackageImage) {
                Storage::disk('public')->delete($data->PackageImage);
            }

            $logoPath = $request->file('PackageImage')->store('package-image', 'public');
            $data->PackageImage = $logoPath;
        }

        $data->save();

        PackageDetails::where('PackageID', $data->PackageID)->delete();

        // Now, insert the new combinations of ServiceID and PartnerID into the PackageDetails table
        foreach ($serviceIDs as $serviceID) {
            foreach ($partnerIDs as $partnerID) {
                PackageDetails::create([
                    'PackageID' => $data->PackageID,  // Use the saved PackageID
                    'ServiceID' => $serviceID,
                    'PartnerID' => $partnerID
                ]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
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

        $data = PackageMaster::find($id);
        $dataDetails = PackageDetails::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found'
            ], 404);
        }
        $data->delete();
        $dataDetails->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }
}
