<?php

namespace ABLab\Accessor\Response;

interface TreatmentResponseInterface
{
    public function getTreatment(): string;

    public function getTreatmentType(): string;
}