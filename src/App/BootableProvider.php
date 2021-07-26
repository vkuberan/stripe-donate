<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace VeeKay\StripeDonate\App;

use VeeKay\StripeDonate\StripeDonate;

/**
 * Interface BootableServiceProvider
 *
 * @package Inpsyde\GoogleTagManager\App
 */
interface BootableProvider extends Provider
{

    /**
     * @param StripeDonate $plugin
     */
    public function boot(StripeDonate $plugin);
}
