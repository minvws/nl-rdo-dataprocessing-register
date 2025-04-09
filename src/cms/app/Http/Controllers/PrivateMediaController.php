<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AuthenticationService;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webmozart\Assert\InvalidArgumentException;

use function abort_if;
use function redirect;
use function response;

class PrivateMediaController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    ) {
    }

    public function __invoke(string $id): RedirectResponse|BinaryFileResponse
    {
        try {
            $user = $this->authenticationService->user();
        } catch (InvalidArgumentException) {
            return redirect('login');
        }

        $media = Media::where('uuid', $id)->firstOrFail();

        abort_if($user->cannot('view', $media->model->firstOrFail()), 403);

        return response()->file($media->getPath());
    }
}
