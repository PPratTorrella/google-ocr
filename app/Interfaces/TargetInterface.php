<?php
/**
 * User: pau
 * Date: 12/17/22
 */
namespace App\Interfaces;

interface TargetInterface
{
    public function setTargetAreaByLabel(AreaInterface $labelArea, float $horMultiplier = 1, float $vertMultiplier = 1, float $pushLeft = 0, float $pushDown = 0): TargetInterface;
    public function insideTarget(AreaInterface $area): bool;
}
