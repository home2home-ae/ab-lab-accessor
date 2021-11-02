<?php

namespace ABLab\Accessor\Manager\Traits;

trait TreatmentCollectionHelpers
{
    protected function getApplicationSetting($applications, $application): null|array
    {
        return collect($applications)->where('unique_id', $application)->first();
    }

    protected function getLaunchedTreatment(array $applicationSetting): null|array
    {
        return collect($applicationSetting['treatments'])->where('allocation', '>=', 100)->first();
    }
}