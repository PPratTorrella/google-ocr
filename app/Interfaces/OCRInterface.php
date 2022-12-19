<?php
/**
 * User: pau
 * Date: 12/16/22
 */
namespace App\Interfaces;

interface OCRInterface
{
    public function getValue(string $fileName, string $config): string;
}
