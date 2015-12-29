<?php

return array
(

	'config' => array(
		'required' => array(
			'app.name',
			'app.key',
			'app.locale',
			'app.fallback',
			'routing.namespace',
			'routing.defaultcontroller',
			'routing.namespace',
			'routing.loginroute',
			'analytics.google.version',
			'analytics.google.trackingid',
		),
	),

	'admin' => array(
		'pm' => array(
			'driver' => 'slack',

			'slack' => array(
				'hook' => 'https://hooks.slack.com/services/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',
				'icon' => ':sheep:',
				'channel' => array(
					'info' => '#dev-info',
					'warning' => '#dev-warning',
					'error' => '#dev-error',
				),
			),
		),
	),

	'auth' => array(
		'ttl' => array(
			'passwordreset' => 15,
			'authtoken' => 24,
		),
	),

	'localization' => array(
		'languages' => array(
			'rtl' => array(
				'ar',		// Arabic
				// 'arc',	// Aramaic
				// 'bcc',	// Southern Balochi
				// 'bqi',	// Bakthiari
				// 'ckb',	// Sorani Kurdish
				'dv',		// Dhivehi
				'fa',		// Persian
				// 'glk',	// Gilaki
				'he',		// Hebrew
				// 'lrc',	// Northern Luri
				// 'mzn',	// Mazanderani
				// 'pnb',	// Western Punjabi
				'ps',		// Pashto
				'sd',		// Sindhi
				'ug',		// Uyghur
				'ur',		// Urdu
				'yi',		// Yiddish
			),
		),
		'currencies' => array(
			'countries' => array(
				'ABW' => 'AWG',	// Aruba
				'AFG' => 'AFN',	// Afghanistan
				'AGO' => 'AOA',	// Angola
				'AIA' => 'XCD',	// Anguilla
				'ALA' => 'EUR',	// Åland Islands
				'ALB' => 'ALL',	// Albania
				'AND' => 'EUR',	// Andorra
				'ARE' => 'AED',	// United Arab Emirates
				'ARG' => 'ARS',	// Argentina
				'ARM' => 'AMD',	// Armenia
				'ASM' => 'USD',	// American Samoa
				// 'ATA' => '',	// Antarctica
				'ATF' => 'EUR',	// French Southern Territories
				'ATG' => 'XCD',	// Antigua and Barbuda
				'AUS' => 'AUD',	// Australia
				'AUT' => 'EUR',	// Austria
				'AZE' => 'AZN',	// Azerbaijan
				'BDI' => 'BIF',	// Burundi
				'BEL' => 'EUR',	// Belgium
				'BEN' => 'XOF',	// Benin
				'BES' => 'USD',	// Bonaire, Sint Eustatius and Saba
				'BFA' => 'XOF',	// Burkina Faso
				'BGD' => 'BDT',	// Bangladesh
				'BGR' => 'BGN',	// Bulgaria
				'BHR' => 'BHD',	// Bahrain
				'BHS' => 'BSD',	// Bahamas
				'BIH' => 'BAM',	// Bosnia and Herzegovina
				'BLM' => 'EUR',	// Saint Barthélemy
				'BLR' => 'BYR',	// Belarus
				'BLZ' => 'BZD',	// Belize
				'BMU' => 'BMD',	// Bermuda
				'BOL' => 'BOB',	// Bolivia, Plurinational State of
				'BRA' => 'BRL',	// Brazil
				'BRB' => 'BBD',	// Barbados
				'BRN' => 'BND',	// Brunei Darussalam
				'BTN' => 'INR',	// Bhutan
				'BVT' => 'NOK',	// Bouvet Island
				'BWA' => 'BWP',	// Botswana
				'CAF' => 'XAF',	// Central African Republic
				'CAN' => 'CAD',	// Canada
				'CCK' => 'AUD',	// Cocos (Keeling) Islands
				'CHE' => 'CHF',	// Switzerland
				'CHL' => 'CLP',	// Chile
				'CHN' => 'CNY',	// China
				'CIV' => 'XOF',	// Côte d'Ivoire
				'CMR' => 'XAF',	// Cameroon
				// 'COD' => '',	// Congo, the Democratic Republic of the
				'COG' => 'XAF',	// Congo
				'COK' => 'NZD',	// Cook Islands
				'COL' => 'COP',	// Colombia
				'COM' => 'KMF',	// Comoros
				'CPV' => 'CVE',	// Cape Verde
				'CRI' => 'CRC',	// Costa Rica
				'CUB' => 'CUP',	// Cuba
				'CUW' => 'ANG',	// Curaçao
				'CXR' => 'AUD',	// Christmas Island
				'CYM' => 'KYD',	// Cayman Islands
				'CYP' => 'EUR',	// Cyprus
				'CZE' => 'CZK',	// Czech Republic
				'DEU' => 'EUR',	// Germany
				'DJI' => 'DJF',	// Djibouti
				'DMA' => 'XCD',	// Dominica
				'DNK' => 'DKK',	// Denmark
				'DOM' => 'DOP',	// Dominican Republic
				'DZA' => 'DZD',	// Algeria
				'ECU' => 'USD',	// Ecuador
				'EGY' => 'EGP',	// Egypt
				'ERI' => 'ERN',	// Eritrea
				'ESH' => 'MAD',	// Western Sahara
				'ESP' => 'EUR',	// Spain
				'EST' => 'EUR',	// Estonia
				'ETH' => 'ETB',	// Ethiopia
				'FIN' => 'EUR',	// Finland
				'FJI' => 'FJD',	// Fiji
				'FLK' => 'FKP',	// Falkland Islands (Malvinas)
				'FRA' => 'EUR',	// France
				'FRO' => 'DKK',	// Faroe Islands
				'FSM' => 'USD',	// Micronesia, Federated States of
				'GAB' => 'XAF',	// Gabon
				'GBR' => 'GBP',	// United Kingdom
				'GEO' => 'GEL',	// Georgia
				'GGY' => 'GBP',	// Guernsey
				'GHA' => 'GHS',	// Ghana
				'GIB' => 'GIP',	// Gibraltar
				'GIN' => 'GNF',	// Guinea
				'GLP' => 'EUR',	// Guadeloupe
				'GMB' => 'GMD',	// Gambia
				'GNB' => 'XOF',	// Guinea-Bissau
				'GNQ' => 'XAF',	// Equatorial Guinea
				'GRC' => 'EUR',	// Greece
				'GRD' => 'XCD',	// Grenada
				'GRL' => 'DKK',	// Greenland
				'GTM' => 'GTQ',	// Guatemala
				'GUF' => 'EUR',	// French Guiana
				'GUM' => 'USD',	// Guam
				'GUY' => 'GYD',	// Guyana
				'HKG' => 'HKD',	// Hong Kong
				'HMD' => 'AUD',	// Heard Island and McDonald Islands
				'HND' => 'HNL',	// Honduras
				'HRV' => 'HRK',	// Croatia
				'HTI' => 'USD',	// Haiti
				'HUN' => 'HUF',	// Hungary
				'IDN' => 'IDR',	// Indonesia
				'IMN' => 'GBP',	// Isle of Man
				'IND' => 'INR',	// India
				'IOT' => 'USD',	// British Indian Ocean Territory
				'IRL' => 'EUR',	// Ireland
				'IRN' => 'IRR',	// Iran, Islamic Republic of
				'IRQ' => 'IQD',	// Iraq
				'ISL' => 'ISK',	// Iceland
				'ISR' => 'ILS',	// Israel
				'ITA' => 'EUR',	// Italy
				'JAM' => 'JMD',	// Jamaica
				'JEY' => 'GBP',	// Jersey
				'JOR' => 'JOD',	// Jordan
				'JPN' => 'JPY',	// Japan
				'KAZ' => 'KZT',	// Kazakhstan
				'KEN' => 'KES',	// Kenya
				'KGZ' => 'KGS',	// Kyrgyzstan
				'KHM' => 'KHR',	// Cambodia
				'KIR' => 'AUD',	// Kiribati
				'KNA' => 'XCD',	// Saint Kitts and Nevis
				'KOR' => 'KRW',	// Korea, Republic of
				'KWT' => 'KWD',	// Kuwait
				'LAO' => 'LAK',	// Lao People's Democratic Republic
				'LBN' => 'LBP',	// Lebanon
				'LBR' => 'LRD',	// Liberia
				'LBY' => 'LYD',	// Libya
				'LCA' => 'XCD',	// Saint Lucia
				'LIE' => 'CHF',	// Liechtenstein
				'LKA' => 'LKR',	// Sri Lanka
				'LSO' => 'ZAR',	// Lesotho
				'LTU' => 'EUR',	// Lithuania
				'LUX' => 'EUR',	// Luxembourg
				'LVA' => 'EUR',	// Latvia
				'MAC' => 'MOP',	// Macao
				'MAF' => 'EUR',	// Saint Martin (French part)
				'MAR' => 'MAD',	// Morocco
				'MCO' => 'EUR',	// Monaco
				'MDA' => 'MDL',	// Moldova, Republic of
				'MDG' => 'MGA',	// Madagascar
				'MDV' => 'MVR',	// Maldives
				'MEX' => 'MXN',	// Mexico
				'MHL' => 'USD',	// Marshall Islands
				'MKD' => 'MKD',	// Macedonia, the Former Yugoslav Republic of
				'MLI' => 'XOF',	// Mali
				'MLT' => 'EUR',	// Malta
				'MMR' => 'MMK',	// Myanmar
				'MNE' => 'EUR',	// Montenegro
				'MNG' => 'MNT',	// Mongolia
				'MNP' => 'USD',	// Northern Mariana Islands
				'MOZ' => 'MZN',	// Mozambique
				'MRT' => 'MRO',	// Mauritania
				'MSR' => 'XCD',	// Montserrat
				'MTQ' => 'EUR',	// Martinique
				'MUS' => 'MUR',	// Mauritius
				'MWI' => 'MWK',	// Malawi
				'MYS' => 'MYR',	// Malaysia
				'MYT' => 'EUR',	// Mayotte
				'NAM' => 'ZAR',	// Namibia
				'NCL' => 'XPF',	// New Caledonia
				'NER' => 'XOF',	// Niger
				'NFK' => 'AUD',	// Norfolk Island
				'NGA' => 'NGN',	// Nigeria
				'NIC' => 'NIO',	// Nicaragua
				'NIU' => 'NZD',	// Niue
				'NLD' => 'EUR',	// Netherlands
				'NOR' => 'NOK',	// Norway
				'NPL' => 'NPR',	// Nepal
				'NRU' => 'AUD',	// Nauru
				'NZL' => 'NZD',	// New Zealand
				'OMN' => 'OMR',	// Oman
				'PAK' => 'PKR',	// Pakistan
				'PAN' => 'USD',	// Panama
				'PCN' => 'NZD',	// Pitcairn
				'PER' => 'PEN',	// Peru
				'PHL' => 'PHP',	// Philippines
				'PLW' => 'USD',	// Palau
				'PNG' => 'PGK',	// Papua New Guinea
				'POL' => 'PLN',	// Poland
				'PRI' => 'USD',	// Puerto Rico
				'PRK' => 'KPW',	// Korea, Democratic People's Republic of
				'PRT' => 'EUR',	// Portugal
				'PRY' => 'PYG',	// Paraguay
				// 'PSE' => '',	// Palestine, State of
				'PYF' => 'XPF',	// French Polynesia
				'QAT' => 'QAR',	// Qatar
				'REU' => 'EUR',	// Réunion
				'ROU' => 'RON',	// Romania
				'RUS' => 'RUB',	// Russian Federation
				'RWA' => 'RWF',	// Rwanda
				'SAU' => 'SAR',	// Saudi Arabia
				'SDN' => 'SDG',	// Sudan
				'SEN' => 'XOF',	// Senegal
				'SGP' => 'SGD',	// Singapore
				// 'SGS' => '',	// South Georgia and the South Sandwich Islands
				'SHN' => 'SHP',	// Saint Helena, Ascension and Tristan da Cunha
				'SJM' => 'NOK',	// Svalbard and Jan Mayen
				'SLB' => 'SBD',	// Solomon Islands
				'SLE' => 'SLL',	// Sierra Leone
				'SLV' => 'USD',	// El Salvador
				'SMR' => 'EUR',	// San Marino
				'SOM' => 'SOS',	// Somalia
				'SPM' => 'EUR',	// Saint Pierre and Miquelon
				'SRB' => 'RSD',	// Serbia
				'SSD' => 'SSP',	// South Sudan
				'STP' => 'STD',	// Sao Tome and Principe
				'SUR' => 'SRD',	// Suriname
				'SVK' => 'EUR',	// Slovakia
				'SVN' => 'EUR',	// Slovenia
				'SWE' => 'SEK',	// Sweden
				'SWZ' => 'SZL',	// Swaziland
				'SXM' => 'ANG',	// Sint Maarten (Dutch part)
				'SYC' => 'SCR',	// Seychelles
				'SYR' => 'SYP',	// Syrian Arab Republic
				'TCA' => 'USD',	// Turks and Caicos Islands
				'TCD' => 'XAF',	// Chad
				'TGO' => 'XOF',	// Togo
				'THA' => 'THB',	// Thailand
				'TJK' => 'TJS',	// Tajikistan
				'TKL' => 'NZD',	// Tokelau
				'TKM' => 'TMT',	// Turkmenistan
				'TLS' => 'USD',	// Timor-Leste
				'TON' => 'TOP',	// Tonga
				'TTO' => 'TTD',	// Trinidad and Tobago
				'TUN' => 'TND',	// Tunisia
				'TUR' => 'TRY',	// Turkey
				'TUV' => 'AUD',	// Tuvalu
				'TWN' => 'TWD',	// Taiwan, Province of China
				'TZA' => 'TZS',	// Tanzania, United Republic of
				'UGA' => 'UGX',	// Uganda
				'UKR' => 'UAH',	// Ukraine
				'UMI' => 'USD',	// United States Minor Outlying Islands
				'URY' => 'UYU',	// Uruguay
				'USA' => 'USD',	// United States
				'UZB' => 'UZS',	// Uzbekistan
				'VAT' => 'EUR',	// Holy See (Vatican City State)
				'VCT' => 'XCD',	// Saint Vincent and the Grenadines
				'VEN' => 'VEF',	// Venezuela, Bolivarian Republic of
				'VGB' => 'USD',	// Virgin Islands, British
				'VIR' => 'USD',	// Virgin Islands, U.S.
				'VNM' => 'VND',	// Viet Nam
				'VUT' => 'VUV',	// Vanuatu
				'WLF' => 'XPF',	// Wallis and Futuna
				'WSM' => 'WST',	// Samoa
				'YEM' => 'YER',	// Yemen
				'ZAF' => 'ZAR',	// South Africa
				'ZMB' => 'ZMW',	// Zambia
				'ZWE' => 'ZWL',	// Zimbabwe
			),
			'properties' => array(
				'AED' => array(
					'name' => 'United Arab Emirates dirham',
					'symbol' => 'د.إ',
					'unit' => 'Fils',
					'decimals' => 2,
				),
				'AFN' => array(
					'name' => 'Afghan afghani',
					'symbol' => '؋',
					'unit' => 'Pul',
					'decimals' => 2,
				),
				'ALL' => array(
					'name' => 'Albanian lek',
					'symbol' => 'L',
					'unit' => 'Qindarkë',
					'decimals' => 2,
				),
				'AMD' => array(
					'name' => 'Armenian dram',
					// 'symbol' => 'Armenian dram sign.svg',
					'unit' => 'Luma',
					'decimals' => 2,
				),
				'ANG' => array(
					'name' => 'Netherlands Antillean guilder',
					'symbol' => 'ƒ',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'AOA' => array(
					'name' => 'Angolan kwanza',
					'symbol' => 'Kz',
					'unit' => 'Cêntimo',
					'decimals' => 2,
				),
				'ARS' => array(
					'name' => 'Argentine peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'AUD' => array(
					'name' => 'Australian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'AWG' => array(
					'name' => 'Aruban florin',
					'symbol' => 'ƒ',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'AZN' => array(
					'name' => 'Azerbaijani manat',
					// 'symbol' => 'Azeri manat symbol.svg',
					'unit' => 'Qəpik',
					'decimals' => 2,
				),
				'BAM' => array(
					'name' => 'Bosnia and Herzegovina convertible mark',
					'symbol' => 'KM', // or КМ[G]',
					'unit' => 'Fening',
					'decimals' => 2,
				),
				'BBD' => array(
					'name' => 'Barbadian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'BDT' => array(
					'name' => 'Bangladeshi taka',
					'symbol' => '৳',
					'unit' => 'Paisa',
					'decimals' => 2,
				),
				'BGN' => array(
					'name' => 'Bulgarian lev',
					'symbol' => 'лв',
					'unit' => 'Stotinka',
					'decimals' => 2,
				),
				'BHD' => array(
					'name' => 'Bahraini dinar',
					'symbol' => '.د.ب',
					'unit' => 'Fils',
					'decimals' => 3,
				),
				'BIF' => array(
					'name' => 'Burundian franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'BMD' => array(
					'name' => 'Bermudian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'BND' => array(
					'name' => 'Brunei dollar',
					'symbol' => '$',
					'unit' => 'Sen',
					'decimals' => 2,
				),
				'BOB' => array(
					'name' => 'Bolivian boliviano',
					'symbol' => 'Bs.',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'BRL' => array(
					'name' => 'Brazilian real',
					'symbol' => 'R$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'BSD' => array(
					'name' => 'Bahamian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'BTN' => array(
					'name' => 'Bhutanese ngultrum',
					'symbol' => 'Nu.',
					'unit' => 'Chetrum',
					'decimals' => 2,
				),
				'BWP' => array(
					'name' => 'Botswana pula',
					'symbol' => 'P',
					'unit' => 'Thebe',
					'decimals' => 2,
				),
				'BYR' => array(
					'name' => 'Belarusian ruble',
					'symbol' => 'Br',
					'unit' => 'Kapyeyka',
					'decimals' => 2,
				),
				'BZD' => array(
					'name' => 'Belize dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'CAD' => array(
					'name' => 'Canadian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'CDF' => array(
					'name' => 'Congolese franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'CHF' => array(
					'name' => 'Swiss franc',
					'symbol' => 'Fr',
					'unit' => 'Rappen',
					'decimals' => 2,
				),
				'CHF' => array(
					'name' => 'Swiss franc',
					'symbol' => 'Fr',
					'unit' => 'Rappen[N]',
					'decimals' => 2,
				),
				'CLP' => array(
					'name' => 'Chilean peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'CNY' => array(
					'name' => 'Chinese yuan',
					'symbol' => '¥', // or 元',
					'unit' => 'Fen[H]',
					'decimals' => 2,
				),
				'COP' => array(
					'name' => 'Colombian peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'CRC' => array(
					'name' => 'Costa Rican colón',
					'symbol' => '₡',
					'unit' => 'Céntimo',
					'decimals' => 2,
				),
				'CUC' => array(
					'name' => 'Cuban convertible peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'CUP' => array(
					'name' => 'Cuban peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'CVE' => array(
					'name' => 'Cape Verdean escudo',
					'symbol' => 'Esc', // or $',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'CZK' => array(
					'name' => 'Czech koruna',
					'symbol' => 'Kč',
					'unit' => 'Haléř',
					'decimals' => 2,
				),
				'DJF' => array(
					'name' => 'Djiboutian franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'DKK' => array(
					'name' => 'Danish krone',
					'symbol' => 'kr',
					'unit' => 'Øre',
					'decimals' => 2,
				),
				'DOP' => array(
					'name' => 'Dominican peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'DZD' => array(
					'name' => 'Algerian dinar',
					'symbol' => 'د.ج',
					'unit' => 'Santeem',
					'decimals' => 2,
				),
				'EGP' => array(
					'name' => 'Egyptian pound',
					'symbol' => '£', // or ج.م',
					'unit' => 'Piastre[B]',
					'decimals' => 2,
				),
				'ERN' => array(
					'name' => 'Eritrean nakfa',
					'symbol' => 'Nfk',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'ETB' => array(
					'name' => 'Ethiopian birr',
					'symbol' => 'Br',
					'unit' => 'Santim',
					'decimals' => 2,
				),
				'EUR' => array(
					'name' => 'Euro',
					'symbol' => '€',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'FJD' => array(
					'name' => 'Fijian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'FKP' => array(
					'name' => 'Falkland Islands pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'GBP' => array(
					'name' => 'British pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'GBP' => array(
					'name' => 'British pound[E]',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'GEL' => array(
					'name' => 'Georgian lari',
					'symbol' => 'ლ',
					'unit' => 'Tetri',
					'decimals' => 2,
				),
				'GGP[F]' => array(
					'name' => 'Guernsey pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'GHS' => array(
					'name' => 'Ghana cedi',
					'symbol' => '₵',
					'unit' => 'Pesewa',
					'decimals' => 2,
				),
				'GIP' => array(
					'name' => 'Gibraltar pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'GMD' => array(
					'name' => 'Gambian dalasi',
					'symbol' => 'D',
					'unit' => 'Butut',
					'decimals' => 2,
				),
				'GNF' => array(
					'name' => 'Guinean franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'GTQ' => array(
					'name' => 'Guatemalan quetzal',
					'symbol' => 'Q',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'GYD' => array(
					'name' => 'Guyanese dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'HKD' => array(
					'name' => 'Hong Kong dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'HNL' => array(
					'name' => 'Honduran lempira',
					'symbol' => 'L',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'HRK' => array(
					'name' => 'Croatian kuna',
					'symbol' => 'kn',
					'unit' => 'Lipa',
					'decimals' => 2,
				),
				'HTG' => array(
					'name' => 'Haitian gourde',
					'symbol' => 'G',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'HUF' => array(
					'name' => 'Hungarian forint',
					'symbol' => 'Ft',
					'unit' => 'Fillér',
					'decimals' => 2,
				),
				'IDR' => array(
					'name' => 'Indonesian rupiah',
					'symbol' => 'Rp',
					'unit' => 'Sen',
					'decimals' => 2,
				),
				'ILS' => array(
					'name' => 'Israeli new shekel',
					'symbol' => '₪',
					'unit' => 'Agora',
					'decimals' => 2,
				),
				'IMP[F]' => array(
					'name' => 'Manx pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'INR' => array(
					'name' => 'Indian rupee',
					'symbol' => '₹',
					'unit' => 'Paisa',
					'decimals' => 2,
				),
				'IQD' => array(
					'name' => 'Iraqi dinar',
					'symbol' => 'ع.د',
					'unit' => 'Fils',
					'decimals' => 3,
				),
				'IRR' => array(
					'name' => 'Iranian rial',
					'symbol' => '﷼',
					'unit' => 'Dinar',
					'decimals' => 2,
				),
				'ISK' => array(
					'name' => 'Icelandic króna',
					'symbol' => 'kr',
					'unit' => 'Eyrir',
					'decimals' => 2,
				),
				'JEP[F]' => array(
					'name' => 'Jersey pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'JMD' => array(
					'name' => 'Jamaican dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'JOD' => array(
					'name' => 'Jordanian dinar',
					'symbol' => 'د.ا',
					'unit' => 'Piastre[J]',
					'decimals' => 2,
				),
				'JPY' => array(
					'name' => 'Japanese yen',
					'symbol' => '¥',
					'unit' => 'Sen[C]',
					'decimals' => 2,
				),
				'KES' => array(
					'name' => 'Kenyan shilling',
					'symbol' => 'Sh',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'KGS' => array(
					'name' => 'Kyrgyzstani som',
					'symbol' => 'лв[K]',
					'unit' => 'Tyiyn',
					'decimals' => 2,
				),
				'KHR' => array(
					'name' => 'Cambodian riel',
					'symbol' => '៛',
					'unit' => 'Sen',
					'decimals' => 2,
				),
				'KMF' => array(
					'name' => 'Comorian franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'KPW' => array(
					'name' => 'North Korean won',
					'symbol' => '₩',
					'unit' => 'Chon',
					'decimals' => 2,
				),
				'KRW' => array(
					'name' => 'South Korean won',
					'symbol' => '₩',
					'unit' => 'Jeon',
					'decimals' => 2,
				),
				'KWD' => array(
					'name' => 'Kuwaiti dinar',
					'symbol' => 'د.ك',
					'unit' => 'Fils',
					'decimals' => 3,
				),
				'KYD' => array(
					'name' => 'Cayman Islands dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'KZT' => array(
					'name' => 'Kazakhstani tenge',
					// 'symbol' => 'Kazakhstani tenge symbol.svg',
					'unit' => 'Tïın',
					'decimals' => 2,
				),
				'LAK' => array(
					'name' => 'Lao kip',
					'symbol' => '₭',
					'unit' => 'Att',
					'decimals' => 2,
				),
				'LBP' => array(
					'name' => 'Lebanese pound',
					'symbol' => 'ل.ل',
					'unit' => 'Piastre',
					'decimals' => 2,
				),
				'LKR' => array(
					'name' => 'Sri Lankan rupee',
					'symbol' => 'Rs', // or රු',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'LRD' => array(
					'name' => 'Liberian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'LSL' => array(
					'name' => 'Lesotho loti',
					'symbol' => 'L',
					'unit' => 'Sente',
					'decimals' => 2,
				),
				'LYD' => array(
					'name' => 'Libyan dinar',
					'symbol' => 'ل.د',
					'unit' => 'Dirham',
					'decimals' => 3,
				),
				'MAD' => array(
					'name' => 'Moroccan dirham',
					'symbol' => 'د. م.',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'MAD' => array(
					'name' => 'Moroccan dirham',
					'symbol' => 'د.م.',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'MDL' => array(
					'name' => 'Moldovan leu',
					'symbol' => 'L',
					'unit' => 'Ban',
					'decimals' => 2,
				),
				'MGA' => array(
					'name' => 'Malagasy ariary',
					'symbol' => 'Ar',
					'unit' => 'Iraimbilanja',
					'decimals' => '5',
				),
				'MKD' => array(
					'name' => 'Macedonian denar',
					'symbol' => 'ден',
					'unit' => 'Deni',
					'decimals' => 2,
				),
				'MMK' => array(
					'name' => 'Burmese kyat',
					'symbol' => 'Ks',
					'unit' => 'Pya',
					'decimals' => 2,
				),
				'MNT' => array(
					'name' => 'Mongolian tögrög',
					'symbol' => '₮',
					'unit' => 'Möngö',
					'decimals' => 2,
				),
				'MOP' => array(
					'name' => 'Macanese pataca',
					'symbol' => 'P',
					'unit' => 'Avo',
					'decimals' => 2,
				),
				'MRO' => array(
					'name' => 'Mauritanian ouguiya',
					'symbol' => 'UM',
					'unit' => 'Khoums',
					'decimals' => '5',
				),
				'MUR' => array(
					'name' => 'Mauritian rupee',
					'symbol' => '₨',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'MVR' => array(
					'name' => 'Maldivian rufiyaa',
					'symbol' => '.ރ',
					'unit' => 'Laari',
					'decimals' => 2,
				),
				'MWK' => array(
					'name' => 'Malawian kwacha',
					'symbol' => 'MK',
					'unit' => 'Tambala',
					'decimals' => 2,
				),
				'MXN' => array(
					'name' => 'Mexican peso',
					'symbol' => '$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'MYR' => array(
					'name' => 'Malaysian ringgit',
					'symbol' => 'RM',
					'unit' => 'Sen',
					'decimals' => 2,
				),
				'MZN' => array(
					'name' => 'Mozambican metical',
					'symbol' => 'MT',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'NAD' => array(
					'name' => 'Namibian dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'NGN' => array(
					'name' => 'Nigerian naira',
					'symbol' => '₦',
					'unit' => 'Kobo',
					'decimals' => 2,
				),
				'NIO' => array(
					'name' => 'Nicaraguan córdoba',
					'symbol' => 'C$',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'NOK' => array(
					'name' => 'Norwegian krone',
					'symbol' => 'kr',
					'unit' => 'Øre',
					'decimals' => 2,
				),
				'NPR' => array(
					'name' => 'Nepalese rupee',
					'symbol' => '₨',
					'unit' => 'Paisa',
					'decimals' => 2,
				),
				'NZD' => array(
					'name' => 'New Zealand dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'OMR' => array(
					'name' => 'Omani rial',
					'symbol' => 'ر.ع.',
					'unit' => 'Baisa',
					'decimals' => 3,
				),
				'PAB' => array(
					'name' => 'Panamanian balboa',
					'symbol' => 'B/.',
					'unit' => 'Centésimo',
					'decimals' => 2,
				),
				'PEN' => array(
					'name' => 'Peruvian nuevo sol',
					'symbol' => 'S/.',
					'unit' => 'Céntimo',
					'decimals' => 2,
				),
				'PGK' => array(
					'name' => 'Papua New Guinean kina',
					'symbol' => 'K',
					'unit' => 'Toea',
					'decimals' => 2,
				),
				'PHP' => array(
					'name' => 'Philippine peso',
					'symbol' => '₱',
					'unit' => 'Centavo',
					'decimals' => 2,
				),
				'PKR' => array(
					'name' => 'Pakistani rupee',
					'symbol' => '₨',
					'unit' => 'Paisa',
					'decimals' => 2,
				),
				'PLN' => array(
					'name' => 'Polish złoty',
					'symbol' => 'zł',
					'unit' => 'Grosz',
					'decimals' => 2,
				),
				'PRB[F]' => array(
					'name' => 'Transnistrian ruble',
					'symbol' => 'р.',
					'unit' => 'Kopek',
					'decimals' => 2,
				),
				'PYG' => array(
					'name' => 'Paraguayan guaraní',
					'symbol' => '₲',
					'unit' => 'Céntimo',
					'decimals' => 2,
				),
				'QAR' => array(
					'name' => 'Qatari riyal',
					'symbol' => 'ر.ق',
					'unit' => 'Dirham',
					'decimals' => 2,
				),
				'RON' => array(
					'name' => 'Romanian leu',
					'symbol' => 'lei',
					'unit' => 'Ban',
					'decimals' => 2,
				),
				'RSD' => array(
					'name' => 'Serbian dinar',
					'symbol' => 'дин.', // or din.',
					'unit' => 'Para',
					'decimals' => 2,
				),
				'RUB' => array(
					'name' => 'Russian ruble',
					'symbol' => 'RUB',
					'unit' => 'Kopek',
					'decimals' => 2,
				),
				'RUB' => array(
					'name' => 'Russian ruble[P]',
					'symbol' => 'RUB',
					'unit' => 'Kopek',
					'decimals' => 2,
				),
				'RWF' => array(
					'name' => 'Rwandan franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'SAR' => array(
					'name' => 'Saudi riyal',
					'symbol' => 'ر.س',
					'unit' => 'Halala',
					'decimals' => 2,
				),
				'SBD' => array(
					'name' => 'Solomon Islands dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SCR' => array(
					'name' => 'Seychellois rupee',
					'symbol' => '₨',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SDG' => array(
					'name' => 'Sudanese pound',
					'symbol' => 'ج.س.',
					'unit' => 'Piastre',
					'decimals' => 2,
				),
				'SEK' => array(
					'name' => 'Swedish krona',
					'symbol' => 'kr',
					'unit' => 'Öre',
					'decimals' => 2,
				),
				'SGD' => array(
					'name' => 'Singapore dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SHP' => array(
					'name' => 'Saint Helena pound',
					'symbol' => '£',
					'unit' => 'Penny',
					'decimals' => 2,
				),
				'SLL' => array(
					'name' => 'Sierra Leonean leone',
					'symbol' => 'Le',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SOS' => array(
					'name' => 'Somali shilling',
					'symbol' => 'Sh',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SRD' => array(
					'name' => 'Surinamese dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'SSP' => array(
					'name' => 'South Sudanese pound',
					'symbol' => '£',
					'unit' => 'Piastre',
					'decimals' => 2,
				),
				'STD' => array(
					'name' => 'São Tomé and Príncipe dobra',
					'symbol' => 'Db',
					'unit' => 'Cêntimo',
					'decimals' => 2,
				),
				'SYP' => array(
					'name' => 'Syrian pound',
					'symbol' => '£', // or ل.س',
					'unit' => 'Piastre',
					'decimals' => 2,
				),
				'SZL' => array(
					'name' => 'Swazi lilangeni',
					'symbol' => 'L',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'THB' => array(
					'name' => 'Thai baht',
					'symbol' => '฿',
					'unit' => 'Satang',
					'decimals' => 2,
				),
				'TJS' => array(
					'name' => 'Tajikistani somoni',
					'symbol' => 'ЅМ',
					'unit' => 'Diram',
					'decimals' => 2,
				),
				'TMT' => array(
					'name' => 'Turkmenistan manat',
					'symbol' => 'm',
					'unit' => 'Tennesi',
					'decimals' => 2,
				),
				'TND' => array(
					'name' => 'Tunisian dinar',
					'symbol' => 'د.ت',
					'unit' => 'Millime',
					'decimals' => 3,
				),
				'TOP' => array(
					'name' => 'Tongan paʻanga[O]',
					'symbol' => 'T$',
					'unit' => 'Seniti',
					'decimals' => 2,
				),
				'TRY' => array(
					'name' => 'Turkish lira',
					// 'symbol' => 'Turkish lira symbol black.svg',
					'unit' => 'Kuruş',
					'decimals' => 2,
				),
				'TTD' => array(
					'name' => 'Trinidad and Tobago dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'TWD' => array(
					'name' => 'New Taiwan dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'TZS' => array(
					'name' => 'Tanzanian shilling',
					'symbol' => 'Sh',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'UAH' => array(
					'name' => 'Ukrainian hryvnia',
					'symbol' => '₴',
					'unit' => 'Kopiyka',
					'decimals' => 2,
				),
				'UGX' => array(
					'name' => 'Ugandan shilling',
					'symbol' => 'Sh',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'USD' => array(
					'name' => 'United States dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'USD' => array(
					'name' => 'United States dollar',
					'symbol' => '$',
					'unit' => 'Cent[A]',
					'decimals' => 2,
				),
				'UYU' => array(
					'name' => 'Uruguayan peso',
					'symbol' => '$',
					'unit' => 'Centésimo',
					'decimals' => 2,
				),
				'UZS' => array(
					'name' => 'Uzbekistani som',
					// 'symbol' => 'Tenge symbol.svg',
					'unit' => 'Tiyin',
					'decimals' => 2,
				),
				'VEF' => array(
					'name' => 'Venezuelan bolívar',
					'symbol' => 'Bs F',
					'unit' => 'Céntimo',
					'decimals' => 2,
				),
				'VND' => array(
					'name' => 'Vietnamese đồng',
					'symbol' => '₫',
					'unit' => 'Hào[Q]',
					'decimals' => 1,
				),
				'VUV' => array(
					'name' => 'Vanuatu vatu',
					'symbol' => 'Vt',
					'unit' => '(none)',
					'decimals' => '(none)',
				),
				'WST' => array(
					'name' => 'Samoan tālā',
					'symbol' => 'T',
					'unit' => 'Sene',
					'decimals' => 2,
				),
				'XAF' => array(
					'name' => 'Central African CFA franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'XCD' => array(
					'name' => 'East Caribbean dollar',
					'symbol' => '$',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'XOF' => array(
					'name' => 'West African CFA franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'XPF' => array(
					'name' => 'CFP franc',
					'symbol' => 'Fr',
					'unit' => 'Centime',
					'decimals' => 2,
				),
				'YER' => array(
					'name' => 'Yemeni rial',
					'symbol' => '﷼',
					'unit' => 'Fils',
					'decimals' => 2,
				),
				'ZAR' => array(
					'name' => 'South African rand',
					'symbol' => 'R',
					'unit' => 'Cent',
					'decimals' => 2,
				),
				'ZMW' => array(
					'name' => 'Zambian kwacha',
					'symbol' => 'ZK',
					'unit' => 'Ngwee',
					'decimals' => 2,
				),
			),
		),
	),

);