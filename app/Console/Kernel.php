<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\SendReminder',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('reminder')->everyMinute();
		$schedule->command('backup:clean')->daily()->at('18:00');
		$schedule->command('backup:run')->daily()->at('19:00');

/*        $schedule->command('queue:work --stop-when-empty')
            ->name('monitor_queue_listener')
            ->everyFiveMinutes()
            ->withoutOverlapping();*/

        $schedule->call(function() {
            // Useful variables
            $op             = [];                           // return from exec()
            $deadline       = 75;                           // process life, in minutes.
            $deadline       *= 60;                          // convert to use with time()
            $path           = base_path();
            $php_executable = '/usr/bin/php';               // absolute path to php excutable
            $running        = false;                        // are queues running ?
            $restart        = false;                        // have to restart queues ?
            $since          = 0;                            // since when queues are running
            $pid_file       = $path . '/queue.pid';         // File which contains the queue pid
            $since_file     = $path . '/running_since';     // File which contains the start timing

            // Commands to start workers, or restarting them.
            $queue_up       = "$php_executable -c " . $path .'/php.ini ' . $path .
                '/artisan queue:work --daemon --tries=3 --sleep=5 > /dev/null & echo $!' ;
            $queue_restart  = "$php_executable -c " . $path .'/php.ini ' . $path .
                '/artisan queue:restart > /dev/null ' ;
            // Check if pid file exists  && queues are running
            if (file_exists($pid_file)) {
                $pid = file_get_contents($pid_file);
                // --no-headings isn't portable, sed does the same working in more un*xes, like OS X.
                exec("ps -p $pid | sed 1d | awk '{print $1}'", $op);
                $result = isset($op[0]) ? (int)end($op) : null;
                $running = $result != '' ? true : false;          // it's running ?
            }
            else {
                $running = false;                                 // queues aren't running
            }
            // checks if queues have been running for too long
            if(file_exists($since_file)) {
                $since = file_get_contents($since_file);
                $since = $since == '' ? 0 : $since;               // empty values are 0
                $restart = ( (time() - ($since + $deadline)) >= 0 ) ? true : false ;
            }
            else {
                $restart = true;                                 // there it wasn't a queue file, so a restart isn't needed
            }
            // Act as needed
            if ($running && $restart){                         // Process exists && needs restart
                exec($queue_restart);                            // kills processes gracefully
                exec($queue_up, $op);                            // ups the process.
                if(isset($op[0])){
                    file_put_contents($pid_file, end($op));
                    file_put_contents($since_file, time());
                }
                \Log::info('Queues have been restarted');
            }
            elseif(!$running) {                                  // El proceso no existe y hay que iniciarlo
                exec($queue_up, $op);
                if(isset($op[0])){
                    file_put_contents($pid_file, end($op));
                    file_put_contents($since_file, time());
                    \Log::info('Queues weren\'t running');
                }
            }
        })->pingBefore('https://cronitor.link/OJA0Ue/run')->thenPing('https://cronitor.link/OJA0Ue/complete')->name('monitor_queue_listener')->everyFiveMinutes();

	}

}
