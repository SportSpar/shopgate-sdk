<?php

/**
 * Copyright Shopgate Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701 <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk;

use Dto\Exceptions\InvalidDataTypeException;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\DTO\Async\Factory;

class Client implements ClientInterface
{
    /**
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

    /**
     * Client constructor.
     *
     * @param GuzzleClientInterface $guzzleClient
     */
    public function __construct(GuzzleClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param array $params
     *
     * @return ResponseInterface
     * @throws InvalidDataTypeException
     */
    public function doRequest(array $params)
    {
        if (!$this->isDirect($params)) {
            return $this->triggerEvent($params);
        }

        try {
            $response = $this->guzzleClient->request(
                $params['method'],
                $params['path'],
                [
                    'query' => ['service' => $params['service']] + (isset($params['query']) ? $params['query'] : []),
                    'json'  => isset($params['body']) ? $params['body']->toJson() : '{}'
                ]
            );
        } catch (GuzzleException $e) {
            //todo-sg: exception handling
            echo $e->getMessage();
        }

        return $response;
    }

    /**
     * @param array $params
     *
     * @throws InvalidDataTypeException
     */
    private function triggerEvent(array $params)
    {
        $values = [$params['body']];
        if ($params['action'] === 'create') {
            $key    = array_keys($params['body'])[0];
            $values = $params['body'][$key];
        }

        $factory = new Factory();
        foreach ($values as $payload) {
            $factory->addEvent($params['action'], $params['entityId'], $params['entity'], $payload);
        }

        try {
            $this->guzzleClient->request(
                'post',
                'events',
                [
                    'json'        => $factory->getRequest()->toJson(),
                    'http_errors' => false
                ]
            );
        } catch (GuzzleException $e) {
            //todo-sg: handle exception
            echo $e->getMessage();
        }
    }

    /**
     * @param array $params
     *
     * @return bool
     * @todo-sg: unit tests
     */
    public function isDirect(array $params)
    {
        return (!isset($params['requestType']) && $params['method'] === 'get') || $params['requestType'] === 'direct';
    }
}
