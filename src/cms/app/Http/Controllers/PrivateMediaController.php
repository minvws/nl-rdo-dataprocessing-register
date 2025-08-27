<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Components\Uuid\Uuid;
use App\Models\Contracts\TenantAware;
use App\Models\Organisation;
use App\Services\AuthenticationService;
use App\Vendor\MediaLibrary\Media;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

use function abort;
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
        $model = $media->model;
        if ($model === null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        if ($model instanceof TenantAware) {
            // Organisation cannot be retrieved through $model->getOrganisation() because the organisation could be deleted, but this
            // is currently not supported troughout the application. This will be fixed in issue#1465
            // Therefore we have to retrieve it using the organisation_id property.
            $organisationId = $model->getAttribute('organisation_id');
            Assert::isInstanceOf($organisationId, Uuid::class);

            $organisation = Organisation::query()->findOrFail($organisationId);
            Filament::setTenant($organisation, true);
        }

        abort_if($user->cannot('view', $model), Response::HTTP_FORBIDDEN);

        return response()->file($media->getPath());
    }
}
