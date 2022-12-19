<?php
/**
 * User: pau
 * Date: 12/17/22
 */
namespace App\Models\Graph;

class Coordinate
{
    private int $x;
    private int $y;

    public function __construct(array $coordinates)
    {
        $this->x = $coordinates[0];
        $this->y = $coordinates[1];
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }
}
