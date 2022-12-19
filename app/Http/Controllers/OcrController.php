<?php


namespace App\Http\Controllers;

use App\Interfaces\OCRInterface;
use Illuminate\Routing\Controller as BaseController;

class OcrController extends BaseController
{

    /**
     * @param OCRInterface $ocrService
     * @return void
     */
    public function index(OCRInterface $ocrService): void
    {
        $carBillConfig = 'car_bill.id';

        $fileNames = [ #in public folder
            'test_image_1.png',
            'test_image_2.png',
            'test_image_3.png',
        ];

        foreach ($fileNames as $fileName) {
            $identityNumber = $ocrService->getValue($fileName, $carBillConfig);
            echo "Identity number ($fileName) is: " . $identityNumber . PHP_EOL;
        }

        /*
         * Case where ID number is found in two seperate recognized text instances are not handled well (test_image_3.png)
         * Multiple results found inside same target area get overridden, so probably area config (car_bill.id) needs better or more specific tuning
         */
    }



}
