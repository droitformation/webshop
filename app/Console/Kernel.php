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

        $schedule->command('queue:work --daemon')
            //->pingBefore('https://cronitor.link/OJA0Ue/run')->thenPing('https://cronitor.link/OJA0Ue/complete')
            ->name('monitor_queue_listener')
            ->everyFiveMinutes()
            ->withoutOverlapping();
	}

}
