<?php

namespace ABLab\Accessor\Manager;

use ABLab\Accessor\Data\FeatureType;
use ABLab\Accessor\Data\TreatmentType;
use ABLab\Accessor\Exceptions\FeatureTypeNotAvailableException;
use ABLab\Accessor\Exceptions\InvalidApplicationException;
use ABLab\Accessor\Exceptions\TreatmentDataInvalidException;
use ABLab\Accessor\Exceptions\TreatmentNotFoundException;
use ABLab\Accessor\Manager\Redis\ConnectionAware;
use ABLab\Accessor\Manager\Traits\TreatmentCollectionHelpers;
use ABLab\Accessor\Manager\Traits\TreatmentRequestTrait;
use ABLab\Accessor\Manager\Traits\TreatmentResponseTrait;
use ABLab\Accessor\Request\GetTreatmentRequest;
use ABLab\Accessor\Response\TreatmentResponse;

use Illuminate\Support\Facades\Redis;

use Exception;

class RedisFeatureRetriever implements FeatureRetrieverInterface
{
    use ConnectionAware,
        TreatmentCollectionHelpers,
        TreatmentRequestTrait,
        TreatmentResponseTrait;

    private GetTreatmentRequest $treatmentRequest;

    private string|null $rawFeature = null;
    private array $feature = [];

    public function __construct(string $connection)
    {
        $this->setConnectionName($connection);
    }

    /**
     * Get treatment response for any request
     *
     * @param GetTreatmentRequest $treatmentRequest
     * @return TreatmentResponse
     * @throws Exception
     */
    public function getTreatment(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        // Validate inputs before continue.
        $treatmentRequest->validateInputs();

        try {
            return $this->executeGetTreatmentRequest($treatmentRequest);
        } catch (TreatmentNotFoundException $e) {
            return $this->defaultTreatmentResponse(TreatmentType::NOT_FOUND);
        } catch (TreatmentDataInvalidException $e) {
            return $this->defaultTreatmentResponse(TreatmentType::INVALID_DATA);
        } catch (InvalidApplicationException $e) {
            return $this->defaultTreatmentResponse(TreatmentType::MISSING_APPLICATION);
        } catch (FeatureTypeNotAvailableException $e) {
            return $this->defaultTreatmentResponse(TreatmentType::FEATURE_TYPE_UNAVAILABLE);
        }
    }

    /**
     * Proxy method for GetTreatment to abstract away the exception handling
     *
     * @throws TreatmentDataInvalidException
     * @throws TreatmentNotFoundException
     * @throws FeatureTypeNotAvailableException
     * @throws InvalidApplicationException
     */
    private function executeGetTreatmentRequest(GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        $this->treatmentRequest = $treatmentRequest;

        $this->rawFeature = Redis::connection($this->getConnectionName())
            ->get($this->getFeatureName());

        if ($this->rawFeature === null) {
            throw new TreatmentNotFoundException();
        }

        $this->feature = json_decode($this->rawFeature, true);
        if (!is_array($this->feature)) {
            throw new TreatmentDataInvalidException();
        }

        return $this->getTreatmentResponseForStage();
    }

    /**
     * Get Treatment for stage
     *
     * @throws InvalidApplicationException
     * @throws FeatureTypeNotAvailableException
     */
    protected function getTreatmentResponseForStage(): TreatmentResponse
    {
        if (!isset($this->feature['applications'][$this->getApplicationStage()]) || empty($this->feature['applications'][$this->getApplicationStage()])) {
            return $this->defaultTreatmentResponse(TreatmentType::MISSING_STAGE);
        }

        $applications = $this->feature['applications'][$this->getApplicationStage()];
        $applicationSetting = $this->getApplicationSetting($applications, $this->getApplication());

        // if this application is not present in feature applications
        if (null === $applicationSetting) {
            throw new InvalidApplicationException();
        }

        $overrides = $this->feature['overrides'];

        // if entity id is present, overrides are active
        if (null !== $this->getEntityId() && $applicationSetting['are_overrides_active']) {
            return $this->getTreatmentForOverrides($applicationSetting, $overrides, $this->getEntityId());
        }

        return $this->getTreatmentByFeatureType($applicationSetting);
    }


    /**
     * Get treatment for entity overrides
     *
     * @throws FeatureTypeNotAvailableException
     */
    protected function getTreatmentForOverrides($applicationSetting, $overrides, $entityId): TreatmentResponse
    {
        $overrideTreatment = collect($overrides)->where('id', $entityId)->first();
        if ($overrideTreatment) {
            return $this->defaultTreatmentResponse(TreatmentType::OVERRIDES, $overrideTreatment['treatment']);
        }

        return $this->getTreatmentByFeatureType($applicationSetting);
    }

    /**
     * Get Treatment by feature type, see C / T1 is launched, if none is launched, send C
     *
     * @param $applicationSetting
     * @return TreatmentResponse
     * @throws FeatureTypeNotAvailableException
     */
    protected function getTreatmentByFeatureType($applicationSetting): TreatmentResponse
    {
        if ($this->feature['type'] === FeatureType::EXPERIMENT) {
            throw new FeatureTypeNotAvailableException();
        }

        $treatment = $this->getLaunchedTreatment($applicationSetting);

        if ($treatment) {
            return $this->defaultTreatmentResponse(TreatmentType::LAUNCH_TREATMENT, $treatment['name']);
        }

        return $this->defaultTreatmentResponse(TreatmentType::DEFAULT);
    }
}
