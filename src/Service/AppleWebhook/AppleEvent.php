<?php

declare(strict_types=1);

namespace App\Service\AppleWebhook;

use ArrayObject;

/**
 * @property-read string $password
 * @property-read string $notification_type
 * @property-read string $auto_renew_product_id
 */
class AppleEvent extends ArrayObject
{
    public function __construct($array = [])
    {
        parent::__construct($array);

        $this->setFlags(self::STD_PROP_LIST);
        $this->setFlags(self::ARRAY_AS_PROPS);
    }
}
