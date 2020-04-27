<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ResetTbl;

class ExportsTest extends TestCase
{
    use RefreshDatabase,ResetTbl;

    protected $helper;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('database.default','testing');
        $this->reset_all();

        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $user->roles()->attach(1);
        $this->actingAs($user);

        $this->helper = new \App\Droit\Helper\Helper();
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

   public function testExportArrets()
    {
        \Excel::fake();

        //Create a categorie
        $categorie = factory(\App\Droit\Categorie\Entities\Categorie::class)->create();

        // Create an arret
        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create(['reference' => 'reference123']);
        $arret->categories()->attach([$categorie->id]);

        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create(['reference' => 'reference543']);
        $arret->categories()->attach([$categorie->id]);

        $response = $this->post('admin/categorie/export', ['id' => $categorie->id]);

        $filename = 'arrets_categories_'.\Str::slug($categorie->title).'_'. date('dmy').'.xlsx';

        \Excel::assertDownloaded($filename, function(\App\Exports\ArretListExport $export) {
            // Assert that the correct export is downloaded.
            return in_array('reference123',\Arr::flatten($export->array())) && in_array('reference543',\Arr::flatten($export->array()));
        });
    }

    public function testExportStock()
    {
        \Excel::fake();

        $date  = \Carbon\Carbon::today()->toDateString();
        $today = \Carbon\Carbon::today()->format('d/m/y');

        $product = factory(\App\Droit\Shop\Product\Entities\Product::class)->create();
        $stock = factory(\App\Droit\Shop\Stock\Entities\Stock::class)->create([
            'product_id' => $product->id,
            'amount'     => '5',
            'operator'   => '+',
            'motif' => 'motif du changement',
            'created_at' => $date
        ]);

        $product = $product->fresh();

        $response = $this->get('admin/stock/export/'.$product->id);

        $filename = 'Export_historique_stock_' . date('dmy').'.xlsx';

        $export = new \App\Exports\StockExport($product->stocks);

        \Excel::assertDownloaded($filename, function($export) use ($today) {
            // Assert that the correct export is downloaded.
            $data = [$today,'motif du changement','+',5,5];
            $results = \Arr::flatten($export->array());
            return $results == $data;
        });
    }

    public function testExportEmailsList()
    {
        \Excel::fake();

        $liste = factory(\App\Droit\Newsletter\Entities\Newsletter_lists::class)->create(['title' => 'OneTitle']);
        $email = factory(\App\Droit\Newsletter\Entities\Newsletter_emails::class)->create([
            'email'   => 'info@domaine.ch',
            'list_id' => $liste->id
        ]);

        $response = $this->post('build/export',['list_id' => $liste->id]);

        $filename = 'Export_liste_'.$liste->title.'.xlsx';

        $export = new \App\Exports\ListExport($liste);

        \Excel::assertDownloaded($filename, function($export)  {
            // Assert that the correct export is downloaded.
            return $export->collection()->flatten(1)->contains('info@domaine.ch');
        });
    }

    public function testOrderExportNormal()
    {
        \Excel::fake();

        $make  = new \tests\factories\ObjectFactory();
        // Create orders
        $orders = $make->order(2);

        $orders_array = $orders->map(function ($order) {
            return [
                'Numero'  => $order->order_no,
                'Date'    => $order->created_at->format('d.m.Y'),
                'Prix'    => $order->amount / 100,
                'Port'    => $order->total_shipping,
                'Total'   => $order->total_with_shipping,
                'Paye'    => '',
                'Status'  => $order->total_with_shipping > 0 ? $order->status_code['status']: 'Gratuit'
            ];
        })->toArray();

        $data['period']['start'] = \Carbon\Carbon::today()->subDays(1)->toDateString();
        $data['period']['end'] = \Carbon\Carbon::today()->addDays(1)->toDateString();
        $data['columns'] = null;
        $data['details'] = null;
        $data['status'] = null;
        $data['send'] = null;
        $data['onlyfree'] = null;
        $data['export'] = true;

        $this->doAssertOrder($data,$orders,$orders_array);
    }

    public function testOrderExportStatusAndFree()
    {
        \Excel::fake();

        $make  = new \tests\factories\ObjectFactory();
        // Create orders
        $orders = $make->order(2, null, 1);

        $orders_array = $orders->map(function ($order) {
            return [
                'Numero'  => $order->order_no,
                'Date'    => $order->created_at->format('d.m.Y'),
                'Prix'    => $order->amount / 100,
                'Port'    => $order->total_shipping,
                'Total'   => $order->total_with_shipping,
                'Paye'    => '',
                'Status'  => $order->total_with_shipping > 0 ? $order->status_code['status'] : 'Gratuit'
            ];
        })->toArray();

        $data['period']['start'] = \Carbon\Carbon::today()->subDays(1)->toDateString();
        $data['period']['end']   = \Carbon\Carbon::today()->addDays(1)->toDateString();
        $data['columns']  = null;
        $data['details']  = null;
        $data['status']   = 'pending';
        $data['send']     = null;
        $data['onlyfree'] = 1;
        $data['export']   = true;

        $this->doAssertOrder($data,$orders,$orders_array);
    }

    public function doAssertOrder($data,$orders,$orders_array)
    {
        $title    = 'Commandes du '.$this->helper->betweenTwoDates($data['period']['start'],$data['period']['end']);
        $filename = 'commandes' . date('dmy').'.xlsx';

        $response = $this->post('admin/orders', $data);

        $export = new \App\Exports\OrderExport($orders,$data['columns'],$title, $data['details']);

        \Excel::assertDownloaded($filename, function($export) use ($orders_array)  {
            // Assert that the correct export is downloaded.
            $data = $export->array();

            $data = array_slice($data, 2);
            $data = array_slice($data, 0, -1);

            return $data == $orders_array;
        });
    }
}
