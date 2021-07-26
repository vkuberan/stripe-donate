<?php

declare(strict_types=1);

# -*- coding: utf-8 -*-

namespace VeeKay\StripeDonate;

use VeeKay\StripeDonate\App\BootableProvider;
use VeeKay\StripeDonate\App\Provider;
// use Inpsyde\GoogleTagManager\Event\BootstrapEvent;
use VeeKay\StripeDonate\Exception\AlreadyBootedException;
use VeeKay\StripeDonate\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

// phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType

/**
 * @package Veekay\StripeDonate
 */
final class StripeDonate implements ContainerInterface
{

    private $values = [];

    private $providers = [];

    private $booted = false;

    /**
     * @param string $id
     * @param $value
     *
     * @return StripeDonate
     * @throws AlreadyBootedException
     */
    public function set(string $id, $value): self
    {
        if ($this->booted) {
            throw new AlreadyBootedException();
        }

        $this->values[$id] = $value;

        return $this;
    }

    /**
     * @param Provider $serviceProvider
     *
     * @return StripeDonateManager
     * @throws AlreadyBootedException
     */
    public function register(Provider $serviceProvider): self
    {
        if ($this->booted) {
            throw new AlreadyBootedException();
        }

        $this->providers[] = $serviceProvider;

        $serviceProvider->register($this);

        return $this;
    }

    /**
     * Boots all service providers.
     *
     * This method is automatically called by handle(), but you can use it
     * to boot all service providers when not handling a request.
     *
     * @return bool
     */
    public function boot(): bool
    {
        if ($this->booted) {
            return false;
        }

        /**
         * Fires right before GoogleTagManager gets bootstrapped.
         *
         * Hook here to register custom service providers.
         *
         * @param StripeDonateManager
         */
        do_action(BootstrapEvent::ACTION, $this);

        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableProvider) {
                $provider->boot($this);
            }
        }

        $this->booted = true;

        return true;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (! $this->has($id)) {
            throw new NotFoundException(
                sprintf('No entry was found for "%s identifier.', (string) $id)
            );
        }

        if (
            ! \is_object($this->values[$id])
            || ! \method_exists($this->values[$id], '__invoke')
        ) {
            return $this->values[$id];
        }

        $raw = $this->values[$id];
        $val = $this->values[$id] = $raw($this);

        return $val;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->values[$id]);
    }

    /**
     * Access all registered providers.
     *
     * @return array
     */
    public function providers(): array
    {
        return $this->providers;
    }
}
