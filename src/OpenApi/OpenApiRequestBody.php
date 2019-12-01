<?php

namespace ByJG\ApiTools\OpenApi;

use ByJG\ApiTools\Base\Body;
use ByJG\ApiTools\Exception\InvalidDefinitionException;
use ByJG\ApiTools\Exception\RequiredArgumentNotFound;

class OpenApiRequestBody extends Body
{
    /**
     * @param $body
     * @return bool
     * @throws Exception\DefinitionNotFoundException
     * @throws Exception\GenericSwaggerException
     * @throws Exception\InvalidRequestException
     * @throws Exception\NotMatchedException
     * @throws InvalidDefinitionException
     * @throws RequiredArgumentNotFound
     */
    public function match($body)
    {
        if (isset($this->structure['content']) || isset($this->structure['$ref'])) {
            if (isset($this->structure['required']) && $this->structure['required'] === true && empty($body)) {
                throw new RequiredArgumentNotFound('The body is required but it is empty');
            }

            if (isset($this->structure['$ref'])) {
                return $this->matchSchema($this->name, $this->structure, $body);
            }

            return $this->matchSchema($this->name, $this->structure['content'][key($this->structure['content'])]['schema'], $body);
        }

        if (!empty($body)) {
            throw new InvalidDefinitionException('Body is passed but there is no request body definition');
        }

        return false;
    }
}
