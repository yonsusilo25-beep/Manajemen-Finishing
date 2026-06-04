<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'produksi', 'production', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
        DB::statement("UPDATE users SET role = 'production' WHERE role = 'produksi'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'production', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'produksi', 'production', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
        DB::statement("UPDATE users SET role = 'produksi' WHERE role = 'production'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'produksi', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
    }
};
