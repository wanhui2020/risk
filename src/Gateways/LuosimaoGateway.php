<?php

/*
 * This file is part of the yuanyou/easy-sms.
 *
 * (c) yuanyou <wuwanhui@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yuanyou\EasySms\Gateways;

use Yuanyou\EasySms\Contracts\MessageInterface;
use Yuanyou\EasySms\Contracts\PhoneNumberInterface;
use Yuanyou\EasySms\Exceptions\GatewayErrorException;
use Yuanyou\EasySms\Support\Config;
use Yuanyou\EasySms\Traits\HasHttpRequest;

/**
 * Class LuosimaoGateway.
 *
 * @see https://luosimao.com/docs/api/
 */
class LuosimaoGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATE = 'https://%s.luosimao.com/%s/%s.%s';

    const ENDPOINT_VERSION = 'v1';

    const ENDPOINT_FORMAT = 'json';

    /**
     * @param \Yuanyou\EasySms\Contracts\PhoneNumberInterface $to
     * @param \Yuanyou\EasySms\Contracts\MessageInterface     $message
     * @param \Yuanyou\EasySms\Support\Config                 $config
     *
     * @return array
     *
     * @throws \Yuanyou\EasySms\Exceptions\GatewayErrorException ;
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $endpoint = $this->buildEndpoint('sms-api', 'send');

        $result = $this->post($endpoint, [
            'mobile' => $to->getNumber(),
            'message' => $message->getContent($this),
        ], [
            'Authorization' => 'Basic '.base64_encode('api:key-'.$config->get('api_key')),
        ]);

        if ($result['error']) {
            throw new GatewayErrorException($result['msg'], $result['error'], $result);
        }

        return $result;
    }

    /**
     * Build endpoint url.
     *
     * @param string $type
     * @param string $function
     *
     * @return string
     */
    protected function buildEndpoint($type, $function)
    {
        return sprintf(self::ENDPOINT_TEMPLATE, $type, self::ENDPOINT_VERSION, $function, self::ENDPOINT_FORMAT);
    }
}