<?php

declare(strict_types=1);

use App\ValueObjects\Markdown;

it('can instantiate from a valid string', function (): void {
    $input = fake()->word();
    $markdown = Markdown::fromString($input);

    expect($markdown->toString())
        ->toBe($input);
});

it('can instantiate from valid markdown', function (): void {
    $input = sprintf('*%s*', fake()->word());
    $markdown = Markdown::fromString($input);

    expect($markdown->toString())
        ->toBe($input);
});

it('can format markdown to html', function (): void {
    $input = fake()->word();
    $markdown = Markdown::fromString(sprintf('*%s*', $input));

    expect($markdown->toHtml())
        ->toBe(sprintf("<p><em>%s</em></p>\n", $input));
});

it('will strip html-format from markdown', function (): void {
    $input = fake()->word();
    $markdown = Markdown::fromString(sprintf('<em>%s</em>', $input));

    expect($markdown->toHtml())
        ->toBe(sprintf("<p>%s</p>\n", $input));
});
