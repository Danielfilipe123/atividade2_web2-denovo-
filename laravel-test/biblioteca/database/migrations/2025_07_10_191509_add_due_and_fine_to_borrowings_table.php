<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('borrowings', function (Blueprint $table) {

        $table->date('due_at')->nullable();
        $table->decimal('fine_amount',8,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('borrowings', function (Blueprint $table) {
            //
        });
    }
};
