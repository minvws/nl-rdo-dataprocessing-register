<?php

declare(strict_types=1);

namespace Tests\Browser;

trait WithAxe
{
    final protected function wcagViolations(): string
    {
        return <<<'JS'
            (async () => {
                const result = await axe.run(document, {
                    iframes: false,
                    runOnly: {
                        type: 'tag',
                        values: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22a', 'wcag22aa'],
                    },
                })

                return result.violations.map((violation) => violation.id).sort().join(', ')
            })()
            JS;
    }
}
