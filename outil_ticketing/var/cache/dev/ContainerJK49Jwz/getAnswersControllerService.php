<?php

namespace ContainerJK49Jwz;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getAnswersControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\AnswersController' shared autowired service.
     *
     * @return \App\Controller\AnswersController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/AnswersController.php';

        $container->services['App\\Controller\\AnswersController'] = $instance = new \App\Controller\AnswersController();

        $instance->setContainer(($container->privates['.service_locator.jIxfAsi'] ?? $container->load('get_ServiceLocator_JIxfAsiService'))->withContext('App\\Controller\\AnswersController', $container));

        return $instance;
    }
}
