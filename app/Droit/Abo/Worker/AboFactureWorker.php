<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Worker\AboFactureWorkerInterface;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Jobs\MakeFactureAbo;
use App\Jobs\MergeFactures;
use App\Jobs\NotifyJobFinishedEmail;

use Illuminate\Foundation\Bus\DispatchesJobs;

class AboFactureWorker implements AboFactureWorkerInterface{

    use DispatchesJobs;

    protected $facture;
    protected $generator;
    protected $print;

    public function __construct(AboFactureInterface $facture, PdfGeneratorInterface $generator)
    {
        $this->facture    = $facture;
        $this->generator  = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function generate($product, $abo, $all = false, $date, $print = null)
    {
        // All abonnements for the product
        if(!$abo->abonnements->isEmpty())
        {
            // Take only abonnes
            $abonnes = $abo->abonnements->whereIn('status',['abonne','tiers']);

            // chunk for not to many
            $chunks = $abonnes->chunk(15);

            foreach($chunks as $chunk) {
                // dispatch job to make 15 factures
                $job = (new MakeFactureAbo($chunk, $product, $all, $date, $print));
                $this->dispatch($job);
            }

            $product = $abo->products->first(function ($item, $key) use($product) {
                return $item->id == $product->id;
            });

            // Throw exception if there is no product
            if(!$product) {
                throw new \App\Exceptions\ProductNotFoundException('Product not found');
            }

            // dispatch types of abos ¯\_(ツ)_/¯
            $status_files = $this->prepareFiles($abonnes, $product);

            // Job for merging documents
            $merge = (new MergeFactures($product, $abo, $status_files));
            $this->dispatch($merge);

            // Job notify merging is done
            $job = (new NotifyJobFinishedEmail('Les factures ont été crées Veuillez penser à les attacher. Nom du fichier: factures_'.$product->reference.'_'.$product->edition_clean));
            $this->dispatch($job);

        }
    }

    public function make($facture)
    {
        $this->generator->setPrint(true);
        $this->generator->makeAbo('facture', $facture);
    }

    public function update($abonnement)
    {
        $factures = $abonnement->factures;

        if(!$factures->isEmpty()) {
            foreach($factures as $facture) {

                // Make or remake the facture is the status is abonne
                if($abonnement->status == 'abonne' || $abonnement->status == 'tiers') {
                    $this->generator->setPrint(true);
                    $this->generator->makeAbo('facture', $facture);
                }
                else {
                     // delete all files because the status is free and don't need a facture 
                     if (!\File::exists($facture->abo_facture)) {
                         \File::delete($facture->abo_facture);
                     }
                }
            }
        }
    }

    /*
    * Bind all invoices
    * */
    public function bind($product, $abo)
    {
        $abonnes = $abo->abonnements->whereIn('status',['abonne','tiers']);

        // dispatch types of abos ¯\_(ツ)_/¯
        $status_files = $this->prepareFiles($abonnes, $product);

        // Job for merging documents
        $merge = (new MergeFactures($product, $abo, $status_files));
        $this->dispatch($merge);

        // Job notify merging is done
        $job = (new NotifyJobFinishedEmail('Les factures ont été crées et attachés.'));
        $this->dispatch($job);

        flash('Les factures sont re-attachés.<br/>Rafraichissez la page pour mettre à jour le document.')->success();

        return redirect()->back();
    }

    public function prepareFiles($abonnes, $product)
    {
        return $abonnes
            ->groupBy(function ($item, $key) {
                if($item->status == 'tiers'){return 'tiers';}
                if($item->exemplaires > 1){return 'multiple';}
                return 'abonne';
            })->map(function ($item, $key) use ($product) {
                return $item->reject(function ($value, $key) use ($product)  {

                    $dir  = 'files/abos/facture/'.$product->id;
                    $path = 'facture_'.$product->reference.'-'.$value->id.'_'.$product->id.'.pdf';

                    return !\File::exists(public_path($dir.'/'.$path)) ? true : false;
                })->mapWithKeys(function ($item) use ($product)  {

                    $dir  = 'files/abos/facture/'.$product->id;
                    $path = 'facture_'.$product->reference.'-'.$item->id.'_'.$product->id.'.pdf';

                    return [$item->numero => public_path($dir.'/'.$path)];
                });
            });
    }
}