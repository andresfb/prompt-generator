<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

use function Laravel\Prompts\clear;
use function Laravel\Prompts\error;
use function Laravel\Prompts\form;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\warning;

final class CreateTokenCommand extends Command
{
    protected $signature = 'create:token';

    protected $description = 'Creates a new API token for a given user';

    public function handle(): void
    {
        try {
            clear();
            intro('Create a API token');

            $results = form()
                ->intro('Login')
                ->text(
                    label: 'Email',
                    default: Config::string('constants.admin_email'),
                    required: true,
                    validate: 'string|email|max:255|exists:users,email',
                    hint: 'user@example.com',
                    name: 'email',
                )
                ->password(
                    label: 'Password',
                    required: true,
                    validate: 'string|min:8',
                    name: 'password',
                )
                ->submit();

            $user = User::query()
                ->where('email', $results['email'])
                ->firstOrFail();

            if (! Hash::check($results['password'], $user->password)) {
                throw new RuntimeException('Invalid password');
            }

            $token = $user->createToken('CLI');

            $this->newLine();
            warning($token->plainTextToken);
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            $this->newLine();
            outro('Done');
        }
    }
}
