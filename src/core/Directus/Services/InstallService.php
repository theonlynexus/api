<?php

namespace Directus\Services;

use Directus\Util\ArrayUtils;
use Directus\Util\Installation\InstallerUtils;

class InstallService extends AbstractService
{
    public function install(array $data)
    {
        $this->validate($data, [
            'env' => 'string',

            'force' => 'bool',

            'db_host' => 'string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'string',

            'mail_from' => 'string',
            'cors_enabled' => 'bool',

            'project_name' => 'string',
            'user_email' => 'required|email',
            'user_password' => 'required|string',
            'user_token' => 'string'
        ]);

        $force = ArrayUtils::pull($data, 'force', false);
        $env = ArrayUtils::get($data, 'env', '_');
        $basePath = $this->container->get('path_base');

        InstallerUtils::ensureCanCreateConfig($basePath, $data, $force);
        InstallerUtils::ensureCanCreateTables($basePath, $data, $force);

        InstallerUtils::createConfig($basePath, $data, $force);

        InstallerUtils::createTables($basePath, $env, $force);
        InstallerUtils::addDefaultSettings($basePath, $data, $env);
        InstallerUtils::addDefaultUser($basePath, $data, $env);
    }
}
