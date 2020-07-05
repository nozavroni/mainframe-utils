<?php
/**
 * Mainframe - a domain codification framework
 *
 * A clumsy attempt to put a name to a concept I've been kicking around for quite some time now. See the
 * README file for a more in-depth overview of this concept and how this library relates to it.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Support\Action;

use DI\ContainerBuilder;
use Mainframe\Container\ConfigDefinition;
use Mainframe\Exception\BootException;
use Mainframe\Exception\Config\ResourceNotFoundException;
use Mainframe\Support\Options\OptionsAware;
use Mainframe\Support\Options\OptionsAwareInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;
use function DI\autowire;

class BuildContainerAction extends AbstractAction implements OptionsAwareInterface
{
    use OptionsAware;

    protected ContainerBuilder $builder;

    protected ConfigDefinition $configDefinition;

    public function __construct(ConfigDefinition $configDefinition, ContainerBuilder $builder)
    {
        $this->builder = $builder;
        $this->configDefinition = $configDefinition;
        $this->setOptions($configDefinition->getOptions());
    }

    /**
     * @todo Need to actually implement the config caching
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('environment');
        $resolver->setAllowedTypes('environment', 'string');
        $resolver->setDefaults([
            'rootDir' => dirname(dirname(dirname(__DIR__))),
            'configCaching' => false,
            // @todo probably dont want this to live here
            'configCacheDir' => 'var/cache/config/container-definition.php'
        ]);
    }

    /**
     * @param $configs
     * @return array
     */
    protected function processConfig(array $configs)
    {
        $processor = new Processor();
        return $processor->processConfiguration(
            $this->configDefinition,
            array_map(
                function($filepath) {
                    if (!file_exists($filepath)) {
                        throw new ResourceNotFoundException(sprintf('Could not find "%s"', $filepath));
                    }
                    return Yaml::parse(file_get_contents($filepath));
                },
                $configs
            )
        );
    }

    /**
     * @param string|string[] $containerConfigs
     * @return mixed|void
     */
    protected function perform(...$args)
    {
        if (!$containerConfigs = array_shift($args)) {
            // @todo no config files specified
            throw new BootException('No container configuration defined');
        }
        $config = collect($this->processConfig(to_array($containerConfigs)))
            ->map(function($val, $key, $iter) {
                $opts = $this->getOptions();
                $templater = function($str) use ($opts) {
                    return str_template($str, $opts, '%%%s%%');
                };
                if (is_array($val)) {
                    return array_map($templater, $val);
                }
                return $templater($val);
            });

        $this->builder
            ->useAnnotations($config->get('annotations', false))
            ->ignorePhpDocErrors($config->get('ignore_phpdoc_errors', false));
        if ($autoWiring = $config->get('autowiring', false)) {
            $autowiredClasses = collect($config->get('autowired_classes', []))
                ->each(function ($class) {
                    throw_if(
                        !class_exists($class),
                        new ResourceNotFoundException(sprintf('"%s" class does not exist', $class))
                    );
                    return $class;
                })
                ->flip()
                ->map(fn ($val, $class, $iter) => autowire())
                ->toArray();
            $this->builder->useAutowiring($autoWiring);
            $this->builder->addDefinitions($autowiredClasses);
        }
        if ($config->get('compile', false)) {
            $this->builder->enableCompilation($config->get('compile_dir'));
        }
        if ($config->get('caching', false)) {
            $this->builder->enableDefinitionCache($config->get('caching_namespace'));
        }
        if ($config->get('allow_lazy', false) && $proxyDir = $config->get('proxy_dir')) {
            $this->builder->writeProxiesToFile(true, $proxyDir);
        }

        return $this->builder->build();

//        $services = (new Finder())
//            ->in($this->configDefinition->getOption('rootDir') . DIRECTORY_SEPARATOR . $this->configDefinition->getOption('rootDir'))
//            // ->path($config->get('services', []))
//            ->files();
//        foreach ($services as $fileInfo) {
//            dump($fileInfo->getRealPath());
//        }
    }
}