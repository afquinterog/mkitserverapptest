<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;
use App\Models\Services\ServerService;
use App\Models\Servers\Application;
use Carbon\Carbon;

class DeployApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:app {route} {branch} {applicationId} {committer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List information from a remote server';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ServerService $serverService)
    {
        //Get arguments
        $route = $this->argument('route');
        $branch = $this->argument('branch');
        $committer = $this->argument('committer');
        $applicationId = $this->argument('applicationId');

        $application = Application::find($applicationId);
      
        // Change the actual path to be the main path and get the envoy file
        chdir( base_path() );

        //Deploy the application
        $process = new Process( $application->before_script . " envoy run ".  $application->deploy_task . " --route=$route --branch=$branch ");
        $process->run();

        if (!$process->isSuccessful()) {
            Log::info( $process->getErrorOutput() );
            $serverService->saveApplicationDeployment($applicationId, 0 , $process->getOutput(), $committer, $branch );
            throw new ProcessFailedException($process);
            //
        }

        //Create new Deployment
        $serverService->saveApplicationDeployment($applicationId, 1 , $process->getOutput(), $committer, $branch );
        //Update last deployment date
        $application->last_time_deployed = Carbon::now();
        $application->new_versions = 0;
        $application->save();
        Log::info( $process->getOutput() );
        return $process->getOutput();
    }
}

