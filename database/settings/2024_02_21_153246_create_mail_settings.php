<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('mail.from_address', 'noreply.ecsystem@gmail.com');
        $this->migrator->add('mail.from_name', 'Easy Certification System');
        $this->migrator->add('mail.driver', 'smtp');
        $this->migrator->add('mail.host', 'smtp.gmail.com');
        $this->migrator->add('mail.port', 587);
        $this->migrator->add('mail.encryption', 'tls');
        $this->migrator->addEncrypted('mail.username', 'noreply.ecsystem@gmail.com');
        $this->migrator->addEncrypted('mail.password', 'kmel hudq zymr sein');
        $this->migrator->add('mail.timeout', null);
        $this->migrator->add('mail.local_domain', 'noreply.ecsystem@gmail.com');
    }
};
