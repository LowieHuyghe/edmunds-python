<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */

namespace Core\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;

/**
 * Migration for translations-table
 *
 * @author      Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright   Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license     http://LicenseUrl
 * @since       Version 0.1
 */
trait _0005_CreateTranslationsTable
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up()
	{
		app('db')->connection()->getSchemaBuilder()->create('translations', function (Blueprint $table)
		{
			$table->primary('hash');
			$table->string('hash', 32);

			$table->text('original');
			$table->index('original');

			$table->text('aa')->nullable();
			$table->text('ab')->nullable();
			$table->text('ae')->nullable();
			$table->text('af')->nullable();
			$table->text('ak')->nullable();
			$table->text('am')->nullable();
			$table->text('an')->nullable();
			$table->text('ar')->nullable();
			$table->text('as')->nullable();
			$table->text('av')->nullable();
			$table->text('ay')->nullable();
			$table->text('az')->nullable();
			$table->text('ba')->nullable();
			$table->text('be')->nullable();
			$table->text('bg')->nullable();
			$table->text('bh')->nullable();
			$table->text('bi')->nullable();
			$table->text('bm')->nullable();
			$table->text('bn')->nullable();
			$table->text('bo')->nullable();
			$table->text('br')->nullable();
			$table->text('bs')->nullable();
			$table->text('ca')->nullable();
			$table->text('ce')->nullable();
			$table->text('ch')->nullable();
			$table->text('co')->nullable();
			$table->text('cr')->nullable();
			$table->text('cs')->nullable();
			$table->text('cu')->nullable();
			$table->text('cv')->nullable();
			$table->text('cy')->nullable();
			$table->text('da')->nullable();
			$table->text('de')->nullable();
			$table->text('dv')->nullable();
			$table->text('dz')->nullable();
			$table->text('ee')->nullable();
			$table->text('el')->nullable();
			$table->text('en')->nullable();
			$table->text('eo')->nullable();
			$table->text('es')->nullable();
			$table->text('et')->nullable();
			$table->text('eu')->nullable();
			$table->text('fa')->nullable();
			$table->text('ff')->nullable();
			$table->text('fi')->nullable();
			$table->text('fj')->nullable();
			$table->text('fo')->nullable();
			$table->text('fr')->nullable();
			$table->text('fy')->nullable();
			$table->text('ga')->nullable();
			$table->text('gd')->nullable();
			$table->text('gl')->nullable();
			$table->text('gn')->nullable();
			$table->text('gu')->nullable();
			$table->text('gv')->nullable();
			$table->text('ha')->nullable();
			$table->text('he')->nullable();
			$table->text('hi')->nullable();
			$table->text('ho')->nullable();
			$table->text('hr')->nullable();
			$table->text('ht')->nullable();
			$table->text('hu')->nullable();
			$table->text('hy')->nullable();
			$table->text('hz')->nullable();
			$table->text('ia')->nullable();
			$table->text('id')->nullable();
			$table->text('ie')->nullable();
			$table->text('ig')->nullable();
			$table->text('ii')->nullable();
			$table->text('ik')->nullable();
			$table->text('io')->nullable();
			$table->text('is')->nullable();
			$table->text('it')->nullable();
			$table->text('iu')->nullable();
			$table->text('ja')->nullable();
			$table->text('jv')->nullable();
			$table->text('ka')->nullable();
			$table->text('kg')->nullable();
			$table->text('ki')->nullable();
			$table->text('kj')->nullable();
			$table->text('kk')->nullable();
			$table->text('kl')->nullable();
			$table->text('km')->nullable();
			$table->text('kn')->nullable();
			$table->text('ko')->nullable();
			$table->text('kr')->nullable();
			$table->text('ks')->nullable();
			$table->text('ku')->nullable();
			$table->text('kv')->nullable();
			$table->text('kw')->nullable();
			$table->text('ky')->nullable();
			$table->text('la')->nullable();
			$table->text('lb')->nullable();
			$table->text('lg')->nullable();
			$table->text('li')->nullable();
			$table->text('ln')->nullable();
			$table->text('lo')->nullable();
			$table->text('lt')->nullable();
			$table->text('lu')->nullable();
			$table->text('lv')->nullable();
			$table->text('mg')->nullable();
			$table->text('mh')->nullable();
			$table->text('mi')->nullable();
			$table->text('mk')->nullable();
			$table->text('ml')->nullable();
			$table->text('mn')->nullable();
			$table->text('mr')->nullable();
			$table->text('ms')->nullable();
			$table->text('mt')->nullable();
			$table->text('my')->nullable();
			$table->text('na')->nullable();
			$table->text('nb')->nullable();
			$table->text('nd')->nullable();
			$table->text('ne')->nullable();
			$table->text('ng')->nullable();
			$table->text('nl')->nullable();
			$table->text('nn')->nullable();
			$table->text('no')->nullable();
			$table->text('nr')->nullable();
			$table->text('nv')->nullable();
			$table->text('ny')->nullable();
			$table->text('oc')->nullable();
			$table->text('oj')->nullable();
			$table->text('om')->nullable();
			$table->text('or')->nullable();
			$table->text('os')->nullable();
			$table->text('pa')->nullable();
			$table->text('pi')->nullable();
			$table->text('pl')->nullable();
			$table->text('ps')->nullable();
			$table->text('pt')->nullable();
			$table->text('qu')->nullable();
			$table->text('rm')->nullable();
			$table->text('rn')->nullable();
			$table->text('ro')->nullable();
			$table->text('ru')->nullable();
			$table->text('rw')->nullable();
			$table->text('sa')->nullable();
			$table->text('sc')->nullable();
			$table->text('sd')->nullable();
			$table->text('se')->nullable();
			$table->text('sg')->nullable();
			$table->text('si')->nullable();
			$table->text('sk')->nullable();
			$table->text('sl')->nullable();
			$table->text('sm')->nullable();
			$table->text('sn')->nullable();
			$table->text('so')->nullable();
			$table->text('sq')->nullable();
			$table->text('sr')->nullable();
			$table->text('ss')->nullable();
			$table->text('st')->nullable();
			$table->text('su')->nullable();
			$table->text('sv')->nullable();
			$table->text('sw')->nullable();
			$table->text('ta')->nullable();
			$table->text('te')->nullable();
			$table->text('tg')->nullable();
			$table->text('th')->nullable();
			$table->text('ti')->nullable();
			$table->text('tk')->nullable();
			$table->text('tl')->nullable();
			$table->text('tn')->nullable();
			$table->text('to')->nullable();
			$table->text('tr')->nullable();
			$table->text('ts')->nullable();
			$table->text('tt')->nullable();
			$table->text('tw')->nullable();
			$table->text('ty')->nullable();
			$table->text('ug')->nullable();
			$table->text('uk')->nullable();
			$table->text('ur')->nullable();
			$table->text('uz')->nullable();
			$table->text('ve')->nullable();
			$table->text('vi')->nullable();
			$table->text('vo')->nullable();
			$table->text('wa')->nullable();
			$table->text('wo')->nullable();
			$table->text('xh')->nullable();
			$table->text('yi')->nullable();
			$table->text('yo')->nullable();
			$table->text('za')->nullable();
			$table->text('zh')->nullable();
			$table->text('zu')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down()
	{
		app('db')->connection()->getSchemaBuilder()->drop('translations');
	}
}
