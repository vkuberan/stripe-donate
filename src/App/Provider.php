<?php

# -*- coding: utf-8 -*-

namespace VeeKay\StripeDonate\App;

use VeeKay\StripeDonate\StripeDonate;

/**
 * Interface Provider
 *
 * @package VeeKay\StripeDonate\App
 */
interface Provider
{

    /**
     * @param StripeDonate $plugin
     */
    public function register(StripeDonate $plugin);
}
