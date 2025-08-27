<?php

namespace App\Http\Controllers\Admin\Reports;
use App\Models\ShopInformation;
use App\Traits\CommonTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use CommonTrait;

    public function getAllShopInformation(Request $request){
        $CurrentPage = $request->pagination['current_page'];
        $PerPage = 20;
        $Export = $request->Export;
        $CustomerCode = $request->CustomerCode;
        $dateFrom = $request->DateFrom;
        $dateTo = $request->DateTo;
        $userID = Auth::user()->UserId;

        if ($Export == 'Y'){
            $CurrentPage = '%';
        }
        $sql = "exec usp_reportAllSalesInformation '$dateFrom', '$dateTo','$userID','$PerPage','$CurrentPage'";
        return $this->getReportData($sql, $PerPage, $CurrentPage, $Export);
    }
}
