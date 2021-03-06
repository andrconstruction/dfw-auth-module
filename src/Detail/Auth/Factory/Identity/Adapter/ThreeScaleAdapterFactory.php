<?php

namespace Detail\Auth\Factory\Identity\Adapter;

use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\Auth\Exception\ConfigException;
use Detail\Auth\Identity\Adapter\ThreeScaleAdapter as Adapter;
use Detail\Auth\Options\Identity\Adapter\ThreeScaleAdapterOptions as AdapterOptions;
use Detail\Auth\Options\Identity\IdentityOptions;

class ThreeScaleAdapterFactory extends BaseAdapterFactory
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param IdentityOptions $identityOptions
     * @return Adapter
     */
    protected function createAdapter(
        ServiceLocatorInterface $serviceLocator,
        IdentityOptions $identityOptions
    ) {
        /** @var AdapterOptions $adapterOptions */
        $adapterOptions = $identityOptions->getAdapterOptions(
            Adapter::CLASS,
            AdapterOptions::CLASS
        );

        /** @var \Detail\Auth\Options\ThreeScaleOptions $threeScaleOptions */
        $threeScaleOptions = $serviceLocator->get('Detail\Auth\Options\ThreeScaleOptions');

        $clientClass = $adapterOptions->getClient();

        if (!$clientClass) {
            throw new ConfigException('Missing 3scale client class');
        }

        /** @var \ThreeScaleClient $client */
        $client = $serviceLocator->get($clientClass);

        $credentialHeaders = array(
            Adapter::CREDENTIAL_APPLICATION_ID  => $adapterOptions->getAppIdHeader(),
            Adapter::CREDENTIAL_APPLICATION_KEY => $adapterOptions->getAppKeyHeader(),
        );

        $adapter = new Adapter(
            $client,
            $threeScaleOptions->getServiceId(),
            $credentialHeaders,
            $this->getCache($serviceLocator, $adapterOptions->getCache())
        );

        return $adapter;
    }
}
