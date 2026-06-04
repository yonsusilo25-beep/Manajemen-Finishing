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

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'production', 'manager', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
        DB::statement("UPDATE users SET role = 'manager' WHERE role IN ('warehouse', 'production')");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'manager', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'manager', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
        DB::statement("UPDATE users SET role = 'warehouse' WHERE role = 'manager'");
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'warehouse', 'foreman', 'foreman wax room', 'foreman mould room', 'foreman melting', 'supervisor') NOT NULL DEFAULT 'foreman'");
    }
};
