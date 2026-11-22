<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Services\User\UserService;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class UserController extends Controller {
  public function __construct(
    private readonly UserService $userService,
  ) {
  }

  public function index(UserIndexRequest $request): Response {
    $validatedData = $request->validated();

    $users = $this->userService->index($validatedData);

    return response([
      'data' => $users,
    ], SymfonyResponse::HTTP_OK);
  }
}
