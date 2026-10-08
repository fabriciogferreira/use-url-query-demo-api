<?php

declare(strict_types=1);

use App\Http\Requests\Common\BaseRequest;

$path = 'App\Http\Requests';

arch("only requests in {$path}")
  ->expect($path)
  ->toExtend(BaseRequest::class)
  ->ignoring(BaseRequest::class);

arch("suffix requests with Request")
  ->expect($path)
  ->toHaveSuffix('Request');
