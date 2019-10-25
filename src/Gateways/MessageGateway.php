<?php

/*
 * This file is part of the yuanyou/easy-sms.
 *
 * (c) yuanyou <wuwanhui@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yuanyou\Risk\Gateways;

use Yuanyou\Risk\Contracts\GatewayInterface;
use Yuanyou\Risk\Contracts\MessageInterface;
use Yuanyou\Risk\Contracts\PhoneNumberInterface;
use Yuanyou\Risk\Support\Config;

/**
 * Class Gateway.
 */
abstract class MessageGateway implements GatewayInterface
{
    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var \Yuanyou\Risk\Support\Config
     */
    protected $config;

    /**
     * @var float
     */
    protected $timeout;

    /**
     * Gateway constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Return timeout.
     *
     * @return int|mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

    /**
     * Set timeout.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);

        return $this;
    }

    /**
     * @return \Yuanyou\Risk\Support\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Yuanyou\Risk\Support\Config $config
     *
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return \strtolower(str_replace([__NAMESPACE__ . '\\', 'Gateway'], '', \get_class($this)));
    }

    /**
     * Send a short message.
     *
     * @param \Yuanyou\Risk\Contracts\PhoneNumberInterface $to
     * @param \Yuanyou\Risk\Contracts\MessageInterface $message
     * @param \Yuanyou\Risk\Support\Config $config
     *
     * @return array
     */
    abstract function send(PhoneNumberInterface $to, MessageInterface $message, Config $config);
}
