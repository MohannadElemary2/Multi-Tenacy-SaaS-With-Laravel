<?php

namespace Modules\Client\Listeners;

use App\Enums\PassportGrantTypes;
use App\Events\CreateTenant;
use App\Models\Tenants\PassportAuthCode;
use App\Models\Tenants\PassportClient;
use App\Models\Tenants\PassportPersonalAccessClient;
use App\Models\Tenants\PassportRefreshToken;
use App\Models\Tenants\PassportToken;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use Modules\Client\Enums\SettingsValues;
use Modules\Client\Events\ClientAdded;
use Modules\UserManagementSystem\Entities\Client\TenantUser;

class CreateTenantListener
{
    /**
     * Handle the event.
     *
     * @param  CreateTenant  $event
     * @return void
     * @author Mohannad Elemary
     */
    public function handle(ClientAdded $event)
    {
        $fqdn = $event->client->domain . '.' . config('app.url_base');

        $hostname = $this->createTenant($fqdn, $event->client);

        app(Environment::class)->hostname($hostname);
    }

    /**
     * Create Tenant website and hostname.
     *
     * @param  $fqdn
     * @param  $client
     * @return Hostname
     * @author Mohannad Elemary
     */
    private function createTenant($fqdn, $client)
    {
        $website = new Website;
        $website->managed_by_database_connection = config('database.current_tenant_connection');

        $website = app(WebsiteRepository::class)->create($website);
        $client->update(['website_id' => $website->id]);

        //add admin for subdomain
        $this->addClientAdminUser($website, $client);

        $hostname       = new Hostname;
        $hostname->fqdn = $fqdn;
        app(HostnameRepository::class)->attach($hostname, $website);

        return $hostname;
    }

    /**
     * Add admin for client domain .
     *
     * @param  $website
     * @param  $client
     * @return void
     * @author Mohannad Elemary
     */
    public function addClientAdminUser($website, $client)
    {
        $this->prepareClientEnvironment($website);
        $this->migrateClientTables($website);

        $user = TenantUser::create([
            'name'         => $client->company_name,
            'email'        => $client->email,
            'phone'        => $client->phone,
            'is_super'     => 1,
            'locale'       => SettingsValues::LOCALE,
            'time_zone'    => SettingsValues::TIME_ZONE
        ]);

        $user->sendEmailVerificationNotification($client->domain);
        $this->addOauthClients($website);
    }

    /**
     * Create Passport Oauth Clients for the new Client
     *
     * @param Website $website
     * @return void
     * @author Mohannad Elemary
     */
    private function addOauthClients($website)
    {
        Artisan::call('tenancy:run', [
            'run'      => 'passport:install',
            '--tenant' => [$website->id],
        ]);

        // Adjust The Passport Client Provider to be Used For The Tenants
        PassportClient::firstWhere('password_client', PassportGrantTypes::PASSWORD_GRANT)->update([
            'provider' => 'clients'
        ]);
    }

    /**
     * Prepare client 'tenant' environment and connect to his database and passport models
     *
     * @param Website $website
     * @return void
     * @author Mohannad Elemary
     */
    private function prepareClientEnvironment($website)
    {
        app(Environment::class)->tenant($website);

        Passport::useClientModel(PassportClient::class);
        Passport::useTokenModel(PassportToken::class);
        Passport::useAuthCodeModel(PassportAuthCode::class);
        Passport::usePersonalAccessClientModel(PassportPersonalAccessClient::class);
        Passport::useRefreshTokenModel(PassportRefreshToken::class);

        Config::set('auth.guards.api.provider', 'clients');
    }

    /**
     * Migrate Client Tables
     *
     * @param Website $website
     * @return void
     * @author Mohannad Elemary
     */
    private function migrateClientTables($website)
    {
        // Run The migrations
        Artisan::call('tenancy:migrate-modules', [
            '--website_id' => [$website->id],
        ]);

        // Run The seeders
        Artisan::call('tenancy:run', [
            'run'           => 'tenancy:run',
            '--tenant'      => [$website->id],
            '--argument'    => ["run=module:seed"],
        ]);
    }
}
