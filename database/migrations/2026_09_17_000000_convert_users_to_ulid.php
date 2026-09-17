<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $userFks = [
        'player_profiles' => 'user_id',
        'scout_profiles' => 'user_id',
        'scouting_interests' => 'scout_id',
        'favorites' => 'scout_id',
    ];

    public function up(): void
    {
        if ($this->isIdUlid()) {
            return;
        }

        $this->dropUserForeignKeys();

        Schema::table('users', function (Blueprint $table) {
            $table->char('_ulid_temp', 26)->nullable();
        });

        DB::table('users')->select('id')->orderBy('id')->each(function (object $user) {
            DB::table('users')->where('id', $user->id)->update([
                '_ulid_temp' => (string) Str::ulid(),
            ]);
        });

        Schema::table('player_profiles', fn (Blueprint $table) => $table->char('user_id', 26)->change());
        Schema::table('scout_profiles', fn (Blueprint $table) => $table->char('user_id', 26)->change());
        Schema::table('scouting_interests', fn (Blueprint $table) => $table->char('scout_id', 26)->change());
        Schema::table('favorites', fn (Blueprint $table) => $table->char('scout_id', 26)->change());
        Schema::table('sessions', fn (Blueprint $table) => $table->char('user_id', 26)->nullable()->change());
        Schema::table('notifications', fn (Blueprint $table) => $table->char('notifiable_id', 26)->change());

        $this->remapChildrenTo('_ulid_temp');

        Schema::table('users', function (Blueprint $table) {
            $table->ulid('id')->change();
        });

        DB::table('users')->update(['id' => DB::raw('_ulid_temp')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('_ulid_temp');
        });

        $this->addUserForeignKeys();
    }

    public function down(): void
    {
        if (! $this->isIdUlid()) {
            return;
        }

        $this->dropUserForeignKeys();

        Schema::table('users', function (Blueprint $table) {
            $table->char('_int_temp', 26)->nullable();
        });

        $index = 1;
        DB::table('users')->select('id')->orderBy('id')->each(function (object $user) use (&$index) {
            DB::table('users')->where('id', $user->id)->update([
                '_int_temp' => (string) $index++,
            ]);
        });

        $this->remapChildrenTo('_int_temp');

        Schema::table('player_profiles', fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->change());
        Schema::table('scout_profiles', fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->change());
        Schema::table('scouting_interests', fn (Blueprint $table) => $table->unsignedBigInteger('scout_id')->change());
        Schema::table('favorites', fn (Blueprint $table) => $table->unsignedBigInteger('scout_id')->change());
        Schema::table('sessions', fn (Blueprint $table) => $table->unsignedBigInteger('user_id')->nullable()->change());
        Schema::table('notifications', fn (Blueprint $table) => $table->unsignedBigInteger('notifiable_id')->change());

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->change();
        });

        DB::table('users')->update(['id' => DB::raw('_int_temp')]);

        if (DB::getDriverName() === 'mysql') {
            DB::statement('alter table users modify id bigint unsigned not null auto_increment');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('_int_temp');
        });

        $this->addUserForeignKeys();
    }

    private function isIdUlid(): bool
    {
        $column = collect(Schema::getColumns('users'))->firstWhere('name', 'id');
        $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

        return str_contains($type, 'char') || str_contains($type, 'varchar');
    }

    private function dropUserForeignKeys(): void
    {
        foreach ($this->userFks as $table => $column) {
            Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropForeign([$column]));
        }
    }

    private function addUserForeignKeys(): void
    {
        foreach ($this->userFks as $table => $column) {
            Schema::table($table, function (Blueprint $blueprint) use ($column) {
                $blueprint->foreign($column)->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    private function remapChildrenTo(string $buffer): void
    {
        foreach ($this->userFks as $table => $column) {
            $this->remapColumn($table, $column, $buffer);
        }

        $this->remapColumn('sessions', 'user_id', $buffer);

        DB::update(
            "update notifications set notifiable_id = (select users.{$buffer} from users where users.id = notifications.notifiable_id and notifications.notifiable_type = ?) where exists (select 1 from users where users.id = notifications.notifiable_id and notifications.notifiable_type = ?)",
            [(new User)->getMorphClass(), (new User)->getMorphClass()]
        );
    }

    private function remapColumn(string $table, string $column, string $buffer): void
    {
        DB::update(
            "update {$table} set {$column} = (select users.{$buffer} from users where users.id = {$table}.{$column}) where exists (select 1 from users where users.id = {$table}.{$column})"
        );
    }
};
