<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->string('password');
        });

        Schema::create('units', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('id_owner');
        });

        Schema::create('unitpeoples', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->date('birthdate');
        });

        Schema::create('unitvehicles', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('color');
            $table->string('plate');
        });

        Schema::create('unitpets', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('name');
            $table->string('race');
        });

        Schema::create('walls', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->datetime('datecreated');
        });

        Schema::create('walllikes', function(Blueprint $table) {
            $table->id();
            $table->integer('id_wall');
            $table->integer('id_user');
        });

        Schema::create('docs', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('fileurl');
        });

        Schema::create('billets', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('fileurl');
        });

        Schema::create('warnings', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('title');
            $table->string('status')->default('IN_REVIEW'); // IN_REVIEW, RESOLVED
            $table->date('datecreated');
            $table->text('photos');
        });

        Schema::create('foundandlost', function(Blueprint $table) {
            $table->id();
            $table->string('status')->default('LOST');  // LOST, RECOVERED
            $table->string('photo');
            $table->string('description');
            $table->string('where');
            $table->date('datecreated');
        });

        Schema::create('areas', function(Blueprint $table) {
            $table->id();
            $table->integer('allowed')->default(1);
            $table->string('title');
            $table->string('cover');
            $table->string('days'); 
            $table->time('start_time');
            $table->time('end_time');
        });

        Schema::create('areadisableddays', function(Blueprint $table) {
            $table->id();
            $table->integer('id_area');
            $table->date('day');
        });

        Schema::create('reservations', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->integer('id_area');
            $table->datetime('reservation_date');
        });
        Schema::table('openingserviceorders', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->string('status');             
            $table->string('problemdescription',);
            $table->string('photos');
            $table->date('datecreated');
            $table->string('problemtype');
            $table->string('sector');
        });

        Schema::table('packages', function(Blueprint $table) {
            $table->id();
            $table->string('packagecode',);
            $table->date('datecreated');
            $table->date('colletctdate');
            $table->string('status');
            $table->integer('id_unit');
            $table->string('destinationname');
            $table->string('origin'); 
            $table->string('type');
            $table->string('collectname');
            $table->string('collectrg');
            $table->string('photos');
            $table->string('user');
        });
        
        Schema::table('rental', function(Blueprint $table) {
            $table->id();
            $table->integer('id_unit');
            $table->date('dateevent');
            $table->integer('id_rentalarea');
            $table->string('status');
            $table->date('datecreated');
            $table->string('user');
        });

        Schema::table('rentalarea', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('textterms');
            $table->integer('capacity');
            $table->integer('price');
            $table->string('photos');
        });

        schema::table('rentalpayment', function(Blueprint $table) {
            $table->id();
            $table->integer('id_rental');
            $table->string('paymenttype');
            $table->date('paymentdate');
            $table->integer('paymentvalue');
            $table->integer('paymentdiscount');
            $table->string('paymentstatus');
            $table->string('user');
        });
        Schema::table('rentaldisableday', function(Blueprint $table) {
            $table->id();
            $table->integer('id_rentalarea');
            $table->date('initialdate');
            $table->date('finaldate');
            $table->string('description');
        });

        Schema::table('rentalhistoric', function(Blueprint $table) {
            $table->id();
            $table->integer('id_rental');
            $table->date('dateevent');
            $table->integer('id_rentalarea');
            $table->string('user');
            $table->string('action');
            $table->date('datecreated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unitpeoples');
        Schema::dropIfExists('unitvehicles');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('foundandlost');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areadisableddays');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('openingserviceorders');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('rental');
        Schema::dropIfExists('rentalarea');
        Schema::dropIfExists('rentalpayment');
        Schema::dropIfExists('rentaldisableday');
        Schema::dropIfExists('rentalhistoric');
    }
};
