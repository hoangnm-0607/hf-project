<?php

namespace App\Controller;

use App\DataCollection\EventErrorCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PatchEventController extends AbstractController
{
    public function __invoke(Request $request, EventErrorCollection $data): EventErrorCollection
    {
        return $data;
    }
}
