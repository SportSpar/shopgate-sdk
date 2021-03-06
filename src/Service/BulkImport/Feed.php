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

namespace Shopgate\ConnectSdk\Service\BulkImport;

use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\StreamInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\File;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\Stream;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\ShopgateSdk;

class Feed
{
    /** @var  ClientInterface */
    protected $client;

    /** @var  string */
    protected $importReference;

    /** @var  string */
    private $url;

    /** @var StreamInterface | resource */
    protected $stream;

    /** @var string */
    protected $handlerType;

    /** @var array */
    protected $requestBodyOptions;

    /** @var bool */
    protected $isFirstItem = true;

    /**
     * @param ClientInterface $client
     * @param string          $importReference
     * @param string          $handlerType
     * @param array           $requestBodyOptions
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function __construct(
        ClientInterface $client,
        $importReference,
        $handlerType,
        $requestBodyOptions = []
    ) {
        $this->client = $client;
        $this->importReference = $importReference;
        $this->handlerType = $handlerType;
        $this->requestBodyOptions = $requestBodyOptions;

        $this->client = $client;
        $this->url = $this->getUrl();

        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream = stream_for();
                $this->stream->write('[');
                break;
            case File::HANDLER_TYPE:
                $this->stream = tmpfile();
                fwrite($this->stream, '[');
                break;
        }
    }

    /**
     * @return string
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    protected function getUrl()
    {
        $response = $this->client->doRequest(
            [
                'method' => 'post',
                'json' => $this->requestBodyOptions,
                'requestType' => 'direct',
                'service' => 'import',
                'path' => 'imports/' . $this->importReference . '/' . 'urls',
            ]
        );

        $response = json_decode($response->getBody(), true);

        return $response['url'];
    }

    /**
     * @return string
     */
    protected function getItemDivider()
    {
        return $this->isFirstItem
            ? ''
            : ',';
    }

    /**
     * @throws AuthenticationInvalidException
     * @throws UnknownException
     * @throws NotFoundException
     * @throws RequestException
     * @throws InvalidDataTypeException
     */
    public function end()
    {
        $requestOption = [];
        $requestOption['method'] = 'PUT';
        $requestOption['url'] = $this->url;
        $requestOption['requestType'] = ShopgateSdk::REQUEST_TYPE_DIRECT;

        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream->write(']');
                $this->client->doRequest($requestOption + ['body' => (string)$this->stream]);
                break;
            case File::HANDLER_TYPE:
                fwrite($this->stream, ']');
                fseek($this->stream, 0);
                $requestOption['body'] = fread($this->stream, filesize(stream_get_meta_data($this->stream)['uri']));
                fclose($this->stream);
                break;
        }

        if (isset($requestOption['body'])) {
            $this->client->doRequest($requestOption);
        }
    }
}
