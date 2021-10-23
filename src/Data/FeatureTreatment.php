<?php

namespace ABLab\Accessor\Data;

abstract class FeatureTreatment
{
    const C = "C";
    const T1 = "T1";
    const T2 = "T2";
    const T3 = "T3";

    public static function toList()
    {
        return [
            FeatureTreatment::C => FeatureTreatment::C,
            FeatureTreatment::T1 => FeatureTreatment::T1,
            FeatureTreatment::T2 => FeatureTreatment::T2,
            FeatureTreatment::T3 => FeatureTreatment::T3,
        ];
    }
}
