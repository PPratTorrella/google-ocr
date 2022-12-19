<?php
/**
 * User: pau
 * Date: 12/16/22
 */
namespace App\Services;

use App\Helpers\ImageCropper;
use App\Interfaces\AreaInterface;
use App\Interfaces\OCRInterface;
use App\Interfaces\TargetInterface;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Protobuf\Internal\RepeatedField as Texts;

class GoogleOCR implements OCRInterface
{
    const AUTH_KEY_PATH = '../service-account-file.json';
    const CONFIG_LABEL_SUFFIX = 'label_detection';
    const CONFIG_AREA_PARAMS_SUFFIX = 'target_area_params';
    const CONFIG_CROPPING_SUFFIX = 'cropping';
    const CONFIG_MIN_CHARS_SUFFIX = 'min_chars';

    private ImageAnnotatorClient $imageAnnotator;
    private AreaInterface $area;
    private TargetInterface $targetService;

    /**
     * @throws ValidationException
     */
    public function __construct(AreaInterface $area, TargetInterface $targetService)
    {
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . self::AUTH_KEY_PATH);
        $this->imageAnnotator = new ImageAnnotatorClient(['credentials' => self::AUTH_KEY_PATH]);
        $this->area = $area;
        $this->targetService = $targetService;
    }

    /**
     * @param string $fileName
     * @param string $config
     * @return string
     * @throws ApiException @TODO handle errors
     */
    public function getValue(string $fileName, string $config): string
    {
        # implementing cropping can improve OCR speed
        $fileName = ImageCropper::cropTopHalf($fileName, $config . '.' . self::CONFIG_CROPPING_SUFFIX);

        $texts = $this->getImageTexts($fileName);
        $valueMinChars = config($config . '.' . self::CONFIG_MIN_CHARS_SUFFIX);
        $targetSet = false;
        echo '<pre>';

        foreach ($texts as $text) {

//            echo $text->getDescription() . PHP_EOL;

            $labels = config($config . '.' . self::CONFIG_LABEL_SUFFIX);
            $labelMatch = in_array($text->getDescription(), $labels);

            if ($labelMatch and !$targetSet) {
                $labelArea = $this->hidrateArea($text);
                $carBillIdConfig = config($config . '.' . self::CONFIG_AREA_PARAMS_SUFFIX);
                $this->targetService->setTargetAreaByLabel($labelArea, ...$carBillIdConfig);
                $targetSet = true;
            }

            if ($targetSet) { # dont need to reset loop for left sided labels but could use config for this..
                $hasMinChars = strlen($text->getDescription()) >= $valueMinChars; //@TODO handle multi-byte
                if (!$hasMinChars) {
                    continue;
                }
                $area = $this->hidrateArea($text);
                $insideTargetArea = $this->targetService->insideTarget($area);
                if ($insideTargetArea) {
                    $value = $text->getDescription();
                    break;
                }
            }
        }

        $this->closeClient();

        return $value ?? ''; # @TODO handle not found or multiple found cases
    }

    /**
     * @param string $fileName
     * @return Texts
     * @throws ApiException
     */
    protected function getImageTexts(string $fileName): Texts
    {
        $image = file_get_contents($fileName);
        $response = $this->imageAnnotator->documentTextDetection($image);
        return $response->getTextAnnotations();
    }

    /**
     * @return void
     */
    protected function closeClient(): void
    {
        $this->imageAnnotator->close();
    }

    /**
     * (re) hidrates area
     *
     * @param $text
     * @return AreaInterface
     */
    protected function hidrateArea($text): AreaInterface
    {
        $vertices = $text->getBoundingPoly()->getVertices(); // seems gets it counter clockwise starting left bottom
        foreach ($vertices as $index => $vertex) {
            $rawArea[$index] = [$vertex->getX(), $vertex->getY()];
        }
        return $this->area->hidrate($rawArea ?? []);
    }
}
