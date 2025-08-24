<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration should only be created AFTER running the SQL script
        // It serves as a record that the subscription system was removed
        
        // Verify that subscription tables no longer exist
        if (Schema::hasTable('subscription_plans') || 
            Schema::hasTable('user_subscriptions') || 
            Schema::hasTable('subscription_plan_course') || 
            Schema::hasTable('subscription_plan_digital_product')) {
            
            throw new \Exception('Please run the SQL script first to remove subscription tables manually.');
        }
        
        // Log the successful removal
        DB::statement("INSERT INTO migrations (migration, batch) VALUES ('subscription_system_removed_manually', (SELECT COALESCE(MAX(batch), 0) + 1 FROM (SELECT batch FROM migrations) as temp))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This cannot be fully reversed as we've permanently removed subscription data
        throw new \Exception('Cannot reverse subscription system removal. This would require restoring from backup.');
    }
};