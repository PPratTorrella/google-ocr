<?php
/**
 * User: pau
 * Date: 12/17/22
 */
namespace App\Models\Graph;

use App\Interfaces\AreaInterface;

class Area implements AreaInterface
{
    private Coordinate $topLeft;
    private Coordinate $topRight;
    private Coordinate $bottomLeft;
    private Coordinate $bottomRight;


    public function __debugInfo() {
        return [
            'bottom left x: ' . $this->bottomLeft->getX() . ', y: ' . $this->bottomLeft->getY(),
            'bottom right x: ' . $this->bottomRight->getX() . ', y: ' . $this->bottomRight->getY(),
            'top right x: ' . $this->topRight->getX() . ', y: ' . $this->topRight->getY(),
            'top left x: ' . $this->topLeft->getX() . ', y: ' . $this->topLeft->getY(),
        ];
    }

    /**
     * @TODO refactor Coordinate dependancy
     * @param array $coordinates starting bottom left, continuing counter clockwise, example: [[0,1], [3,1], [3,4], [0,4]]
     * @return Area
     */
    public function hidrate(array $coordinates): AreaInterface
    {
        $this->bottomLeft = new Coordinate($coordinates[0]);
        $this->bottomRight = new Coordinate($coordinates[1]);
        $this->topRight = new Coordinate($coordinates[2]);
        $this->topLeft = new Coordinate($coordinates[3]);
        return $this;
    }

    /**
     * @return Coordinate[] array
     */
    public function getCoordinates(): array
    {
        return [
            $this->bottomLeft,
            $this->bottomRight,
            $this->topRight,
            $this->topLeft,
        ];
    }

    /**
     * @return int
     */
    public function getMaxHorLength(): int
    {
        $minLeft = $this->getMinLeft();
        $maxRight = $this->getMaxRight();
        return $maxRight - $minLeft;
    }

    /**
     * @return int
     */
    public function getMaxVertLength(): int
    {
        $minBottom = $this->getMinBottom();
        $maxTop = $this->getMaxTop();
        return $maxTop - $minBottom;
    }

    /**
     * @return int
     */
    public function getmiddlePointRightY(): int
    {
        return ($this->topRight->getY() + $this->bottomRight->getY()) / 2;
    }

    /**
     * Aboslute number gained from left to right
     * @return int
     */
    public function getSlopeDifference(): int
    {
        return $this->topLeft->getY() - $this->topRight->getY();
    }

    /**
     * @return int x-axis
     */
    public function getMaxRight(): int
    {
        return max($this->topRight->getX(), $this->bottomRight->getX());
    }

    /**
     * @return int x-axis
     */
    private function getMinLeft(): int
    {
        return min($this->topLeft->getX(), $this->bottomLeft->getX());
    }

    /**
     * @return int y-axis
     */
    private function getMinBottom(): int
    {
        return min($this->bottomLeft->getY(), $this->bottomRight->getY());
    }

    /**
     * @return int y-axis
     */
    private function getMaxTop(): int
    {
        return max($this->topLeft->getY(), $this->topRight->getY());
    }
}
