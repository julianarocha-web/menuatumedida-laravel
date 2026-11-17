<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('app:create-admin-user')]
#[Description('Create the initial administrator account')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = $this->ask('Nombre del administrador');
        $email = $this->ask('Email del administrador');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            ]
        );

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return self::FAILURE;
        }

        $password = $this->secret('Contraseña (mínimo 12 caracteres)');
        $passwordConfirmation = $this->secret('Confirmar contraseña');

        if (! is_string($password) || strlen($password) < 12 || $password !== $passwordConfirmation) {
            $this->components->error('La contraseña debe tener al menos 12 caracteres y coincidir con la confirmación.');

            return self::FAILURE;
        }

        $administrator = new User;
        $administrator->name = $name;
        $administrator->email = $email;
        $administrator->password = $password;
        $administrator->es_admin = true;
        $administrator->save();

        $this->components->info('Cuenta administradora creada.');

        return self::SUCCESS;
    }
}
