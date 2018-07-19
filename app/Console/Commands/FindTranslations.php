<?php

namespace App\Console\Commands;

use Barryvdh\TranslationManager\Manager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Contracts\Foundation\Application;
use Log;

class FindTranslations extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:theme';

     /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find translations in theme';

    /** @var \Barryvdh\TranslationManager\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
		$this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $counter = $this->manager->findTranslations(resource_path("themes/novum"));
        $this->info('Done importing, processed '.$counter.' items!');
    }
}