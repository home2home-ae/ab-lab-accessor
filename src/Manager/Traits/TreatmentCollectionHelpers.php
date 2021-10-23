<?php

namespace ABLab\Accessor\Manager\Traits;

trait TreatmentCollectionHelpers
{
    protected function getApplicationSetting($applications, $application): array
    {
        return collect($applications)->where('unique_id', $application)->first();
    }

    protected function getLaunchedTreatment(array $applicationSetting)
    {
        return collect($applicationSetting['treatments'])->where('allocation', '>=', 100)->first();
    }
}