<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerformanceIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Index for dashboard queries - user orders with status and expiry
            $table->index(['user_id', 'status', 'expiry'], 'idx_orders_user_status_expiry');
            
            // Index for product-based queries
            $table->index(['product_id', 'status', 'expiry'], 'idx_orders_product_status_expiry');
            
            // Index for test-based queries
            $table->index(['test_id', 'status', 'expiry'], 'idx_orders_test_status_expiry');
        });

        Schema::table('attempts', function (Blueprint $table) {
            // Index for user attempts - most common query
            $table->index(['user_id', 'test_id'], 'idx_attempts_user_test');
            
            // Index for test analytics
            $table->index(['test_id', 'user_id', 'accuracy'], 'idx_attempts_test_user_accuracy');
            
            // Index for session-based attempts
            $table->index(['session_id', 'test_id'], 'idx_attempts_session_test');
        });

        Schema::table('tests', function (Blueprint $table) {
            // Index for active tests with search
            $table->index(['status', 'name'], 'idx_tests_status_name');
            
            // Index for category-based filtering
            $table->index(['category_id', 'status', 'price'], 'idx_tests_category_status_price');
            
            // Index for client-specific tests
            $table->index(['client_slug', 'status'], 'idx_tests_client_status');
        });

        // Check if product_test table exists and add index
        if (Schema::hasTable('product_test')) {
            Schema::table('product_test', function (Blueprint $table) {
                $table->index(['product_id', 'test_id'], 'idx_product_test_relation');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // Index for user lookups
            $table->index(['email', 'status'], 'idx_users_email_status');
            $table->index(['client_slug', 'status'], 'idx_users_client_status');
        });

        Schema::table('products', function (Blueprint $table) {
            // Index for product queries
            $table->index(['status', 'client_slug'], 'idx_products_status_client');
        });

        // Add indexes to categories if table exists
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index(['status', 'slug'], 'idx_categories_status_slug');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_user_status_expiry');
            $table->dropIndex('idx_orders_product_status_expiry');
            $table->dropIndex('idx_orders_test_status_expiry');
        });

        Schema::table('attempts', function (Blueprint $table) {
            $table->dropIndex('idx_attempts_user_test');
            $table->dropIndex('idx_attempts_test_user_accuracy');
            $table->dropIndex('idx_attempts_session_test');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropIndex('idx_tests_status_name');
            $table->dropIndex('idx_tests_category_status_price');
            $table->dropIndex('idx_tests_client_status');
        });

        if (Schema::hasTable('product_test')) {
            Schema::table('product_test', function (Blueprint $table) {
                $table->dropIndex('idx_product_test_relation');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email_status');
            $table->dropIndex('idx_users_client_status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_status_client');
        });

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex('idx_categories_status_slug');
            });
        }
    }
}