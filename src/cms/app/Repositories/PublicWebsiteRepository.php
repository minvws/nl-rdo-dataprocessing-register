<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PublicWebsite as PublicWebsiteModel;

class PublicWebsiteRepository
{
    public function get(): PublicWebsiteModel
    {
        return PublicWebsiteModel::firstOrCreate();
    }
}
