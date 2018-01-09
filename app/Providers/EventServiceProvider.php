<?php namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
        'App\Events\OrderWasPlaced' => [
            'App\Listeners\EmailOrderConfirmation'
        ],
        'App\Events\InscriptionWasRegistered' => [
            'App\Listeners\EmailInscriptionConfirmation'
        ],
        'App\Events\NewAboRequest' => [
            'App\Listeners\EmailAboConfirmation'
        ],
        'App\Events\SubscriptionAddTag' => ['App\Listeners\SubscribeToNewsletter'],
        'App\Events\SubscriptionRemoveTag' => ['App\Listeners\UnsubscribeFromNewsletter']
	];

	/**
	 * Register any other events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
	}

}
