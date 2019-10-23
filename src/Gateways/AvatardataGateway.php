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
 * Class AvatardataGateway.
 *
 * @see http://www.avatardata.cn/Docs/Api/fd475e40-7809-4be7-936c-5926dd41b0fe
 */
class AvatardataGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://v1.avatardata.cn/Sms/Send';

    const ENDPOINT_FORMAT = 'json';

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface     $message
     * @param Config               $config
     *
     * @return array
     *
     * @throws GatewayErrorException;
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $params = [
            'mobile' => $to->getNumber(),
            'templateId' => $message->getTemplate($this),
            'param' => implode(',', $message->getData($this)),
            'dtype' => self::ENDPOINT_FORMAT,
            'key' => $config->get('app_key'),
        ];

        $result = $this->get(self::ENDPOINT_URL, $params);

        if ($result['error_code']) {
            throw new GatewayErrorException($result['reason'], $result['error_code'], $result);
        }

        return $result;
    }
}
