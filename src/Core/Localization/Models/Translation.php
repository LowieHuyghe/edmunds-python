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

namespace Core\Localization\Models;

use Core\Localization\Format\DateTime;
use Core\Bases\Models\BaseModel;
use Faker\Generator;
use Core\Validation\Validator;

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
 * @property DateTime $created_at
 * @property DateTime $updated_at
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
	protected function addValidationRules(&$validator)
	{
		parent::addValidationRules($validator);

		$this->required = array_merge($this->required, array('hash', 'original', 'used'));

		$validator->rule('hash')->max(32);
		$validator->rule('original')->max(21800);
		$validator->rule('used')->integer();

		$validator->rule('aa')->max(21800);
		$validator->rule('ab')->max(21800);
		$validator->rule('ae')->max(21800);
		$validator->rule('af')->max(21800);
		$validator->rule('ak')->max(21800);
		$validator->rule('am')->max(21800);
		$validator->rule('an')->max(21800);
		$validator->rule('ar')->max(21800);
		$validator->rule('as')->max(21800);
		$validator->rule('av')->max(21800);
		$validator->rule('ay')->max(21800);
		$validator->rule('az')->max(21800);
		$validator->rule('ba')->max(21800);
		$validator->rule('be')->max(21800);
		$validator->rule('bg')->max(21800);
		$validator->rule('bh')->max(21800);
		$validator->rule('bi')->max(21800);
		$validator->rule('bm')->max(21800);
		$validator->rule('bn')->max(21800);
		$validator->rule('bo')->max(21800);
		$validator->rule('br')->max(21800);
		$validator->rule('bs')->max(21800);
		$validator->rule('ca')->max(21800);
		$validator->rule('ce')->max(21800);
		$validator->rule('ch')->max(21800);
		$validator->rule('co')->max(21800);
		$validator->rule('cr')->max(21800);
		$validator->rule('cs')->max(21800);
		$validator->rule('cu')->max(21800);
		$validator->rule('cv')->max(21800);
		$validator->rule('cy')->max(21800);
		$validator->rule('da')->max(21800);
		$validator->rule('de')->max(21800);
		$validator->rule('dv')->max(21800);
		$validator->rule('dz')->max(21800);
		$validator->rule('ee')->max(21800);
		$validator->rule('el')->max(21800);
		$validator->rule('en')->max(21800);
		$validator->rule('eo')->max(21800);
		$validator->rule('es')->max(21800);
		$validator->rule('et')->max(21800);
		$validator->rule('eu')->max(21800);
		$validator->rule('fa')->max(21800);
		$validator->rule('ff')->max(21800);
		$validator->rule('fi')->max(21800);
		$validator->rule('fj')->max(21800);
		$validator->rule('fo')->max(21800);
		$validator->rule('fr')->max(21800);
		$validator->rule('fy')->max(21800);
		$validator->rule('ga')->max(21800);
		$validator->rule('gd')->max(21800);
		$validator->rule('gl')->max(21800);
		$validator->rule('gn')->max(21800);
		$validator->rule('gu')->max(21800);
		$validator->rule('gv')->max(21800);
		$validator->rule('ha')->max(21800);
		$validator->rule('he')->max(21800);
		$validator->rule('hi')->max(21800);
		$validator->rule('ho')->max(21800);
		$validator->rule('hr')->max(21800);
		$validator->rule('ht')->max(21800);
		$validator->rule('hu')->max(21800);
		$validator->rule('hy')->max(21800);
		$validator->rule('hz')->max(21800);
		$validator->rule('ia')->max(21800);
		$validator->rule('id')->max(21800);
		$validator->rule('ie')->max(21800);
		$validator->rule('ig')->max(21800);
		$validator->rule('ii')->max(21800);
		$validator->rule('ik')->max(21800);
		$validator->rule('io')->max(21800);
		$validator->rule('is')->max(21800);
		$validator->rule('it')->max(21800);
		$validator->rule('iu')->max(21800);
		$validator->rule('ja')->max(21800);
		$validator->rule('jv')->max(21800);
		$validator->rule('ka')->max(21800);
		$validator->rule('kg')->max(21800);
		$validator->rule('ki')->max(21800);
		$validator->rule('kj')->max(21800);
		$validator->rule('kk')->max(21800);
		$validator->rule('kl')->max(21800);
		$validator->rule('km')->max(21800);
		$validator->rule('kn')->max(21800);
		$validator->rule('ko')->max(21800);
		$validator->rule('kr')->max(21800);
		$validator->rule('ks')->max(21800);
		$validator->rule('ku')->max(21800);
		$validator->rule('kv')->max(21800);
		$validator->rule('kw')->max(21800);
		$validator->rule('ky')->max(21800);
		$validator->rule('la')->max(21800);
		$validator->rule('lb')->max(21800);
		$validator->rule('lg')->max(21800);
		$validator->rule('li')->max(21800);
		$validator->rule('ln')->max(21800);
		$validator->rule('lo')->max(21800);
		$validator->rule('lt')->max(21800);
		$validator->rule('lu')->max(21800);
		$validator->rule('lv')->max(21800);
		$validator->rule('mg')->max(21800);
		$validator->rule('mh')->max(21800);
		$validator->rule('mi')->max(21800);
		$validator->rule('mk')->max(21800);
		$validator->rule('ml')->max(21800);
		$validator->rule('mn')->max(21800);
		$validator->rule('mr')->max(21800);
		$validator->rule('ms')->max(21800);
		$validator->rule('mt')->max(21800);
		$validator->rule('my')->max(21800);
		$validator->rule('na')->max(21800);
		$validator->rule('nb')->max(21800);
		$validator->rule('nd')->max(21800);
		$validator->rule('ne')->max(21800);
		$validator->rule('ng')->max(21800);
		$validator->rule('nl')->max(21800);
		$validator->rule('nn')->max(21800);
		$validator->rule('no')->max(21800);
		$validator->rule('nr')->max(21800);
		$validator->rule('nv')->max(21800);
		$validator->rule('ny')->max(21800);
		$validator->rule('oc')->max(21800);
		$validator->rule('oj')->max(21800);
		$validator->rule('om')->max(21800);
		$validator->rule('or')->max(21800);
		$validator->rule('os')->max(21800);
		$validator->rule('pa')->max(21800);
		$validator->rule('pi')->max(21800);
		$validator->rule('pl')->max(21800);
		$validator->rule('ps')->max(21800);
		$validator->rule('pt')->max(21800);
		$validator->rule('qu')->max(21800);
		$validator->rule('rm')->max(21800);
		$validator->rule('rn')->max(21800);
		$validator->rule('ro')->max(21800);
		$validator->rule('ru')->max(21800);
		$validator->rule('rw')->max(21800);
		$validator->rule('sa')->max(21800);
		$validator->rule('sc')->max(21800);
		$validator->rule('sd')->max(21800);
		$validator->rule('se')->max(21800);
		$validator->rule('sg')->max(21800);
		$validator->rule('si')->max(21800);
		$validator->rule('sk')->max(21800);
		$validator->rule('sl')->max(21800);
		$validator->rule('sm')->max(21800);
		$validator->rule('sn')->max(21800);
		$validator->rule('so')->max(21800);
		$validator->rule('sq')->max(21800);
		$validator->rule('sr')->max(21800);
		$validator->rule('ss')->max(21800);
		$validator->rule('st')->max(21800);
		$validator->rule('su')->max(21800);
		$validator->rule('sv')->max(21800);
		$validator->rule('sw')->max(21800);
		$validator->rule('ta')->max(21800);
		$validator->rule('te')->max(21800);
		$validator->rule('tg')->max(21800);
		$validator->rule('th')->max(21800);
		$validator->rule('ti')->max(21800);
		$validator->rule('tk')->max(21800);
		$validator->rule('tl')->max(21800);
		$validator->rule('tn')->max(21800);
		$validator->rule('to')->max(21800);
		$validator->rule('tr')->max(21800);
		$validator->rule('ts')->max(21800);
		$validator->rule('tt')->max(21800);
		$validator->rule('tw')->max(21800);
		$validator->rule('ty')->max(21800);
		$validator->rule('ug')->max(21800);
		$validator->rule('uk')->max(21800);
		$validator->rule('ur')->max(21800);
		$validator->rule('uz')->max(21800);
		$validator->rule('ve')->max(21800);
		$validator->rule('vi')->max(21800);
		$validator->rule('vo')->max(21800);
		$validator->rule('wa')->max(21800);
		$validator->rule('wo')->max(21800);
		$validator->rule('xh')->max(21800);
		$validator->rule('yi')->max(21800);
		$validator->rule('yo')->max(21800);
		$validator->rule('za')->max(21800);
		$validator->rule('zh')->max(21800);
		$validator->rule('zu')->max(21800);
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
