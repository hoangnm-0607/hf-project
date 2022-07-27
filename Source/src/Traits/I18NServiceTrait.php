<?php

declare(strict_types=1);

namespace App\Traits;

use App\Service\I18NService;
use Symfony\Contracts\Service\Attribute\Required;

trait I18NServiceTrait
{
    protected I18NService $i18NService;

    #[Required]
    public function setI18NService(I18NService $i18NService): void
    {
        $this->i18NService = $i18NService;
    }
}
