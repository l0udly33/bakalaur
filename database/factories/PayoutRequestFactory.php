<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PayoutRequest;
use App\Models\User;

class PayoutRequestFactory extends Factory
{
    protected $model = PayoutRequest::class;

    public function definition(): array
    {
        return [
            // Sukuriame trainerį, kuris bus susietas su payout
            'trainer_id' => User::factory()->create(['role' => 'trainer']), // Traineris turi būti susietas su PayoutRequest

            // Atsitiktinė suma, kurią traineris gali užsidirbti
            'amount' => $this->faker->randomFloat(2, 10, 500),

            // Atsitiktinis paypal el. paštas
            'paypal_email' => $this->faker->safeEmail,

            // Atsitiktinis statusas, gali būti "pending", "completed" arba "canceled"
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),

            // Sukuriame atsitiktinę datą, per šį laikotarpį gali būti atliekamas filtras pagal datą
            'created_at' => $this->faker->dateTimeThisYear(),  // Galite nustatyti norimą datą, pvz., dabar ar per pastaruosius metus
            'updated_at' => $this->faker->dateTimeThisYear(),  // Galite naudoti tą pačią datą kaip ir created_at
        ];
    }
}
