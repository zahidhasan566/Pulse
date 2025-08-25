<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait CommonTrait
{
    public function getReportData($sql, $PerPage, $CurrentPage, $Export)
    {
        $conn = DB::connection('sqlsrv');

        $pdo = $conn->getPdo()->prepare($sql);
        $pdo->execute();

        $res = array();
        do {
            $rows = $pdo->fetchAll(\PDO::FETCH_ASSOC);
            $res[] = $rows;
        } while ($pdo->nextRowset());


        $NUmberOfRecord = $res[1][0]['NUmberOfRecord'];
        $pages_count = ceil($NUmberOfRecord / $PerPage);
        $last_page = $pages_count;
        $from = 1;
        $to = 100;
        if ($Export != 'Y') {
            $from = (($CurrentPage * $PerPage) + 1) - $PerPage;
            $to = ($CurrentPage * $PerPage);
        }
        $paginationData [] = [
            'current_page' => $CurrentPage,
            'last_page' => $last_page,
            'total' => (int)$NUmberOfRecord,
            'from' => $from,
            'to' => $to,
        ];
        return response()->json([
            'data' => $res[0],
            'paginationData' => $paginationData
        ]);
    }

    public function generateAgentCode(){
        $prefix = 'AGT';
        $year = date('Y');

        // Get the last agent code for this year
        $lastAgent = DB::table('agents')
            ->where('AgentCode', 'like', $prefix . $year . '%')
            ->orderBy('AgentCode', 'desc')
            ->first();

        if ($lastAgent) {
            // Extract the number part and increment
            $lastNumber = intval(substr($lastAgent->AgentCode, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: AGT2024-0001
        return $prefix . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
