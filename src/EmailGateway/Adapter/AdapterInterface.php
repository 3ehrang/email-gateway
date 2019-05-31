<?php
/**
 * Adapter Interface
 */

namespace EGateway\Adapter;

/**
 * Each Adapter must implement this interface in order to connect with a email platform.
 */
interface AdapterInterface
{
    public function __construct(array $data, array $config);

    public function send();
}
