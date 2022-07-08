<?php

declare(strict_types=1);

namespace App\Erp\Message\Base;

abstract class BaseErpMessage implements ErpMessageInterface
{
    /**
     * Используется в header->type Kafka сообщений
     */
    public const MESSAGE_TYPE = 'base.message';

    public const MESSAGE_FORMAT = 'json';

    public function getMessageKey(): ?string
    {
        return null;
    }

    public function getAvroSchema(): string
    {
        return '';
    }
}