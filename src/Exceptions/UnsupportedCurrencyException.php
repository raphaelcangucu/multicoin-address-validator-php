<?php

declare(strict_types=1);

namespace Multicoin\AddressValidator\Exceptions;

use InvalidArgumentException;

/**
 * Exception thrown when an unsupported currency is requested
 */
class UnsupportedCurrencyException extends InvalidArgumentException
{
}