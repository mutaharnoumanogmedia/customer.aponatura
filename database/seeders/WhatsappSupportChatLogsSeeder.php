<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WhatsappSupportChatLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            $chatMessages = ($faker->sentences($faker->numberBetween(2, 6)));
            $chatMessagesCount = count($chatMessages);
            DB::table('whatsapp_support_chat_logs')->insert([
                'whatsapp_session_chat_id' => $faker->numberBetween(1000, 9999),
                'agent_id'                 => $faker->numberBetween(1, 2),
                'agent_name'               => $faker->randomElement(['Alice', 'Bob']),
                'customer_name'            => $faker->name,
                'whatsapp_number'          => $faker->phoneNumber,
                'review_sent'              => $faker->boolean,
                'review_stars'             => $faker->optional()->numberBetween(1, 5),
                'review_note'              => $faker->optional()->sentence,
                'chat_log'                 => json_encode($chatMessages, true),
                'chat_started_at'          => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                'chat_ended_at'            => $faker->dateTimeBetween('now', '+1 hour')->format('Y-m-d H:i:s'),
                'total_messages'           => $chatMessagesCount,
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        }
    }
}
