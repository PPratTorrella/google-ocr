<?php
/**
 * User: pau
 * Date: 12/17/22
 */
namespace App\Interfaces;

use App\Models\Graph\Coordinate;

interface AreaInterface
{
    public function hidrate(array $coordinates): AreaInterface;
    public function getCoordinates(): array;
    public function getMaxHorLength(): int;
    public function getMaxVertLength(): int;
    public function getMaxRight(): int;
    public function getmiddlePointRightY(): int;
    public function getSlopeDifference(): int;
}
