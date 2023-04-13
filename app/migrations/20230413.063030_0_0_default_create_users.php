<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault8d2203807a9e95dcebb16b5b509704fb extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
            ->addColumn('id', 'primary', ['nullable' => false, 'default' => null])
            ->addColumn('username', 'string', ['nullable' => false, 'default' => null])
            ->addColumn('email', 'string', ['nullable' => false, 'default' => null])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop();
    }
}
