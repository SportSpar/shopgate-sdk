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

namespace Shopgate\ConnectSdk\Dto\Customer;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method string getCode()
 * @method string getType()
 * @method string getName()
 * @method string getIsRequired()
 * @method AttributeValue\Get[] getValues()
 *
 * @method $this setType(string $type)
 * @method $this setName(string $name)
 * @method $this setIsRequired(boolean $isRequired)
 * @method $this setValues(AttributeValue\Create[] $values)
 *
 * @package Shopgate\ConnectSdk\Dto\Customer
 */
class Attribute extends Base
{
    const TYPE_TEXT                 = 'text';
    const TYPE_NUMBER               = 'number';
    const TYPE_BOOLEAN              = 'boolean';
    const TYPE_DATE                 = 'date';
    const TYPE_COLLECTION_OF_VALUES = 'collectionOfValues';
}
