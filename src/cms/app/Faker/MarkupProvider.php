<?php

declare(strict_types=1);

namespace App\Faker;

use Faker\Provider\Miscellaneous;
use Illuminate\Support\Arr;

use function sprintf;

class MarkupProvider extends Miscellaneous
{
    /**
     * @return array<string>
     */
    public function frontmatter(): array
    {
        return [
            'word1' => $this->generator->word(),
            'word2' => $this->generator->word(),
            'word3' => $this->generator->word(),
            'word4' => $this->generator->word(),
            'word5' => $this->generator->word(),
            'sentence1' => $this->generator->sentence(),
            'sentence2' => $this->generator->sentence(),
        ];
    }

    public function markdown(): string
    {
        $markdown = [
            sprintf("# %s\r\n", $this->generator->sentence()),
            sprintf("%s\r\n", $this->generator->paragraph()),
            sprintf("## %s\r\n", $this->generator->sentence()),
            sprintf("%s\r\n", $this->generator->paragraph()),
        ];

        return Arr::join($markdown, "\r\n");
    }
}
