<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Settings\Agents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $roleId = Auth::user()->RoleID;

        // Query for totals
        $query = DB::table('sales')
            ->join('payments', 'sales.SaleID', '=', 'payments.SaleID')
            ->select(
                DB::raw('COUNT(sales.SaleID) as total_sales'),  // Count of sales
                DB::raw('SUM(payments.Amount) as total_amount_paid'),  // Sum of payments
                DB::raw('SUM(payments.AgentCommission) as total_agent_commission')  // Sum of agent commission
            );

        // If the role is Agent, filter by the logged-in user's AgentID
        if ($roleId == 'Agent') {
            $findAgentId = Agents::where('AgentCode', Auth::user()->UserID)->first();
            $query->where('sales.AgentID', $findAgentId->AgentID);
        }

        // Execute the query
        $result = $query->first();
        $totalSales = $result->total_sales;
        $totalAmountPaid = $result->total_amount_paid;
        $totalAgentCommission = $result->total_agent_commission;

        return response()->json([
            'total_sales' => $totalSales,
            'total_amount_paid' => $totalAmountPaid,
            'total_agent_commission' => $totalAgentCommission,
        ]);

    }
}
