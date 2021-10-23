<?php

namespace ABLab\Accessor\Response;

use ABLab\Accessor\Request\GetTreatmentRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class TreatmentResponse implements TreatmentResponseInterface, Arrayable, Jsonable
{
    protected ?string $rawResponse;
    protected array $arrayConvertedResponse;
    protected string $treatmentAvailable;
    protected ?GetTreatmentRequest $treatmentRequest;


    protected string $treatmentType;


    public function __construct(string $treatmentAvailable, string $treatmentType)
    {
        $this->rawResponse = null;
        $this->arrayConvertedResponse = [];
        $this->treatmentRequest = null;

        $this->treatmentAvailable = $treatmentAvailable;
        $this->treatmentType = $treatmentType;
    }

    /**
     * @return string|null
     */
    public function getRawResponse(): ?string
    {
        return $this->rawResponse;
    }

    /**
     * @param string|null $rawResponse
     */
    public function setRawResponse(?string $rawResponse): TreatmentResponse
    {
        $this->rawResponse = $rawResponse;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayConvertedResponse(): array
    {
        return $this->arrayConvertedResponse;
    }

    /**
     * @param array $arrayConvertedResponse
     */
    public function setArrayConvertedResponse(array $arrayConvertedResponse): TreatmentResponse
    {
        $this->arrayConvertedResponse = $arrayConvertedResponse;
        return $this;
    }

    /**
     * @return GetTreatmentRequest|null
     */
    public function getTreatmentRequest(): ?GetTreatmentRequest
    {
        return $this->treatmentRequest;
    }

    /**
     * @param GetTreatmentRequest|null $treatmentRequest
     * @return TreatmentResponse
     */
    public function setTreatmentRequest(?GetTreatmentRequest $treatmentRequest): TreatmentResponse
    {
        $this->treatmentRequest = $treatmentRequest;
        return $this;
    }

    public function toArray()
    {
        return [
            //'raw' => $this->rawResponse,
            //'response' => $this->arrayConvertedResponse,
            'treatment' => $this->treatmentAvailable,
            //'request' => $this->treatmentRequest->toArray(),
            'treatmentType' => $this->treatmentType,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function getTreatment(): string
    {
        return $this->treatmentAvailable;
    }

    public function getTreatmentType(): string
    {
        return $this->treatmentType;
    }
}
