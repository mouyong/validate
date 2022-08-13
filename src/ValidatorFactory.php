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
    }

    public function getFactory()
    {
        if (is_null($this->factory)) {
            $this->factory = new Factory($this->loadTranslator());
        }

        return $this->factory;
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

        // webman
        if (is_null($this->langPath) && !class_exists(\Illuminate\Foundation\Application::class)) {
           $currentLanguage = config('translation.locale');
        } 
        // laravel
        else if (is_null($this->langPath) && class_exists(\Illuminate\Foundation\Application::class)) {
            $currentLanguage = config('app.locale');
        }

        $loader->load($currentLanguage, 'validation', 'lang');

        return new Translator($loader, $$currentLanguage);
    }

    public function __call(string $method, array $args)
    {

        return call_user_func_array([$this->getFactory(), $method], $args);
    }
}
