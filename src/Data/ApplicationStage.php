<?php

namespace ABLab\Accessor\Data;

abstract class ApplicationStage
{
    const DEVELOPMENT = 'DEVELOPMENT';
    const PRODUCTION = 'PRODUCTION';

    public static function toList()
    {
        return [
            ApplicationStage::DEVELOPMENT => ApplicationStage::DEVELOPMENT,
            ApplicationStage::PRODUCTION => ApplicationStage::PRODUCTION,
        ];
    }
}
