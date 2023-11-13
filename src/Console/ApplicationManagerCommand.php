<?php

namespace Risky2k1\ApplicationManager\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ApplicationManagerCommand extends Command
{
    protected $signature = 'application:install';

    protected $description = 'Install the Application Manager package';

    public function handle()
    {
        $this->info('Installing Application Manager...');

        $this->info('Publishing configuration...');

        if (!$this->configExists('application-manager.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed BlogPackage');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Risky2k1\ApplicationManager\ApplicationManagerServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
