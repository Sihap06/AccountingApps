<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonPath = storage_path('app/payment-methods.json');
        
        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true);
            
            foreach ($data['payment_methods'] as $method) {
                PaymentMethod::updateOrCreate(
                    ['code' => $method['code']],
                    ['name' => $method['name']]
                );
            }
        }
    }
}
