<?php
/**
 * Service class to register services.
 *
 * @package MagicMaker\Core
 */

declare(strict_types=1);

namespace MagicMaker\Core;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use MagicMaker\Core\Config;
use MagicMaker\Core\Container;
use MagicMaker\Core\Contracts\ConfigInterface;

/**
 * Service class
 *
 * @since 1.0.0
 */
class Service {
	/**
	 * Service instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Service
	 */
	private static $instance;

	/**
	 * Container instance.
	 *
	 * @since 1.0.1
	 *
	 * @var Container
	 *
	 * @access protected
	 */
	protected Container $container;

	/**
	 * Service constructor.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		$this->container = Container::instance();
	}

	/**
	 * Get the service instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Service
	 */
	public static function instance(): Service {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register and Load a config file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path The plugin path.
	 * @param string $file The config file to load.
	 *
	 * @return Service
	 *
	 * @throws InvalidArgumentException If the file is not found.
	 */
	public function set_config( string $path, string $file ): Service {
		self::instance()
			->register_config( $path )
			->load( $file );

		return $this;
	}

	/**
	 * Register WordPress classes
	 *
	 * @since 1.0.0
	 *
	 * @return Service
	 */
	public function set_services(): Service {
		// Register the rest api routes.
		$this->container->get( 'thingsRestApi' )->register_routes();

		// call __invoke() method of the service classes.
		$this->container->get( 'assets' )();
		$this->container->get( 'formAction' )();
		$this->container->get( 'listAction' )();
		$this->container->get( 'formShortcode' )();
		$this->container->get( 'listShortcode' )();

		return $this;
	}

	/**
	 * Register default config service.
	 *
	 * Keep in mind that,
	 * ContainerBuilder is available without loaded config file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path The plugin path.
	 *
	 * @return ConfigInterface
	 */
	private function register_config( string $path ): ConfigInterface {
		$builder = $this->container->get_container_builder();

		$this->container
			->register( 'fileLocator', FileLocator::class )
			->addArgument( $path );

		$this->container
			->register( 'yamlFileLoader', YamlFileLoader::class )
			->addArgument( $builder )
			->addArgument( new Reference( 'fileLocator' ) );

		$this->container
			->register( 'config', Config::class )
			->addArgument( new Reference( 'yamlFileLoader' ) );

		return $this->container->get( 'config' );
	}
}
