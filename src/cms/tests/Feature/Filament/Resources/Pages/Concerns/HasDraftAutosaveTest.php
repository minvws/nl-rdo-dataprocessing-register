<?php

declare(strict_types=1);

use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;
use Filament\Facades\Filament;

it('keeps the draft cleanup reachable on every page that autosaves', function (): void {
    $traitFile = (new ReflectionClass(HasDraftAutosave::class))->getFileName();
    $pagesWithoutCleanup = [];
    $pagesWithAutosave = [];

    foreach (Filament::getPanel('admin')->getResources() as $resource) {
        foreach ($resource::getPages() as $pageRegistration) {
            $page = $pageRegistration->getPage();

            if (!in_array(HasDraftAutosave::class, class_uses_recursive($page), true)) {
                continue;
            }

            $pagesWithAutosave[] = $page;

            foreach (['afterSave', 'afterCreate'] as $hook) {
                $hookLine = (new ReflectionMethod(HasDraftAutosave::class, $hook))->getStartLine();
                $methods = (new ReflectionClass($page))->getMethods();

                foreach ($methods as $method) {
                    if ($method->getFileName() === $traitFile && $method->getStartLine() === $hookLine) {
                        continue 2;
                    }
                }

                $pagesWithoutCleanup[] = sprintf('%s::%s()', $page, $hook);
            }
        }
    }

    expect($pagesWithAutosave)
        ->not->toBeEmpty()
        ->and($pagesWithoutCleanup)
        ->toBe([]);
});
