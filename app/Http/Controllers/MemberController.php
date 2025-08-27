<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Settings\Agents;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    use CommonTrait;
    public function index(Request $request)
    {
        try {

            $roleId =  Auth::user()->RoleID;
            $query = Sales::select([
                'sales.SaleID',
                'Payments.PaymentID',
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
                ->Join('Payments', 'sales.SaleID', '=', 'Payments.SaleID')
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

            if($roleId=='Customer'){
                $query->where('sales.MobileNumber', Auth::user()->Mobile);
            }
            if($roleId=='Agent'){
                $agent = Agents::select('AgentId')->where('AgentCode', Auth::user()->UserID)->first();
                if($agent){
                    $query->where('sales.AgentId', $agent->AgentId);
                }
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
}
