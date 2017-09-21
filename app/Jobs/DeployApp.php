<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

use App\Models\Servers\Application;
use Symfony\Component\Console\Output\BufferedOutput;

class DeployApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 200;

    protected $application;
    protected $committer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Application $application, $committer)
    {
        $this->application = $application;
        $this->committer = $committer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info( 'Application  : ' . $this->application->name );
        Log::info( 'ID  : ' . $this->application->id );
        Log::info( 'Deploy Task  : ' . $this->application->deploy_task );
        Log::info( 'Route  : ' . $this->application->route );
      
        $exitCode = Artisan::call( $this->application->deploy_command, [ 
            'route' => $this->application->route,
            'branch' => $this->application->branch,
            'committer' => $this->committer,
            'applicationId' => $this->application->id,
        ] );

        Log::info('Result=' );
        Log::info( $exitCode );
        Log::info('Output=' );
        Log::info( Artisan::output() );

    }
}
