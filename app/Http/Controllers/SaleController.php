<?php

namespace App\Http\Controllers;

use App\Models\PackageMaster;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Sales;
use App\Models\Settings\Agents;
use App\Models\User;
use App\Traits\CodeGeneration;
use Carbon\Carbon;
use Dotenv\Util\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    use CodeGeneration;
    public function getSupportingData()
    {
        try {
            // Fetch all partners, packages, and agents
            $partners = Partner::all();
            $packages = PackageMaster::all();
            $agents = Agents::select('AgentID',   DB::raw("CONCAT(AgentName,'-',AgentCode,'-',AgentPhone) AS AgentName"))->get();  // Get all agents

            return response()->json([
                'status' => 'success',
                'data' => [
                    'partners' => $partners,
                    'packages' => $packages,
                    'agents' => $agents  // Include agents in the response
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching supporting data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Sales::select([
                    'sales.SaleID',
                    'sales.FullName',
                    'sales.Email',
                    'sales.ProfilePicture',
                    'sales.MobileNumber',
                    'PackageMaster.PackageName',
                    'Partners.PartnerName',
                    'Agents.AgentName',
                    'sales.CreatedAt'
                ])
                ->join('PackageMaster', 'sales.PackageID', '=', 'PackageMaster.PackageID')
                ->join('Partners', 'Sales.PartnerID', '=', 'Partners.PartnerID')
                ->join('Agents', 'sales.AgentID', '=', 'Agents.AgentID')
                ->orderBy('CreatedAt', 'desc');

            // Add search functionality if needed
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('FullName', 'like', "%{$search}%")
                        ->orWhere('Email', 'like', "%{$search}%")
                        ->orWhere('MobileNumber', 'like', "%{$search}%");
                });
            }

            $sales = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $sales
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching sales: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'PartnerID' => 'required|exists:partners,PartnerID',
                'PackageID' => 'required',
                'AgentID' => 'required|exists:agents,AgentID',
                'FullName' => 'required|string|max:255',
                'visitType' => 'required|string|max:255',
                'Email' => 'nullable|email|max:255',
                'Password' => 'nullable|string|min:6',
                'CountryCode' => 'nullable|string|max:10',
                'MobileNumber' => 'required|string|max:20',
                'AdditionalNumber' => 'nullable|string|max:20',
                'DOB' => 'nullable|date',
                'Gender' => 'nullable|in:Male,Female,Other',
                'MaritalStatus' => 'nullable|in:Single,Married,Divorced,Widowed',
                'GeoLocation' => 'nullable|string|max:255',
                'Height' => 'nullable|numeric',
                'Weight' => 'nullable|numeric',
                'NIDPassportNumber' => 'nullable|string|max:50',
                'BankDetails' => 'nullable|string|max:500',
                'VisitDate' => 'required',
                'ExpireDate' => 'nullable|date',

                // Nominee Information
                'NomineeName' => 'nullable|string|max:255',
                'NomineePhone' => 'nullable|string|max:20',
                'NomineeGender' => 'nullable|in:Male,Female,Other',
                'NomineeRelationship' => 'nullable|string|max:100',
                'NomineeDOB' => 'nullable|date',
                'NomineeBankDetails' => 'nullable|string|max:500',

                // Medical Information
                'BloodGroup' => 'nullable|string|max:10',
                'DiseaseInfo' => 'nullable|string|max:1000',

                // Payment Information
                'PromoCode' => 'nullable|string|max:100',
                'Wallet' => 'nullable|string|max:255',
                'Currency' => 'nullable|string|max:50',
                'RefundOption' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate the MembershipID (auto-generate)
            //$membershipID = 'MEM-' . strtoupper(Str::uuid()->toString());
            $membershipID = $this->generateMemberShipCode();

            // Hash password if provided
            //$hashedPassword = $request->Password ? Hash::make($request->Password) : null;

            // Insert sale data
            $sale = Sales::create([
                'PartnerID' => $request->PartnerID,
                'PackageID' => $request->PackageID,
                'AgentID' => $request->AgentID,
                'FullName' => $request->FullName,
                'Email' => $request->Email,
                'CountryCode' => $request->CountryCode ?? '+880',
                'MobileNumber' => $request->MobileNumber,
                'AdditionalNumber' => $request->AdditionalNumber,
                'MembershipID' => $membershipID,
                'DOB' => $request->DOB,
                'Gender' => $request->Gender,
                'MaritalStatus' => $request->MaritalStatus,
                'ProfilePicture' => $request->ProfilePicture ? $request->file('ProfilePicture')->store('customer-profile', 'public'):'',
                'GeoLocation' => $request->GeoLocation,
                'Height' => $request->Height,
                'Weight' => $request->Weight,
                'NIDPassportNumber' => $request->NIDPassportNumber,
                'BankDetails' => $request->BankDetails,
                'VisitDate' => $request->VisitDate ?? Carbon::now(),
                'RegistrationDate' => $request->RegistrationDate ?? now()->toDateString(),
                'ExpireDate' => $request->ExpireDate,
                'visitType' => $request->visitType,

                // Nominee Information
                'NomineeName' => $request->NomineeName,
                'NomineePhone' => $request->NomineePhone,
                'NomineeGender' => $request->NomineeGender,
                'NomineeRelationship' => $request->NomineeRelationship,
                'NomineeDOB' => $request->NomineeDOB,
                'NomineeBankDetails' => $request->NomineeBankDetails,

                // Medical Information
                'BloodGroup' => $request->BloodGroup,
                'DiseaseInfo' => $request->DiseaseInfo,

                'CreatedAt' => now(),
                'UpdatedAt' => now(),
            ]);

//            if ($request->hasFile('ProfilePicture')) {
//                $logoPath = $request->file('ProfilePicture')->store('customer-profile', 'public');
//                $partner->PartnerLogo = $logoPath;
//            }

            //Insert  User Manager
            $user = new User();
            $user->UserID = $membershipID;
            $user->Name = $request->FullName;
            $user->Email = $request->Email;
            $user->Mobile = $request->MobileNumber;
            $user->Address = '';
            $user->NID = $request->NIDPassportNumber;
            $user->RawPassword = ('123456');
            $user->Password = bcrypt('123456');
            $user->RoleID = 'Customer';
            $user->Status = 1;
            $user->CreatedBy = Auth::user()->UserID;
            $user->UpdatedBy = Auth::user()->UserID;
            $user->CreatedAt = \Illuminate\Support\Carbon::now()->format('Y-m-d H:i:s');
            $user->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();

            DB::commit();
//            // Insert payment data
//            $payment = Payment::create([
//                'SaleID' => $sale->SaleID,
//                'PromoCode' => $request->PromoCode,
//                'Bank' => $request->Bank,
//                'Wallet' => $request->Wallet,
//                'Currency' => $request->Currency,
//                'RefundOption' => $request->RefundOption,
//                'Amount' => $request->Amount,
//                'Status' => 'received',
//                'PaymentDate' => now(),
//                'CreatedAt' => now(),
//                'UpdatedAt' => now(),
//            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sale created successfully',
                'data' => [
                    'SaleID' => $sale->SaleID,
                    'FullName' => $sale->FullName,
                    'MembershipID' => $membershipID
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating sale and payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $sales = Sales::find($id);

        if (!$sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sales
        ]);
    }

    public function destroy($id)
    {
        try {
            // Fetch the sale record
            $sale = Sales::findOrFail($id);

            User::where('UserID', $sale->MembershipID)->delete();


            // Delete the sale
            $sale->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sale deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting sale: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'PartnerID' => 'required|exists:partners,PartnerID',
                'PackageID' => 'required',
                'AgentID' => 'required|exists:agents,AgentID',
                'FullName' => 'required|string|max:255',
                'visitType' => 'required|string|max:255',
                'Email' => 'nullable|email|max:255',
                'Password' => 'nullable|string|min:6',
                'CountryCode' => 'nullable|string|max:10',
                'MobileNumber' => 'required|string|max:20',
                'AdditionalNumber' => 'nullable|string|max:20',
                'DOB' => 'nullable|date',
                'Gender' => 'nullable|in:Male,Female,Other',
                'MaritalStatus' => 'nullable|in:Single,Married,Divorced,Widowed',
                'GeoLocation' => 'nullable|string|max:255',
                'Height' => 'nullable|numeric',
                'Weight' => 'nullable|numeric',
                'NIDPassportNumber' => 'nullable|string|max:50',
                'BankDetails' => 'nullable|string|max:500',
                'VisitDate' => 'required',
                'ExpireDate' => 'nullable|date',

                // Nominee Information
                'NomineeName' => 'nullable|string|max:255',
                'NomineePhone' => 'nullable|string|max:20',
                'NomineeGender' => 'nullable|in:Male,Female,Other',
                'NomineeRelationship' => 'nullable|string|max:100',
                'NomineeDOB' => 'nullable|date',
                'NomineeBankDetails' => 'nullable|string|max:500',

                // Medical Information
                'BloodGroup' => 'nullable|string|max:10',
                'DiseaseInfo' => 'nullable|string|max:1000',

                // Payment Information
                'PromoCode' => 'nullable|string|max:100',
                'Wallet' => 'nullable|string|max:255',
                'Currency' => 'nullable|string|max:50',
                'RefundOption' => 'nullable|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the sale record
            $sale = Sales::findOrFail($request->SaleID);

            if ($request->hasFile('ProfilePicture')) {
                // Delete old logo if exists
                if ($sale->ProfilePicture) {
                    Storage::disk('public')->delete($sale->ProfilePicture);
                }

            }

            // Prepare update data
            $updateData = [
                'PartnerID' => $request->PartnerID,
                'PackageID' => $request->PackageID,
                'AgentID' => $request->AgentID,
                'FullName' => $request->FullName,
                'Email' => $request->Email,
                'CountryCode' => $request->CountryCode ?? '+880',
                'MobileNumber' => $request->MobileNumber,
                'AdditionalNumber' => $request->AdditionalNumber,
                'DOB' => $request->DOB,
                'Gender' => $request->Gender,
                'MaritalStatus' => $request->MaritalStatus,
                'ProfilePicture' => $request->ProfilePicture?$request->file('ProfilePicture')->store('customer-profile', 'public'):'',
                'GeoLocation' => $request->GeoLocation,
                'Height' => $request->Height,
                'Weight' => $request->Weight,
                'NIDPassportNumber' => $request->NIDPassportNumber,
                'BankDetails' => $request->BankDetails,
                'VisitDate' => $request->VisitDate ?? Carbon::now(),
                'RegistrationDate' => $request->RegistrationDate ?? now()->toDateString(),
                'ExpireDate' => $request->ExpireDate,
                'visitType' => $request->visitType,

                // Nominee Information
                'NomineeName' => $request->NomineeName,
                'NomineePhone' => $request->NomineePhone,
                'NomineeGender' => $request->NomineeGender,
                'NomineeRelationship' => $request->NomineeRelationship,
                'NomineeDOB' => $request->NomineeDOB,
                'NomineeBankDetails' => $request->NomineeBankDetails,

                // Medical Information
                'BloodGroup' => $request->BloodGroup,
                'DiseaseInfo' => $request->DiseaseInfo,

                'UpdatedAt' => now()
            ];


            // Update sale
            $sale->update($updateData);

            // Update payment
//            $payment = Payment::where('SaleID', $request->SaleID)->first();
//            if ($payment) {
//                $payment->update([
//                    'PromoCode' => $request->PromoCode,
//                    'Bank' => $request->Bank,
//                    'Wallet' => $request->Wallet,
//                    'Currency' => $request->Currency,
//                    'RefundOption' => $request->RefundOption,
//                    'Amount' => $request->Amount,
//                    'Status' => 'Pending',
//                    'UpdatedAt' => now()
//                ]);
//            }

            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating sale and payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
