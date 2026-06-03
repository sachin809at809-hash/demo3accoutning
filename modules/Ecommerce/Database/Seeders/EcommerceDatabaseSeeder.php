<?php

namespace Modules\Ecommerce\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Ecommerce\Models\EcommerceCategory;
use Modules\Ecommerce\Models\EcommerceProduct;

class EcommerceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $company_id = company_id() ?? 1;

        // Categories
        $electronics = EcommerceCategory::create([
            'company_id' => $company_id,
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Gadgets, devices, and accessories',
            'is_active' => true,
        ]);

        $groceries = EcommerceCategory::create([
            'company_id' => $company_id,
            'name' => 'Groceries',
            'slug' => 'groceries',
            'description' => 'Daily essentials delivered in 10 minutes',
            'is_active' => true,
        ]);

        // Products
        EcommerceProduct::create([
            'company_id' => $company_id,
            'category_id' => $electronics->id,
            'name' => 'Wireless Noise-Cancelling Headphones',
            'slug' => 'wireless-headphones',
            'price' => 299.99,
            'stock_quantity' => 50,
            'description' => "Experience premium sound with our industry-leading noise cancellation technology.\n\nUp to 30 hours of battery life and quick charging support.",
            'is_active' => true,
        ]);

        EcommerceProduct::create([
            'company_id' => $company_id,
            'category_id' => $electronics->id,
            'name' => 'Smart Watch Pro',
            'slug' => 'smart-watch-pro',
            'price' => 199.50,
            'stock_quantity' => 120,
            'description' => 'Track your fitness, receive notifications, and stay connected on the go with the new Smart Watch Pro.',
            'is_active' => true,
        ]);

        EcommerceProduct::create([
            'company_id' => $company_id,
            'category_id' => $groceries->id,
            'name' => 'Organic Avocados (Pack of 4)',
            'slug' => 'organic-avocados',
            'price' => 5.99,
            'stock_quantity' => 300,
            'description' => 'Fresh, ripe, and creamy organic avocados. Perfect for guacamole or avocado toast.',
            'is_active' => true,
        ]);

        EcommerceProduct::create([
            'company_id' => $company_id,
            'category_id' => $groceries->id,
            'name' => 'Artisan Sourdough Bread',
            'slug' => 'sourdough-bread',
            'price' => 4.50,
            'stock_quantity' => 25,
            'description' => 'Freshly baked artisan sourdough bread with a crispy crust and chewy interior.',
            'is_active' => true,
        ]);
        
        EcommerceProduct::create([
            'company_id' => $company_id,
            'category_id' => null,
            'name' => 'Premium Water Bottle',
            'slug' => 'premium-water-bottle',
            'price' => 24.99,
            'stock_quantity' => 0,
            'description' => 'Insulated stainless steel water bottle keeps drinks cold for 24 hours or hot for 12 hours.',
            'is_active' => true,
        ]);
    }
}
