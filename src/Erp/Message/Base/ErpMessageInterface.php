<?php

declare(strict_types=1);

namespace App\Erp\Message\Base;

/**
 * Все классы, реализующие данный интерфейс
 * будут загружены в MessageClassNameResolver
 */
interface ErpMessageInterface
{
    /**
     * @return string|null Ключ сообщения в Kafka (null, если сообщение без ключа)
     */
    public function getMessageKey(): ?string;
}