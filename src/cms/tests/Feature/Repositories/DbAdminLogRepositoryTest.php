<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\AdminLogEntry;
use App\Repositories\DbAdminLogRepository;
use Illuminate\Support\Facades\App;
use RuntimeException;

use function expect;
use function fake;
use function it;

it('creates a new admin log entry with the given message and context', function (): void {
    $message = fake()->sentence();
    $context = [
        1 => fake()->sentence(),
        fake()->word() => fake()->sentence(),
    ];

    App::get(DbAdminLogRepository::class)->log($message, $context);
    $adminLogEntry = AdminLogEntry::query()
        ->orderBy('created_at', 'desc')
        ->first();

    expect($adminLogEntry)
        ->not()->toBeNull()
        ->and($adminLogEntry->message)->toBe($message)
        ->and($adminLogEntry->context)->toBe($context);
});

it('includes a duration in the context when logging a timed action', function (): void {
    $message = fake()->sentence();
    $context = [
        1 => fake()->sentence(),
        fake()->word() => fake()->sentence(),
    ];

    App::get(DbAdminLogRepository::class)->timedLog(
        function (): void {
            // Do nothing
        },
        $message,
        $context,
    );
    $adminLogEntry = AdminLogEntry::query()
        ->orderBy('created_at', 'desc')
        ->first();

    expect($adminLogEntry)
        ->not()->toBeNull()
        ->and($adminLogEntry->message)->toBe($message)
        ->and($adminLogEntry->context)->toHaveKey('duration_seconds')
        ->and($adminLogEntry->context['duration_seconds'])->toBeNumeric();
});

it('includes an exception message in the context when the callback fails', function (): void {
    $message = fake()->sentence();
    $context = [
        1 => fake()->sentence(),
        fake()->word() => fake()->sentence(),
    ];

    App::get(DbAdminLogRepository::class)->timedLog(
        function (): void {
            throw new RuntimeException('Oof, something went wrong');
        },
        $message,
        $context,
    );
    $adminLogEntry = AdminLogEntry::query()
        ->orderBy('created_at', 'desc')
        ->first();

    expect($adminLogEntry)
        ->not()->toBeNull()
        ->and($adminLogEntry->message)->toBe($message)
        ->and($adminLogEntry->context)->toHaveKey('exception_message')
        ->and($adminLogEntry->context['exception_message'])->toBe('Oof, something went wrong');
})
    ->expectException(RuntimeException::class);
