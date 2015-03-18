<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace Thorr\Nonce;

use Thorr\Persistence\DataMapper\Manager\DataMapperManager;
use Zend\ModuleManager\Feature;
use Zend\ServiceManager\ServiceManager;

class Module implements Feature\ConfigProviderInterface, Feature\ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return [
            'thorr_nonce' => [
                'default_nonce_expiration_interval' => 'P7D',
            ],

            'thorr_persistence_dmm' => [
                'entity_data_mapper_map' => [
                    // key is the entity class, value is an arbitrary service name
                    Entity\Nonce::class => DataMapper\DoctrineMapperAdapter::class,
                ],
                'doctrine' => [
                    'adapters' => [
                        // key is the arbitrary service name, value is an adapter spec (string|array)
                        DataMapper\DoctrineMapperAdapter::class => DataMapper\DoctrineMapperAdapter::class,
                    ],
                ],
            ],

            'doctrine' => [
                'driver' => [
                    'thorr-nonce-xml-driver' => [
                        'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                        'paths' => __DIR__ . '/mappings',
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Thorr\Nonce\Entity' => 'thorr-nonce-xml-driver',
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Service\NonceService::class => function (ServiceManager $serviceManager) {
                    $nonceRepository = $serviceManager->get(DataMapperManager::class)
                                                      ->getDataMapperForEntity(Entity\Nonce::class);
                    $moduleOptions = $serviceManager->get(Options\ModuleOptions::class);

                    return new Service\NonceService($nonceRepository, $moduleOptions);
                },
                Options\ModuleOptions::class => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('config')['thorr_nonce'];

                    return new Options\ModuleOptions($config);
                },
            ]
        ];
    }
}
