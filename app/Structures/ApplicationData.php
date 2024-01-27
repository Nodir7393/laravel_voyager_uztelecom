<?php

namespace App\Structures;


use App\Models\Application;

/**
 * Class CarModelData
 */
class ApplicationData extends \App\Http\Requests\ApplicationRequest
{
    /**
     * @var array
     */
    private $translations;

    /**
     * CarModelData constructor.
     * @param array $translations
     */
    public function __construct(array $translations)
    {
        $this->setTranslations($translations);
    }

    /**
     * @param array $input
     * @return ApplicationData
     */
    public static function fill(array $input): ApplicationData
    {
        return new self(
            $input['translations']
        );
    }

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * @param array $translations
     */
    public function setTranslations(array $translations): void
    {
        foreach ($translations as $translate) {
            if ($translate['name'] == '') continue;
            $this->translations[] = new Application($translate);
        }
    }
}
