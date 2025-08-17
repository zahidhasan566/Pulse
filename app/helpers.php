<?php



if (! function_exists('generateDownloadFileLink')) {
    function generateDownloadFileLink($dataList)
    {


        foreach ($dataList as &$data) {
            foreach (['Delivery Chalan', 'G.D Copy', 'Other Document', 'GDCopy', 'DeliveryChalan', 'OtherDocument'] as $key) {
                if (isset($data[$key]) && !empty($data[$key])) {
                    $fileName = $data[$key];
                    // $localFilePath = public_path('assets/lostdocuments/' . $fileName);
                    // if (file_exists($localFilePath)) {
                    //     $data[$key] = $localBaseUrl . $fileName;
                    // } else {
                    //     $data[$key] = $s3BaseUrl . $fileName;
                    // }
                    $data[$key] = $s3BaseUrl . 'lost-documents/' . $fileName;

                    if($fileName) {
                        $url = $s3BaseUrl . 'lost-documents/' . $fileName;
                        $data[$key] = $url;
                    }else {
                        $data[$key] = null;
                    }
                }
            }
        }
        return $dataList;
    }
}

