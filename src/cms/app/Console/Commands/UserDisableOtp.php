<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Console\Command;

use function Laravel\Prompts\text;

class UserDisableOtp extends Command
{
    protected $description = 'Disable one-time-password for an existing user';
    protected $signature = 'app:user-disable-otp';

    public function handle(OtpService $otpService): int
    {
        $inputData = $this->getInputData();

        $user = User::where('email', $inputData['email'])->first();
        if ($user === null) {
            $this->output->error('User does not exist');

            return self::FAILURE;
        }

        $otpService->disable($user);

        $this->output->success('Otp disabled');

        return self::SUCCESS;
    }

    /**
     * @return array{'email': string}
     */
    private function getInputData(): array
    {
        return [
            'email' => text(label: 'Email address', required: true),
        ];
    }
}
