<?php

namespace CupNoodles\LocationDomains\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Schema;

/**
 * 
 */
class AddLocationDomains extends Migration
{
    public function up()
    {

        if (!Schema::hasColumn('locations', 'use_alternate_domain')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->boolean('use_alternate_domain');
            });
        }
        
        if (!Schema::hasColumn('locations', 'alternate_domain')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->string('alternate_domain');
            });
        }

    }

    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['use_alternate_domain', 'alternate_domain']);
        });

    }

}
