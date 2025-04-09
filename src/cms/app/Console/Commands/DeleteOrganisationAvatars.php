<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Organisation;
use App\Vendor\MediaLibrary\Media;
use Illuminate\Console\Command;

use function sprintf;

class DeleteOrganisationAvatars extends Command
{
    protected $signature = 'app:delete-organisation-avatars';
    protected $description = 'Delete existing organisations avatars';

    public function handle(): int
    {
        $organisations = Organisation::withTrashed()->get();

        foreach ($organisations as $organisation) {
            $medias = $organisation->getMedia('organisation_avatars');

            /** @var Media $media */
            foreach ($medias as $media) {
                $this->output->info(sprintf('Avatar deleted for %s', $organisation->name));
                $media->delete();
            }
        }

        $this->output->success('Organisation avatars deleted');

        return self::SUCCESS;
    }
}
