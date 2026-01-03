<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the first admin user securely';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Creating admin user...');
        $this->newLine();

        // Admin data
        $name = 'Sallah';
        $email = 'ledecore-support@gmail.com';
        $password = '12345678';

        // Check if admin with this email already exists
        $existingAdmin = User::where('email', $email)->first();

        if ($existingAdmin) {
            $this->error("❌ Admin user with email '{$email}' already exists!");
            $this->warn("   ID: {$existingAdmin->id}");
            $this->warn("   Name: {$existingAdmin->name}");
            $this->warn("   Is Admin: " . ($existingAdmin->is_admin ? 'Yes' : 'No'));
            $this->newLine();
            $this->info('Command execution stopped. No changes were made.');
            return Command::FAILURE;
        }

        try {
            // Create admin user with hashed password
            $admin = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
            ]);

            $this->info('✅ Admin user created successfully!');
            $this->newLine();
            $this->table(
                ['Field', 'Value'],
                [
                    ['ID', $admin->id],
                    ['Name', $admin->name],
                    ['Email', $admin->email],
                    ['Is Admin', $admin->is_admin ? 'Yes' : 'No'],
                    ['Created At', $admin->created_at->format('Y-m-d H:i:s')],
                ]
            );
            $this->newLine();
            $this->comment('⚠️  Password: 12345678 (hashed and stored securely)');
            $this->comment('⚠️  Please change the password after first login for security.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Failed to create admin user!');
            $this->error("   Error: {$e->getMessage()}");
            $this->newLine();
            return Command::FAILURE;
        }
    }
}
