<?php

declare(strict_types=1);

namespace App\Http;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;
use Spatie\Csp\Scheme;

class CspPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::FONT, Keyword::SELF)
            ->addDirective(Directive::FONT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::FONT, 'fonts.bunny.net')
            ->addDirective(Directive::FONT, Scheme::DATA)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::IMG, Scheme::BLOB)
            ->addDirective(Directive::IMG, Scheme::DATA)
            ->addDirective(Directive::IMG, 'ui-avatars.com')
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_EVAL)
            ->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::STYLE, 'fonts.bunny.net')
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE)
            ->addDirective(Directive::WORKER, Keyword::SELF)
            ->addDirective(Directive::WORKER, Scheme::BLOB);
    }
}
