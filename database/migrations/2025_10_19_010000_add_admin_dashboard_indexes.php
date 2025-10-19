<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminDashboardIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * CRITICAL: Add missing indexes specifically for admin dashboard performance
     *
     * These indexes complement the existing performance indexes and target
     * the specific queries causing admin dashboard slowness.
     *
     * @return void
     */
    public function up()
    {
        // 1. ATTEMPTS TABLE - Add missing single column indexes for admin queries
        Schema::table('attempts', function (Blueprint $table) {
            // For ORDER BY created_at DESC in admin dashboard
            if (!$this->indexExists('attempts', 'idx_attempts_created_at')) {
                $table->index('created_at', 'idx_attempts_created_at');
            }

            // For WHERE answer IS NULL (writing test filtering)
            if (!$this->indexExists('attempts', 'idx_attempts_answer')) {
                $table->index('answer', 'idx_attempts_answer');
            }
        });

        // 2. ORDERS TABLE - Add single column indexes for better join performance
        Schema::table('orders', function (Blueprint $table) {
            // These allow MySQL to use index for joins even without full composite match
            if (!$this->indexExists('orders', 'idx_orders_status_single')) {
                $table->index('status', 'idx_orders_status_single');
            }

            if (!$this->indexExists('orders', 'idx_orders_product_id_single')) {
                $table->index('product_id', 'idx_orders_product_id_single');
            }
        });

        // 3. MOCK_ATTEMPTS TABLE - CRITICAL: No indexes at all!
        Schema::table('mock_attempts', function (Blueprint $table) {
            // For WHERE status = -1 (incomplete attempts)
            if (!$this->indexExists('mock_attempts', 'idx_mock_attempts_status')) {
                $table->index('status', 'idx_mock_attempts_status');
            }

            // For ORDER BY id DESC
            // (Primary key already indexed, but add created_at for alternative sorting)
            if (!$this->indexExists('mock_attempts', 'idx_mock_attempts_created')) {
                $table->index('created_at', 'idx_mock_attempts_created');
            }

            // For foreign key lookups
            if (!$this->indexExists('mock_attempts', 'idx_mock_attempts_mock_id')) {
                $table->index('mock_id', 'idx_mock_attempts_mock_id');
            }

            if (!$this->indexExists('mock_attempts', 'idx_mock_attempts_user_id')) {
                $table->index('user_id', 'idx_mock_attempts_user_id');
            }

            // Composite for common admin query: WHERE status = -1 ORDER BY created_at DESC
            if (!$this->indexExists('mock_attempts', 'idx_mock_status_created')) {
                $table->index(['status', 'created_at'], 'idx_mock_status_created');
            }
        });

        // 4. USERS TABLE - Add lastlogin_at for sorting new users
        Schema::table('users', function (Blueprint $table) {
            if (!$this->indexExists('users', 'idx_users_lastlogin')) {
                $table->index('lastlogin_at', 'idx_users_lastlogin');
            }
        });
    }

    /**
     * Check if an index exists on a table
     *
     * @param string $table
     * @param string $index
     * @return bool
     */
    private function indexExists($table, $index)
    {
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexes = $sm->listTableIndexes($table);

        return isset($indexes[$index]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attempts', function (Blueprint $table) {
            if ($this->indexExists('attempts', 'idx_attempts_created_at')) {
                $table->dropIndex('idx_attempts_created_at');
            }
            if ($this->indexExists('attempts', 'idx_attempts_answer')) {
                $table->dropIndex('idx_attempts_answer');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'idx_orders_status_single')) {
                $table->dropIndex('idx_orders_status_single');
            }
            if ($this->indexExists('orders', 'idx_orders_product_id_single')) {
                $table->dropIndex('idx_orders_product_id_single');
            }
        });

        Schema::table('mock_attempts', function (Blueprint $table) {
            if ($this->indexExists('mock_attempts', 'idx_mock_attempts_status')) {
                $table->dropIndex('idx_mock_attempts_status');
            }
            if ($this->indexExists('mock_attempts', 'idx_mock_attempts_created')) {
                $table->dropIndex('idx_mock_attempts_created');
            }
            if ($this->indexExists('mock_attempts', 'idx_mock_attempts_mock_id')) {
                $table->dropIndex('idx_mock_attempts_mock_id');
            }
            if ($this->indexExists('mock_attempts', 'idx_mock_attempts_user_id')) {
                $table->dropIndex('idx_mock_attempts_user_id');
            }
            if ($this->indexExists('mock_attempts', 'idx_mock_status_created')) {
                $table->dropIndex('idx_mock_status_created');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if ($this->indexExists('users', 'idx_users_lastlogin')) {
                $table->dropIndex('idx_users_lastlogin');
            }
        });
    }
}
