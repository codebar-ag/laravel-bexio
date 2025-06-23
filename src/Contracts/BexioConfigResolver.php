<?php

namespace CodebarAg\Bexio\Contracts;

use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;
use Illuminate\Http\Request;

interface BexioConfigResolver
{
    public function __invoke(Request $request): ConfigWithCredentials;
}
