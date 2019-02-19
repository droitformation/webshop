<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class StatsTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);
    }

    public function testMakeOrdersToTest()
    {
        $amount = 12000;

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Premier titre','price' => 2000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Second titre','price' => 2000]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Autre titre','price' => 2000]);

        $today = \Carbon\Carbon::today()->startOfDay()->toDateTimeString();

        $order = $this->makeOrder([$product1->id, $product2->id, $product3->id],$amount,$today);

        $this->assertEquals(3,$order->products->count());
        $this->assertEquals(12000,$order->amount);
        $this->assertEquals($today,$order->created_at);
    }

    public function testQueryByPeriod()
    {
        // make some orders
        $amount1 = 12000;

        $product1 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Premier titre','price' => 2000]);
        $product2 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Second titre','price' => 2000]);
        $product3 = factory(\App\Droit\Shop\Product\Entities\Product::class)->create(['title' => 'Autre titre','price' => 2000]);

        $order1 = $this->makeOrder([$product1->id, $product1->id, $product3->id],$amount1, \Carbon\Carbon::today()->startOfDay()->subDays(3)->toDateTimeString());

        $amount2 = 6000;

        // Premier titre 2x, Autre titre 2x, Second titre 1x
        // Sum price 200.00
        // Sum products 5

        $order2 = $this->makeOrder([$product2->id, $product3->id],$amount2, \Carbon\Carbon::today()->startOfDay()->subDays(1)->toDateTimeString());

        $worker = new \App\Droit\Statistique\StatistiqueWorker();

        $start = \Carbon\Carbon::today()->subDays(4)->toDateString();
        $end   = \Carbon\Carbon::today()->subDays(1)->toDateString();

        $sort = ['start' => $start, 'end' => $end];

        $results = $worker->setFilters([])->setSort($sort)
            ->setAggregate(['model' => 'order', 'name' => 'sum', 'type' => 'price']) // product or price or title (title,count)
            ->makeQuery('order')
            ->aggregate();

        $this->assertEquals(200.00,$results);

        $results = $worker->setFilters([])->setSort($sort)
            ->setAggregate(['model' => 'order', 'name' => 'sum', 'type' => 'product']) // product or price or title (title,count)
            ->makeQuery('order')
            ->aggregate();

        $this->assertEquals(5,$results);

        $results = $worker->setFilters([])->setSort($sort)
            ->setAggregate(['model' => 'order', 'name' => 'sum', 'type' => 'title']) // product or price or title (title,count)
            ->makeQuery('order')
            ->aggregate();

        $expected = [
            ['count' => 2, 'title' => 'Autre titre'],
            ['count' => 2, 'title' => 'Premier titre'],
            ['count' => 1, 'title' => 'Second titre'],
        ];

        $this->assertEquals($expected,array_values($results->toArray()));

    }

    public function testInscriptionStats()
    {
        $worker   = new \App\Droit\Statistique\StatistiqueWorker();

        $inscription1 = $this->makeInscription(\Carbon\Carbon::today()->subDays(2)->toDateString());
        $inscription2 = $this->makeInscription(\Carbon\Carbon::today()->subDays(3)->toDateString());

        $start = \Carbon\Carbon::today()->subDays(4)->toDateString();
        $end   = \Carbon\Carbon::today()->subDays(1)->toDateString();

        $sort = ['start' => $start, 'end' => $end];

        $results = $worker->setFilters([])->setSort($sort)
            ->setAggregate(['model' => 'inscription', 'name' => 'sum', 'type' => 'price']) // product or price or title (title,count)
            ->makeQuery('inscription')
            ->aggregate();

        $this->assertEquals(80.00,$results);

        $results = $worker->setFilters([])->setSort($sort)
            ->setAggregate(['model' => 'inscription', 'name' => 'sum', 'type' => null]) // product or price
            ->makeQuery('inscription')
            ->aggregate();

        $this->assertEquals(2,$results);

    }

    /*
     * Helpers
     * */
    public function makeOrder($products,$amount,$created_at)
    {
        $order = factory(\App\Droit\Shop\Order\Entities\Order::class)->create(['amount' => $amount, 'created_at' => $created_at]);

        $order->products()->attach($products);

        $order = $order->fresh();

        return $order;
    }

    public function makeInscription($created_at)
    {
        $make     = new \tests\factories\ObjectFactory();

        $colloque = $make->colloque(); // 'price' => 4000
        $person   = $make->makeUser();

        return factory(\App\Droit\Inscription\Entities\Inscription::class)->create([
            'user_id' => $person->id,
            'price_id' => $colloque->prices->first()->id,
            'group_id' => null,
            'colloque_id' => $colloque->id,
            'created_at' => $created_at
        ]);
    }
}
