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
 * @see https://github.com/mouyong/validate/blob/master/src/ValidatorFactory.php
 */
class ValidatorFactory
{
    protected $langPath;
    
    /**
     * @var Factory
     */
    private $factory;

    public function __construct(?string $langPath = null)
    {
        $this->setLangPath($langPath);

        $this->factory = new Factory($this->loadTranslator());
    }

    public function setLangPath(?string $langPath = null)
    {
        $this->langPath = $langPath;
    }

    public function getLangPath()
    {
        // webman
        if (is_null($this->langPath) && !class_exists(\Illuminate\Foundation\Application::class)) {
            $this->langPath = base_path() . '/resource/translations';
        } 
        // laravel
        else if (is_null($this->langPath) && class_exists(\Illuminate\Foundation\Application::class)) {
            $this->langPath = base_path() . '/resources/lang';
        }

        return $this->langPath;
    }

    protected function loadTranslator(): Translator
    {
        $langPath = $this->getLangPath();

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
