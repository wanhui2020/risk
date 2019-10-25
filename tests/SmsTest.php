<?php

/*
 * This file is part of the yuanyou/easy-sms.
 *
 * (c) yuanyou <wuwanhui@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yuanyou\Risk\Tests;

use Closure;
use PHPUnit\Framework\TestCase;
use Yuanyou\Risk\Contracts\GatewayInterface;
use Yuanyou\Risk\Contracts\MessageInterface;
use Yuanyou\Risk\Contracts\PhoneNumberInterface;
use Yuanyou\Risk\Contracts\StrategyInterface;
use Yuanyou\Risk\Exceptions\InvalidArgumentException;
use Yuanyou\Risk\Exceptions\NoGatewayAvailableException;
use Yuanyou\Risk\SmsService;
use Yuanyou\Risk\Strategies\OrderStrategy;
use Yuanyou\Risk\Support\Config;
use RuntimeException;

/**
 * Class EasySms.
 */
class SmsTest extends  TestCase
{
    protected function runTest()
    {
        return parent::runTest(); // TODO: Change the autogenerated stub
    }

    public function __construct()
    {
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'sms',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/yuanyou-sms.log',
                ],
                'sms' => [
                    'token' => 'be0c5e4151465c9dcfeb0410401bc6246aac6b619dab0ceb14a1111234ce6e3f',
                    'secret_key' => 'bc619a86ae754aa5bdbf33c19396fc94',
                    'sign_id' => 13,
                ],

            ],
        ];

        $easySms = new SmsService($config);

        try {

            $result = $easySms->send('13983087661', [
                'content' => '测试内容',
                'template' => 1,
                'data' => ["code" => 1111]
            ]);
            dd($result);
        } catch (InvalidArgumentException $e) {
            dd($e);
        } catch (NoGatewayAvailableException $e) {

            dd($e);
        }
    }
}
