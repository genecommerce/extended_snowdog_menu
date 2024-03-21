<?php

declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model;

use Magento\Logging\Model\Event;
use Magento\Logging\Model\Processor;

class Logging
{
    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly \Magento\Framework\App\RequestInterface $request,
        private readonly \Psr\Log\LoggerInterface $logger
    ) {
    }

    /**
     * Handler for snowdog menu saved
     *
     * @param array $config
     * @param Event $eventModel
     * @param Processor $processor
     * @return mixed
     */
    public function postDispatchMenuSaved(array $config, Event $eventModel, Processor $processor)
    {
        $serializedNodes = $this->request->getParam('serialized_nodes');
        if (is_string($serializedNodes)) {
            $this->logger->info("Snowdog Menu item saved", compact('serializedNodes'));
        }
    }
}
