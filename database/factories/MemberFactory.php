<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(gender: 'M'),
            'school_name' => fake()->name
        ];
    }
}


/**
 *  $table->id();
            $table->string('name');
            $table->string('school_name');
            $table->enum('gender',['L','P']);
            $table->date('date_of_birth');
            $table->foreignId('costume_size_id');
            $table->string('costume_label',40);
            $table->foreignId('marketing_source_id')->nullable();
            $table->string('marketing_source_other')->nullable();
            $table->string('instagram');
            $table->timestamps();
 */