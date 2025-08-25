<?php

namespace App\Traits;

use App\Models\PaymentReceive\TblPaymentReceive;
use App\Models\TblDealerInfoSetup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait CodeGeneration
{
    public function generateMemberShipCode()
    {

        $combinedCode = 'PLM-';
        $combinedLength = strlen($combinedCode);
        $maxCode = DB::select(DB::raw("select MAX(MembershipID) as MaxNo FROM Sales WHERE LEFT(MembershipID,'$combinedLength') = '$combinedCode'"));
        $maxCode = $maxCode[0]->MaxNo;
        if ($maxCode === null) {
            $nextCode = $combinedCode . '00001';
        }
        else {
            $nextCode = substr($maxCode,$combinedLength);
            $nextCodeInc = $nextCode + 1;
            $nextCode = sprintf("%0".strlen($nextCode)."d", $nextCodeInc);
            $nextCode = $combinedCode.$nextCode;
        }
        return $nextCode;
    }


}
