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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto\FulfillmentGroup\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Fulfillment setId(string $id)
 * @method Fulfillment setStatus(string $status)
 * @method Fulfillment setCarrier(string $carrier)
 * @method Fulfillment setServiceLevel(string $serviceLevel)
 * @method Fulfillment setTracking(string $tracking)
 * @method Fulfillment setFulfillmentPackages(object[] $fulfillmentPackages)
 * @method string getId()
 * @method string getStatus()
 * @method string getCarrier()
 * @method string getServiceLevel()
 * @method string getTracking()
 * @method object[] getFulfillmentPackages()
 *
 * @codeCoverageIgnore
 */
class Fulfillment extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'string'],
            'status' => ['type' => 'string'],
            'carrier' => ['type' => 'string'],
            'serviceLevel' => ['type' => 'string'],
            'tracking' => ['type' => 'number'],
            'fulfillmentPackages' => [
                'type' => 'array',
                'items' => ['type' => 'object']
            ]
        ],
        'additionalProperties' => true,
    ];
}
