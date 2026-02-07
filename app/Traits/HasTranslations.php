<?php

namespace App\Traits;

trait HasTranslations
{
    /**
     * Get a translated attribute
     * Fallback to English if current language is missing
     */
    public function getTranslated(string $attribute)
    {
        $locale = app()->getLocale();
        $data = $this->$attribute;

        return $data[$locale] ?? $data['en'] ?? null;
    }
}
