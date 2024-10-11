<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Message;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from' => $this->faker->email(),
            'content' => $this->faker->paragraph(),
            'to' => json_encode([$this->faker->email(), $this->faker->email()]), 
            'files' => json_encode([$this->faker->filePath(), $this->faker->filePath()]),
        ];
    }
}
