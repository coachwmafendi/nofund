<?php

namespace Database\Seeders;

use App\Enums\DonationStatus;
use App\Models\ActivityLog;
use App\Models\ApiKey;
use App\Models\BankAccount;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Organization;
use App\Models\Payout;
use App\Models\RecurringPlan;
use App\Models\Refund;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks for cleaner seeding (MySQL only)
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        $this->command->info('Seeding organizations...');
        $organizations = Organization::factory(5)->create();

        foreach ($organizations as $organization) {
            $this->seedOrganization($organization);
        }

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->command->info('Seeding completed!');
        $this->printRecordCounts();
    }

    /**
     * Seed data for a specific organization.
     */
    private function seedOrganization(Organization $organization): void
    {
        $this->command->info("  Seeding data for organization: {$organization->name}");

        // 1. Users (5-10 per org)
        $userCount = random_int(5, 10);
        $users = User::factory($userCount)->for($organization)->create();

        // Make first user an admin
        $users->first()->update(['role' => \App\Enums\UserRole::ADMIN]);

        // 2. Bank Accounts (1-3 per org)
        $bankAccountCount = random_int(1, 3);
        $bankAccounts = BankAccount::factory($bankAccountCount)->for($organization)->create();

        // Mark first one as default
        if ($bankAccounts->isNotEmpty()) {
            $bankAccounts->first()->update(['is_default' => true]);
        }

        // 3. Campaigns (3-8 per org)
        $campaignCount = random_int(3, 8);
        $campaigns = Campaign::factory($campaignCount)->for($organization)->create([
            'created_by' => $users->random()->id,
        ]);

        // 4. Donors (20-50 per org)
        $donorCount = random_int(20, 50);
        $donors = Donor::factory($donorCount)->for($organization)->create();

        // 5. Donations (50-200 per org)
        $donationCount = random_int(50, 200);
        $donations = Donation::factory($donationCount)->for($organization)->create();

        // Get succeeded donations for later use
        $succeededDonations = $donations->where('status', DonationStatus::SUCCEEDED);

        // 6. Recurring Plans (5-15 per org)
        $recurringCount = random_int(5, 15);
        foreach (range(1, $recurringCount) as $i) {
            RecurringPlan::factory()->for($organization)->create([
                'donor_id' => $donors->random()->id,
                'campaign_id' => fake()->boolean(70) ? $campaigns->random()->id : null,
            ]);
        }

        // 7. Refunds (5-10% of succeeded donations)
        if ($succeededDonations->count() > 0) {
            $refundCount = (int) floor($succeededDonations->count() * fake()->randomFloat(2, 0.05, 0.10));
            $refundCount = max(1, min($refundCount, $succeededDonations->count()));

            $refundDonations = $succeededDonations->random(min($refundCount, $succeededDonations->count()));

            foreach ($refundDonations as $donation) {
                Refund::factory()->for($organization)->create([
                    'donation_id' => $donation->id,
                    'amount' => fake()->randomFloat(2, 10, min($donation->amount, 500)),
                    'processed_by' => $users->random()->id,
                ]);
            }
        }

        // 8. Payouts (2-5 per org)
        $payoutCount = random_int(2, 5);
        for ($i = 0; $i < $payoutCount; $i++) {
            Payout::factory()->for($organization)->create([
                'bank_account_id' => $bankAccounts->random()->id,
            ]);
        }

        // 9. Transactions (linked to donations, refunds, payouts)
        foreach ($donations->where('status', DonationStatus::SUCCEEDED) as $donation) {
            Transaction::factory()->for($organization)->create([
                'transactionable_type' => Donation::class,
                'transactionable_id' => $donation->id,
                'type' => \App\Enums\TransactionType::DONATION,
                'amount' => $donation->amount,
                'description' => "Donation received from {$donation->donor_name}",
            ]);
        }

        // Add refund transactions
        $refunds = Refund::where('organization_id', $organization->id)
            ->where('status', \App\Enums\RefundStatus::SUCCEEDED)
            ->get();
        foreach ($refunds as $refund) {
            Transaction::factory()->for($organization)->create([
                'transactionable_type' => Refund::class,
                'transactionable_id' => $refund->id,
                'type' => \App\Enums\TransactionType::REFUND,
                'amount' => $refund->amount,
                'description' => "Refund processed for donation",
            ]);
        }

        // Add payout transactions
        $payouts = Payout::where('organization_id', $organization->id)
            ->where('status', \App\Enums\PayoutStatus::PAID)
            ->get();
        foreach ($payouts as $payout) {
            Transaction::factory()->for($organization)->create([
                'transactionable_type' => Payout::class,
                'transactionable_id' => $payout->id,
                'type' => \App\Enums\TransactionType::PAYOUT,
                'amount' => $payout->amount,
                'description' => "Payout to bank account",
            ]);
        }

        // 10. API Keys (1-3 per org)
        $apiKeyCount = random_int(1, 3);
        ApiKey::factory($apiKeyCount)->for($organization)->create();

        // 11. Webhooks (1-2 per org)
        $webhookCount = random_int(1, 2);
        $webhooks = Webhook::factory($webhookCount)->for($organization)->create();

        // 12. Webhook Deliveries (0-10 per webhook)
        foreach ($webhooks as $webhook) {
            $deliveryCount = random_int(0, 10);
            for ($i = 0; $i < $deliveryCount; $i++) {
                WebhookDelivery::factory()->create([
                    'webhook_id' => $webhook->id,
                ]);
            }
        }

        // 13. Activity Logs (20-50 per org)
        $activityLogCount = random_int(20, 50);
        ActivityLog::factory($activityLogCount)->for($organization)->create();
    }

    /**
     * Print record counts per table.
     */
    private function printRecordCounts(): void
    {
        $this->command->info('');
        $this->command->info('=== Record Counts ===');

        $tables = [
            'organizations' => Organization::class,
            'users' => User::class,
            'bank_accounts' => BankAccount::class,
            'campaigns' => Campaign::class,
            'donors' => Donor::class,
            'donations' => Donation::class,
            'recurring_plans' => RecurringPlan::class,
            'refunds' => Refund::class,
            'payouts' => Payout::class,
            'transactions' => Transaction::class,
            'api_keys' => ApiKey::class,
            'webhooks' => Webhook::class,
            'webhook_deliveries' => WebhookDelivery::class,
            'activity_logs' => ActivityLog::class,
        ];

        foreach ($tables as $name => $model) {
            $count = $model::count();
            $this->command->info("  {$name}: {$count}");
        }

        $this->command->info('');
    }
}