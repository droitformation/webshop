<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //$this->call(CalcTauxTableSeeder::class);
        //$this->call(CalcIpcTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(CivilitesTableSeeder::class);
        $this->call(ProfessionsTableSeeder::class);
        $this->call(CantonsTableSeeder::class);
        $this->call(PaysTableSeeder::class);
        $this->call(Adresse_typesTableSeeder::class);

        $this->call(LocationTableSeeder::class);
        $this->call(OrganisateursTableSeeder::class);
        $this->call(ShippingTableSeeder::class);
        $this->call(PayementTableSeeder::class);
        $this->call(AttributTableSeeder::class);
        $this->call(SiteTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(RegistryTableSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(NewsletterTableSeeder::class);

/*
        $this->call(Adresse_typesTableSeeder::class);
        $this->call(CivilitesTableSeeder::class);
        $this->call(ProfessionsTableSeeder::class);
        $this->call(CantonsTableSeeder::class);
        $this->call(PaysTableSeeder::class);
        $this->call(SpecialisationTableSeeder::class);
        $this->call(MembreTableSeeder::class);*/
        

        // Colloque
/*
        $this->call(CompteTableSeeder::class);
        $this->call(LocationTableSeeder::class);
        $this->call(OrganisateursTableSeeder::class);
*/

    /*
        // Shop
        $this->call(ShippingTableSeeder::class);
        $this->call(PayementTableSeeder::class);
        $this->call(DomainTableSeeder::class);
        $this->call(AttributTableSeeder::class);
        $this->call(AuthorTableSeeder::class);
        $this->call(ShopCategorieTableSeeder::class);

        // Site

        $this->call(SiteTableSeeder::class);
        $this->call(MenuTableSeeder::class);
  */

        // Content
        // $this->call(CategorieTableSeeder::class);
        
        // User
        // $this->call(RoleTableSeeder::class);

        // Abo
        //$this->call(AboTableSeeder::class);

        //Newsletter
        //$this->call(TypeSeeder::class);

        // Registry
        //$this->call(RegistryTableSeeder::class);

        // Bloc
        //$this->call(BlocTableSeeder::class);
        //$this->call(BlocPageTableSeeder::class);

        // Content
        //$this->call(ContentTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
