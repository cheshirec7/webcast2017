<?php

use Carbon\Carbon;
use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Database\DisableForeignKeys;

/**
 * Class PermissionTableSeeder.
 */
class PermissionTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple([config('access.permissions_table'), config('access.permission_role_table')]);

        /**
         * Don't need to assign any permissions to administrator because the all flag is set to true
         * in RoleTableSeeder.php.
         */

        /**
         * Misc Access Permissions.
         */
        $permission_model = config('access.permission');
        $checkpointTimer = new $permission_model();
        $checkpointTimer->name = 'checkpoint-timer';
        $checkpointTimer->display_name = 'Checkpoint Timer';
        $checkpointTimer->created_at = Carbon::now();
        $checkpointTimer->updated_at = Carbon::now();
        $checkpointTimer->save();

        $this->enableForeignKeys();
    }
}
