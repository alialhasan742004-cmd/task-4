<?php

namespace App\Contracts;

interface AiProvider
{

    public function enhancePhoto($file): array;
}
