<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Models;
use Carbon\Carbon;
use Core\Bases\Models\BaseModel;
use Faker\Generator;
use Core\Io\Validation\Validation;

/**
 * The model for translations
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property string hash
 * @property string original
 * @property int $used
 * @property string aa		Afar
 * @property string ab		Abkhaz
 * @property string ae		Avestan
 * @property string af		Afrikaans
 * @property string ak		Akan
 * @property string am		Amharic
 * @property string an		Aragonese
 * @property string ar		Arabic
 * @property string as		Assamese
 * @property string av		Avaric
 * @property string ay		Aymara
 * @property string az		Azerbaijani
 * @property string ba		Bashkir
 * @property string be		Belarusian
 * @property string bg		Bulgarian
 * @property string bh		Bihari
 * @property string bi		Bislama
 * @property string bm		Bambara
 * @property string bn		Bengali, Bangla
 * @property string bo		Tibetan Standard, Tibetan, Central
 * @property string br		Breton
 * @property string bs		Bosnian
 * @property string ca		Catalan
 * @property string ce		Chechen
 * @property string ch		Chamorro
 * @property string co		Corsican
 * @property string cr		Cree
 * @property string cs		Czech
 * @property string cu		Old Church Slavonic, Church Slavonic, Old Bulgarian
 * @property string cv		Chuvash
 * @property string cy		Welsh
 * @property string da		Danish
 * @property string de		German
 * @property string dv		Divehi, Dhivehi, Maldivian
 * @property string dz		Dzongkha
 * @property string ee		Ewe
 * @property string el		Greek (modern)
 * @property string en		English
 * @property string eo		Esperanto
 * @property string es		Spanish
 * @property string et		Estonian
 * @property string eu		Basque
 * @property string fa		Persian (Farsi)
 * @property string ff		Fula, Fulah, Pulaar, Pular
 * @property string fi		Finnish
 * @property string fj		Fijian
 * @property string fo		Faroese
 * @property string fr		French
 * @property string fy		Western Frisian
 * @property string ga		Irish
 * @property string gd		Scottish Gaelic, Gaelic
 * @property string gl		Galician
 * @property string gn		Guaran�
 * @property string gu		Gujarati
 * @property string gv		Manx
 * @property string ha		Hausa
 * @property string he		Hebrew (modern)
 * @property string hi		Hindi
 * @property string ho		Hiri Motu
 * @property string hr		Croatian
 * @property string ht		Haitian, Haitian Creole
 * @property string hu		Hungarian
 * @property string hy		Armenian
 * @property string hz		Herero
 * @property string ia		Interlingua
 * @property string id		Indonesian
 * @property string ie		Interlingue
 * @property string ig		Igbo
 * @property string ii		Nuosu
 * @property string ik		Inupiaq
 * @property string io		Ido
 * @property string is		Icelandic
 * @property string it		Italian
 * @property string iu		Inuktitut
 * @property string ja		Japanese
 * @property string jv		Javanese
 * @property string ka		Georgian
 * @property string kg		Kongo
 * @property string ki		Kikuyu, Gikuyu
 * @property string kj		Kwanyama, Kuanyama
 * @property string kk		Kazakh
 * @property string kl		Kalaallisut, Greenlandic
 * @property string km		Khmer
 * @property string kn		Kannada
 * @property string ko		Korean
 * @property string kr		Kanuri
 * @property string ks		Kashmiri
 * @property string ku		Kurdish
 * @property string kv		Komi
 * @property string kw		Cornish
 * @property string ky		Kyrgyz
 * @property string la		Latin
 * @property string lb		Luxembourgish, Letzeburgesch
 * @property string lg		Ganda
 * @property string li		Limburgish, Limburgan, Limburger
 * @property string ln		Lingala
 * @property string lo		Lao
 * @property string lt		Lithuanian
 * @property string lu		Luba-Katanga
 * @property string lv		Latvian
 * @property string mg		Malagasy
 * @property string mh		Marshallese
 * @property string mi		M?ori
 * @property string mk		Macedonian
 * @property string ml		Malayalam
 * @property string mn		Mongolian
 * @property string mr		Marathi (Mar??h?)
 * @property string ms		Malay
 * @property string mt		Maltese
 * @property string my		Burmese
 * @property string na		Nauru
 * @property string nb		Norwegian Bokm�l
 * @property string nd		Northern Ndebele
 * @property string ne		Nepali
 * @property string ng		Ndonga
 * @property string nl		Dutch
 * @property string nn		Norwegian Nynorsk
 * @property string no		Norwegian
 * @property string nr		Southern Ndebele
 * @property string nv		Navajo, Navaho
 * @property string ny		Chichewa, Chewa, Nyanja
 * @property string oc		Occitan
 * @property string oj		Ojibwe, Ojibwa
 * @property string om		Oromo
 * @property string or		Oriya
 * @property string os		Ossetian, Ossetic
 * @property string pa		Panjabi, Punjabi
 * @property string pi		P?li
 * @property string pl		Polish
 * @property string ps		Pashto, Pushto
 * @property string pt		Portuguese
 * @property string qu		Quechua
 * @property string rm		Romansh
 * @property string rn		Kirundi
 * @property string ro		Romanian
 * @property string ru		Russian
 * @property string rw		Kinyarwanda
 * @property string sa		Sanskrit (Sa?sk?ta)
 * @property string sc		Sardinian
 * @property string sd		Sindhi
 * @property string se		Northern Sami
 * @property string sg		Sango
 * @property string si		Sinhala, Sinhalese
 * @property string sk		Slovak
 * @property string sl		Slovene
 * @property string sm		Samoan
 * @property string sn		Shona
 * @property string so		Somali
 * @property string sq		Albanian
 * @property string sr		Serbian
 * @property string ss		Swati
 * @property string st		Southern Sotho
 * @property string su		Sundanese
 * @property string sv		Swedish
 * @property string sw		Swahili
 * @property string ta		Tamil
 * @property string te		Telugu
 * @property string tg		Tajik
 * @property string th		Thai
 * @property string ti		Tigrinya
 * @property string tk		Turkmen
 * @property string tl		Tagalog
 * @property string tn		Tswana
 * @property string to		Tonga (Tonga Islands)
 * @property string tr		Turkish
 * @property string ts		Tsonga
 * @property string tt		Tatar
 * @property string tw		Twi
 * @property string ty		Tahitian
 * @property string ug		Uyghur
 * @property string uk		Ukrainian
 * @property string ur		Urdu
 * @property string uz		Uzbek
 * @property string ve		Venda
 * @property string vi		Vietnamese
 * @property string vo		Volap�k
 * @property string wa		Walloon
 * @property string wo		Wolof
 * @property string xh		Xhosa
 * @property string yi		Yiddish
 * @property string yo		Yoruba
 * @property string za		Zhuang, Chuang
 * @property string zh		Chinese
 * @property string zu		Zulu
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Translation extends BaseModel
{

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'hash';

	/**
	 * Add the validation of the model
	 */
	protected function addValidationRules()
	{
		parent::addValidationRules();

		$this->validator->value('hash')->max(32)->unique('translations', $this->getKeyName())->required();
		$this->validator->value('original')->max(21800)->required();
		$this->validator->value('used')->integer()->required();

		$this->validator->value('aa')->max(21800);
		$this->validator->value('ab')->max(21800);
		$this->validator->value('ae')->max(21800);
		$this->validator->value('af')->max(21800);
		$this->validator->value('ak')->max(21800);
		$this->validator->value('am')->max(21800);
		$this->validator->value('an')->max(21800);
		$this->validator->value('ar')->max(21800);
		$this->validator->value('as')->max(21800);
		$this->validator->value('av')->max(21800);
		$this->validator->value('ay')->max(21800);
		$this->validator->value('az')->max(21800);
		$this->validator->value('ba')->max(21800);
		$this->validator->value('be')->max(21800);
		$this->validator->value('bg')->max(21800);
		$this->validator->value('bh')->max(21800);
		$this->validator->value('bi')->max(21800);
		$this->validator->value('bm')->max(21800);
		$this->validator->value('bn')->max(21800);
		$this->validator->value('bo')->max(21800);
		$this->validator->value('br')->max(21800);
		$this->validator->value('bs')->max(21800);
		$this->validator->value('ca')->max(21800);
		$this->validator->value('ce')->max(21800);
		$this->validator->value('ch')->max(21800);
		$this->validator->value('co')->max(21800);
		$this->validator->value('cr')->max(21800);
		$this->validator->value('cs')->max(21800);
		$this->validator->value('cu')->max(21800);
		$this->validator->value('cv')->max(21800);
		$this->validator->value('cy')->max(21800);
		$this->validator->value('da')->max(21800);
		$this->validator->value('de')->max(21800);
		$this->validator->value('dv')->max(21800);
		$this->validator->value('dz')->max(21800);
		$this->validator->value('ee')->max(21800);
		$this->validator->value('el')->max(21800);
		$this->validator->value('en')->max(21800);
		$this->validator->value('eo')->max(21800);
		$this->validator->value('es')->max(21800);
		$this->validator->value('et')->max(21800);
		$this->validator->value('eu')->max(21800);
		$this->validator->value('fa')->max(21800);
		$this->validator->value('ff')->max(21800);
		$this->validator->value('fi')->max(21800);
		$this->validator->value('fj')->max(21800);
		$this->validator->value('fo')->max(21800);
		$this->validator->value('fr')->max(21800);
		$this->validator->value('fy')->max(21800);
		$this->validator->value('ga')->max(21800);
		$this->validator->value('gd')->max(21800);
		$this->validator->value('gl')->max(21800);
		$this->validator->value('gn')->max(21800);
		$this->validator->value('gu')->max(21800);
		$this->validator->value('gv')->max(21800);
		$this->validator->value('ha')->max(21800);
		$this->validator->value('he')->max(21800);
		$this->validator->value('hi')->max(21800);
		$this->validator->value('ho')->max(21800);
		$this->validator->value('hr')->max(21800);
		$this->validator->value('ht')->max(21800);
		$this->validator->value('hu')->max(21800);
		$this->validator->value('hy')->max(21800);
		$this->validator->value('hz')->max(21800);
		$this->validator->value('ia')->max(21800);
		$this->validator->value('id')->max(21800);
		$this->validator->value('ie')->max(21800);
		$this->validator->value('ig')->max(21800);
		$this->validator->value('ii')->max(21800);
		$this->validator->value('ik')->max(21800);
		$this->validator->value('io')->max(21800);
		$this->validator->value('is')->max(21800);
		$this->validator->value('it')->max(21800);
		$this->validator->value('iu')->max(21800);
		$this->validator->value('ja')->max(21800);
		$this->validator->value('jv')->max(21800);
		$this->validator->value('ka')->max(21800);
		$this->validator->value('kg')->max(21800);
		$this->validator->value('ki')->max(21800);
		$this->validator->value('kj')->max(21800);
		$this->validator->value('kk')->max(21800);
		$this->validator->value('kl')->max(21800);
		$this->validator->value('km')->max(21800);
		$this->validator->value('kn')->max(21800);
		$this->validator->value('ko')->max(21800);
		$this->validator->value('kr')->max(21800);
		$this->validator->value('ks')->max(21800);
		$this->validator->value('ku')->max(21800);
		$this->validator->value('kv')->max(21800);
		$this->validator->value('kw')->max(21800);
		$this->validator->value('ky')->max(21800);
		$this->validator->value('la')->max(21800);
		$this->validator->value('lb')->max(21800);
		$this->validator->value('lg')->max(21800);
		$this->validator->value('li')->max(21800);
		$this->validator->value('ln')->max(21800);
		$this->validator->value('lo')->max(21800);
		$this->validator->value('lt')->max(21800);
		$this->validator->value('lu')->max(21800);
		$this->validator->value('lv')->max(21800);
		$this->validator->value('mg')->max(21800);
		$this->validator->value('mh')->max(21800);
		$this->validator->value('mi')->max(21800);
		$this->validator->value('mk')->max(21800);
		$this->validator->value('ml')->max(21800);
		$this->validator->value('mn')->max(21800);
		$this->validator->value('mr')->max(21800);
		$this->validator->value('ms')->max(21800);
		$this->validator->value('mt')->max(21800);
		$this->validator->value('my')->max(21800);
		$this->validator->value('na')->max(21800);
		$this->validator->value('nb')->max(21800);
		$this->validator->value('nd')->max(21800);
		$this->validator->value('ne')->max(21800);
		$this->validator->value('ng')->max(21800);
		$this->validator->value('nl')->max(21800);
		$this->validator->value('nn')->max(21800);
		$this->validator->value('no')->max(21800);
		$this->validator->value('nr')->max(21800);
		$this->validator->value('nv')->max(21800);
		$this->validator->value('ny')->max(21800);
		$this->validator->value('oc')->max(21800);
		$this->validator->value('oj')->max(21800);
		$this->validator->value('om')->max(21800);
		$this->validator->value('or')->max(21800);
		$this->validator->value('os')->max(21800);
		$this->validator->value('pa')->max(21800);
		$this->validator->value('pi')->max(21800);
		$this->validator->value('pl')->max(21800);
		$this->validator->value('ps')->max(21800);
		$this->validator->value('pt')->max(21800);
		$this->validator->value('qu')->max(21800);
		$this->validator->value('rm')->max(21800);
		$this->validator->value('rn')->max(21800);
		$this->validator->value('ro')->max(21800);
		$this->validator->value('ru')->max(21800);
		$this->validator->value('rw')->max(21800);
		$this->validator->value('sa')->max(21800);
		$this->validator->value('sc')->max(21800);
		$this->validator->value('sd')->max(21800);
		$this->validator->value('se')->max(21800);
		$this->validator->value('sg')->max(21800);
		$this->validator->value('si')->max(21800);
		$this->validator->value('sk')->max(21800);
		$this->validator->value('sl')->max(21800);
		$this->validator->value('sm')->max(21800);
		$this->validator->value('sn')->max(21800);
		$this->validator->value('so')->max(21800);
		$this->validator->value('sq')->max(21800);
		$this->validator->value('sr')->max(21800);
		$this->validator->value('ss')->max(21800);
		$this->validator->value('st')->max(21800);
		$this->validator->value('su')->max(21800);
		$this->validator->value('sv')->max(21800);
		$this->validator->value('sw')->max(21800);
		$this->validator->value('ta')->max(21800);
		$this->validator->value('te')->max(21800);
		$this->validator->value('tg')->max(21800);
		$this->validator->value('th')->max(21800);
		$this->validator->value('ti')->max(21800);
		$this->validator->value('tk')->max(21800);
		$this->validator->value('tl')->max(21800);
		$this->validator->value('tn')->max(21800);
		$this->validator->value('to')->max(21800);
		$this->validator->value('tr')->max(21800);
		$this->validator->value('ts')->max(21800);
		$this->validator->value('tt')->max(21800);
		$this->validator->value('tw')->max(21800);
		$this->validator->value('ty')->max(21800);
		$this->validator->value('ug')->max(21800);
		$this->validator->value('uk')->max(21800);
		$this->validator->value('ur')->max(21800);
		$this->validator->value('uz')->max(21800);
		$this->validator->value('ve')->max(21800);
		$this->validator->value('vi')->max(21800);
		$this->validator->value('vo')->max(21800);
		$this->validator->value('wa')->max(21800);
		$this->validator->value('wo')->max(21800);
		$this->validator->value('xh')->max(21800);
		$this->validator->value('yi')->max(21800);
		$this->validator->value('yo')->max(21800);
		$this->validator->value('za')->max(21800);
		$this->validator->value('zh')->max(21800);
		$this->validator->value('zu')->max(21800);
	}

	/**
	 * Define-function for the instance generator
	 * @param Generator $faker
	 * @return array
	 */
	protected static function factory($faker)
	{
		return array(
			'hash' => str_random(60),
			'original' => $faker->realText(100, 5),
			'used' => rand(1, 3),
			'en' => $faker->realText(100, 5),
			'nl' => $faker->realText(100, 5),
		);
	}

}
