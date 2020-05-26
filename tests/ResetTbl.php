<?php namespace Tests;

trait ResetTbl
{

    function reset_all(){

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('abos')->truncate();
        \DB::table('abo_factures')->truncate();
        \DB::table('abo_rappels')->truncate();
        \DB::table('abo_users')->truncate();
        \DB::table('adresse_members')->truncate();
        \DB::table('adresse_specialisations')->truncate();
        \DB::table('adresses')->truncate();
        \DB::table('analyse_authors')->truncate();
        \DB::table('analyse_categories')->truncate();
        \DB::table('analyses')->truncate();
        \DB::table('analyses_arret')->truncate();
        \DB::table('arret_categories')->truncate();
        \DB::table('arrets')->truncate();
        \DB::table('arrets_groupes')->truncate();
        \DB::table('calculette_ipc')->truncate();
        \DB::table('calculette_taux')->truncate();
        \DB::table('colloque_attestations')->truncate();
        \DB::table('colloque_documents')->truncate();
        \DB::table('colloque_inscription_rappels')->truncate();
        \DB::table('colloque_inscriptions')->truncate();
        \DB::table('colloque_inscriptions_groupes')->truncate();
        \DB::table('colloque_inscriptions_participants')->truncate();
        \DB::table('colloque_occurrence_prices')->truncate();
        \DB::table('colloque_occurrence_users')->truncate();
        \DB::table('colloque_occurrences')->truncate();
        \DB::table('colloque_option_groupes')->truncate();
        \DB::table('colloque_option_users')->truncate();
        \DB::table('colloque_options')->truncate();
        \DB::table('colloque_organisateurs')->truncate();
        \DB::table('colloque_prices')->truncate();
        \DB::table('colloque_specialisations')->truncate();
        \DB::table('colloques')->truncate();
        \DB::table('groupes')->truncate();
        \DB::table('list_specialisations')->truncate();
        \DB::table('members')->truncate();
        \DB::table('newsletter_campagnes')->truncate();
        \DB::table('newsletter_clipboards')->truncate();
        \DB::table('newsletter_contents')->truncate();
        \DB::table('newsletter_emails')->truncate();
        \DB::table('newsletter_lists')->truncate();
        \DB::table('newsletter_subscriptions')->truncate();
        \DB::table('newsletter_tracking')->truncate();
        \DB::table('newsletter_types')->truncate();
        \DB::table('newsletter_users')->truncate();
        \DB::table('reminders')->truncate();
        \DB::table('seminaire_colloques')->truncate();
        \DB::table('seminaire_subjects')->truncate();
        \DB::table('seminaires')->truncate();
        \DB::table('shop_coupon_product')->truncate();
        \DB::table('shop_coupons')->truncate();
        \DB::table('shop_order_products')->truncate();
        \DB::table('shop_orders')->truncate();
        \DB::table('shop_parent_categories')->truncate();
        \DB::table('shop_product_attributes')->truncate();
        \DB::table('shop_product_authors')->truncate();
        \DB::table('shop_product_categories')->truncate();
        \DB::table('shop_product_domains')->truncate();
        \DB::table('shop_products')->truncate();
        \DB::table('shop_rappels')->truncate();
        \DB::table('shop_stocks')->truncate();
        \DB::table('sondage_avis')->truncate();
        \DB::table('sondage_avis_items')->truncate();
        \DB::table('sondage_avis_reponses')->truncate();
        \DB::table('sondage_reponses')->truncate();
        \DB::table('sondages')->truncate();
        \DB::table('subject_authors')->truncate();
        \DB::table('subject_categories')->truncate();
        \DB::table('subjects')->truncate();
        \DB::table('transaction_references')->truncate();
        \DB::table('users')->truncate();
        \DB::table('inscription_rabais')->truncate();
        \DB::table('price_link')->truncate();
        \DB::table('price_link_colloques')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->artisan('db:seed');

    }
}