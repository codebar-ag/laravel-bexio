<?php

namespace CodebarAg\Bexio\Contracts;

use Illuminate\Http\Request;
use CodebarAg\Bexio\DTO\Config\ConfigWithCredentials;

interface BexioConfigResolver
{
    public function __invoke(Request $request): ConfigWithCredentials;
}
