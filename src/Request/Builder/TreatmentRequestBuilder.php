<?php

namespace ABLab\Accessor\Request\Builder;

use ABLab\Accessor\Request\GetTreatmentRequest;

class TreatmentRequestBuilder
{
    private string $featureName;
    private ?string $applicationStage = null;
    private ?string $application = null;
    private ?string $entityId = null;
    private ?string $defaultTreatment = null;

    /**
     * @return string
     */
    private function getFeatureName(): string
    {
        return $this->featureName;
    }

    /**
     * @return string
     */
    private function getApplicationStage(): ?string
    {
        return $this->applicationStage;
    }

    /**
     * @return string
     */
    private function getApplication(): ?string
    {
        return $this->application;
    }

    /**
     * @return string|null
     */
    private function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * @return string|null
     */
    private function getDefaultTreatment(): ?string
    {
        return $this->defaultTreatment;
    }

    /**
     * @return TreatmentRequestBuilder
     */
    public static function builder(): TreatmentRequestBuilder
    {
        return new TreatmentRequestBuilder();
    }

    /**
     * @param string $featureName
     * @return TreatmentRequestBuilder
     */
    public function setFeatureName(string $featureName): TreatmentRequestBuilder
    {
        $this->featureName = $featureName;
        return $this;
    }

    /**
     * @param string $applicationStage
     * @return TreatmentRequestBuilder
     */
    public function setApplicationStage(string $applicationStage): TreatmentRequestBuilder
    {
        $this->applicationStage = $applicationStage;
        return $this;
    }

    /**
     * @param string $application
     * @return TreatmentRequestBuilder
     */
    public function setApplication(string $application): TreatmentRequestBuilder
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param string|null $entityId
     * @return TreatmentRequestBuilder
     */
    public function setEntityId(?string $entityId): TreatmentRequestBuilder
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @param string|null $defaultTreatment
     * @return TreatmentRequestBuilder
     */
    public function setDefaultTreatment(?string $defaultTreatment): TreatmentRequestBuilder
    {
        $this->defaultTreatment = $defaultTreatment;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function build(): GetTreatmentRequest
    {
        return new GetTreatmentRequest(
            $this->getFeatureName(),
            $this->getApplication(),
            $this->getApplicationStage(),
            $this->getEntityId(),
            $this->getDefaultTreatment()
        );
    }
}