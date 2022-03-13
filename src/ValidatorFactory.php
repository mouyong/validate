<?php

namespace Mouyong\Validate;

use Illuminate\Translation;
use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;

/**
 *
 * @mixin Factory
 *
 * @see https://medium.com/@jeffochoa/using-the-illuminate-validation-validator-class-outside-laravel-6b2b0c07d3a4
 */
class ValidatorFactory
{
    /**
     * @var Factory
     */
    private $factory;

    public function __construct()
    {
        $this->factory = new Factory($this->loadTranslator());
    }

    protected function loadTranslator(): Translator
    {
        $langPath = base_path() . '/lang';

        $filesystem = new Filesystem();
        $loader = new Translation\FileLoader($filesystem, $langPath);
        $loader->addNamespace('lang', $langPath);
        $loader->load('en', 'validation', 'lang');

        return new Translator($loader, 'en');
    }

    public function __call(string $method, array $args)
    {
        return call_user_func_array([$this->factory, $method], $args);
    }
}