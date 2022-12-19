<?php
/**
 * User: pau
 * Date: 12/17/22
 */
namespace App\Services\Graph;

use App\Interfaces\AreaInterface;
use App\Interfaces\TargetInterface;

class Target implements TargetInterface
{
    private AreaInterface $labelArea;
    private AreaInterface $targetArea;

    public function __construct(AreaInterface $targetArea)
    {
        $this->targetArea = $targetArea;
    }

    /**
     * Sets tipical form target area in relative dimensions from its left-sided label
     *
     * @param AreaInterface $labelArea
     * @param float $horMultiplier caluclate targets horizontal length by multiplying to label's horizontal length. For twice as long set 2
     * @param float $vertMultiplier caluclate targets vertical length by multiplying to label's vertical length.
     * @param float $pushLeft target moves left overlapping some of the label by multiplying to label's horizontal length
     * @param float $pushDown target moves down by multiplying to label's vertical length
     * @return Target
     */
    public function setTargetAreaByLabel(AreaInterface $labelArea, float $horMultiplier = 1, float $vertMultiplier = 1, float $pushLeft = 0, float $pushDown = 0): Target
    {
        $this->labelArea = $labelArea;
        $coordinatesData = $this->calculateTargetArea($horMultiplier, $vertMultiplier, $pushLeft, $pushDown);
        $this->targetArea = $this->targetArea->hidrate($coordinatesData);
        return $this;
    }

    /**
     * Checks if area is inside target area
     *
     * @param AreaInterface $area
     * @return bool
     */
    public function insideTarget(AreaInterface $area): bool
    {
        $areaCo = $area->getCoordinates();
        $targetCo = $this->targetArea->getCoordinates();

        $bottomLeftIsInside = (($areaCo[0]->getX() > $targetCo[0]->getX()) and ($areaCo[0]->getY() > $targetCo[0]->getY()));
        $bottomRightIsInside = (($areaCo[1]->getX() < $targetCo[1]->getX()) and ($areaCo[1]->getY() > $targetCo[1]->getY()));
        $topRightIsInside = (($areaCo[2]->getX() < $targetCo[2]->getX()) and ($areaCo[2]->getY() < $targetCo[2]->getY()));
        $topLeftIsInside = (($areaCo[3]->getX() > $targetCo[3]->getX()) and ($areaCo[3]->getY() < $targetCo[3]->getY()));

        return ($bottomLeftIsInside and $bottomRightIsInside and $topRightIsInside and $topLeftIsInside);
    }

    /**
     * @param float $horMultiplier caluclate targets horizontal length by multiplying label's horizontal length. So if target is twice as long as label set to 2
     * @param float $vertMultiplier caluclate targets vertical length by multiplying label's vertical length.
     * @param float $pushLeft target moves left overlapping some of the label. Number should be a % of label's horizontal length
     * @param float $pushDown target moves down. Number should be a % of label's vertical length
     * @return array coordinates raw data, starting bottom left, continuing counter clockwise, example: [[0,1], [3,1], [3,4], [0,4]]
     */
    private function calculateTargetArea(float $horMultiplier = 1, float $vertMultiplier = 1, float $pushLeft = 0, float $pushDown = 0): array
    {
        $middlePointRightY = $this->labelArea->getmiddlePointRightY();
        $slopeDifference = $this->labelArea->getSlopeDifference();
        $horLength = $this->labelArea->getMaxHorLength();
        $vertLength = $this->labelArea->getMaxVertLength();

        $targetHorLength = $horLength * $horMultiplier;
        $targetVertLength = $vertLength * $vertMultiplier;
        $targetLeftX = $this->labelArea->getMaxRight() - ($pushLeft * $horLength);
        $targetRightX = $targetLeftX + $targetHorLength;
        $targetLeftYTop = $middlePointRightY + ($targetVertLength / 2) - ($pushDown * $vertLength);
        $targetLeftYBottom = $middlePointRightY - ($targetVertLength / 2) - ($pushDown * $vertLength);
        $targetSlopeYModifier = $slopeDifference * $horMultiplier;

        $targetBottomLeft = [$targetLeftX, $targetLeftYBottom];
        $targetBottomRight = [$targetRightX, $targetLeftYBottom + $targetSlopeYModifier];
        $targetTopRight = [$targetRightX, $targetLeftYTop + $targetSlopeYModifier];
        $targetTopLeft = [$targetLeftX, $targetLeftYTop];

        return [$targetBottomLeft, $targetBottomRight, $targetTopRight, $targetTopLeft];
    }

}
