<?php

namespace ABLab\Accessor\Request;

use ABLab\Accessor\Data\ApplicationStage;
use ABLab\Accessor\Data\FeatureTreatment;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class GetTreatmentRequest implements Arrayable, Jsonable
{
    private string $featureName;
    private ?string $applicationStage;
    private ?string $application;


    private ?string $entityId;
    private ?string $defaultTreatment;

    /**
     * @throws Exception
     */
    public function __construct(string  $featureName,
                                ?string $application,
                                ?string $applicationStage,
                                ?string $entityId,
                                ?string $defaultTreatment = null)
    {
        $this->featureName = $featureName;
        $this->application = $application;
        $this->applicationStage = $applicationStage;
        $this->entityId = $entityId;
        $this->defaultTreatment = $defaultTreatment;
    }

    /**
     * @param string $applicationStage
     * @return GetTreatmentRequest
     */
    public function setApplicationStage(string $applicationStage): GetTreatmentRequest
    {
        $this->applicationStage = $applicationStage;
        return $this;
    }

    /**
     * @param string $application
     * @return GetTreatmentRequest
     */
    public function setApplication(string $application): GetTreatmentRequest
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param string $defaultTreatment
     * @return GetTreatmentRequest
     */
    public function setDefaultTreatment(string $defaultTreatment): GetTreatmentRequest
    {
        $this->defaultTreatment = $defaultTreatment;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function validateInputs()
    {
        if (!in_array($this->applicationStage, array_keys(ApplicationStage::toList()))) {
            throw new Exception("{$this->applicationStage} is not a supported stage.");
        }

        if (!in_array($this->defaultTreatment, array_keys(FeatureTreatment::toList()))) {
            throw new Exception("{$this->defaultTreatment} is not a supported treatment.");
        }
    }

    /**
     * @return string
     */
    public function getFeatureName(): string
    {
        return $this->featureName;
    }

    /**
     * @return ?string
     */
    public function getApplicationStage(): ?string
    {
        return $this->applicationStage;
    }

    /**
     * @return ?string
     */
    public function getApplication(): ?string
    {
        return $this->application;
    }

    /**
     * @return string|null
     */
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * @return string|null
     */
    public function getDefaultTreatment(): ?string
    {
        return $this->defaultTreatment;
    }

    public function toArray()
    {
        return [
            'featureName' => $this->featureName,
            'application' => $this->application,
            'applicationStage' => $this->applicationStage,
            'entityId' => $this->entityId,
            'defaultTreatment' => $this->defaultTreatment
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), true);
    }
}