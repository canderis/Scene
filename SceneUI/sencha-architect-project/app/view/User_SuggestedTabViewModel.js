/*
 * File: app/view/User_SuggestedTabViewModel.js
 *
 * This file was generated by Sencha Architect
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.2.x Modern library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.2.x Modern. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Scene.view.User_SuggestedTabViewModel', {
	extend: 'Ext.app.ViewModel',
	alias: 'viewmodel.user_suggestedtab',

	requires: [
		'Ext.data.Store',
		'Ext.data.field.Field'
	],

	stores: {
		SuggestedListStore: {
			data: [
				{
					distanceUnit: 'enim',
					key: 'minima',
					distance: 'ut',
					name: 'ut',
					address: 'labore',
					city: 'et',
					state: 'quo',
					postal_code: 'dolores',
					id: 'quas_01',
					phone: 'ducimus',
					rating_y: 'laboriosam',
					name_y: 'quibusdam',
					snippet_text_y: 'molestiae',
					image_url_y: 'aliquid',
					snippet_image_url_y: 'temporibus',
					display_phone_y: 'ut',
					id_y: 'ut',
					is_closed_y: 'inventore'
				},
				{
					distanceUnit: 'sit',
					key: 'ut',
					distance: 'est',
					name: 'natus',
					address: 'temporibus',
					city: 'accusantium',
					state: 'sed',
					postal_code: 'iusto',
					id: 'quis_11',
					phone: 'nisi',
					rating_y: 'velit',
					name_y: 'tempore',
					snippet_text_y: 'reprehenderit',
					image_url_y: 'velit',
					snippet_image_url_y: 'dolorem',
					display_phone_y: 'cupiditate',
					id_y: 'dolore',
					is_closed_y: 'id'
				},
				{
					distanceUnit: 'quod',
					key: 'repellendus',
					distance: 'architecto',
					name: 'assumenda',
					address: 'ut',
					city: 'non',
					state: 'eligendi',
					postal_code: 'nam',
					id: 'velit_21',
					phone: 'dolor',
					rating_y: 'qui',
					name_y: 'voluptas',
					snippet_text_y: 'magnam',
					image_url_y: 'dignissimos',
					snippet_image_url_y: 'sit',
					display_phone_y: 'ipsum',
					id_y: 'et',
					is_closed_y: 'quia'
				},
				{
					distanceUnit: 'animi',
					key: 'voluptas',
					distance: 'qui',
					name: 'doloremque',
					address: 'sed',
					city: 'accusamus',
					state: 'doloribus',
					postal_code: 'libero',
					id: 'harum_31',
					phone: 'unde',
					rating_y: 'eaque',
					name_y: 'et',
					snippet_text_y: 'dolorum',
					image_url_y: 'dolor',
					snippet_image_url_y: 'vitae',
					display_phone_y: 'velit',
					id_y: 'distinctio',
					is_closed_y: 'harum'
				},
				{
					distanceUnit: 'eos',
					key: 'odit',
					distance: 'ipsam',
					name: 'officiis',
					address: 'unde',
					city: 'error',
					state: 'beatae',
					postal_code: 'eligendi',
					id: 'omnis_41',
					phone: 'molestias',
					rating_y: 'non',
					name_y: 'et',
					snippet_text_y: 'sed',
					image_url_y: 'assumenda',
					snippet_image_url_y: 'vel',
					display_phone_y: 'aliquid',
					id_y: 'quo',
					is_closed_y: 'totam'
				},
				{
					distanceUnit: 'quo',
					key: 'rerum',
					distance: 'earum',
					name: 'cumque',
					address: 'quis',
					city: 'facilis',
					state: 'sequi',
					postal_code: 'voluptatum',
					id: 'voluptates_51',
					phone: 'voluptas',
					rating_y: 'aliquam',
					name_y: 'temporibus',
					snippet_text_y: 'est',
					image_url_y: 'maxime',
					snippet_image_url_y: 'nesciunt',
					display_phone_y: 'quasi',
					id_y: 'vitae',
					is_closed_y: 'autem'
				},
				{
					distanceUnit: 'ipsum',
					key: 'possimus',
					distance: 'harum',
					name: 'excepturi',
					address: 'voluptas',
					city: 'recusandae',
					state: 'modi',
					postal_code: 'in',
					id: 'qui_61',
					phone: 'autem',
					rating_y: 'ipsum',
					name_y: 'cum',
					snippet_text_y: 'laudantium',
					image_url_y: 'qui',
					snippet_image_url_y: 'sit',
					display_phone_y: 'consectetur',
					id_y: 'est',
					is_closed_y: 'non'
				},
				{
					distanceUnit: 'qui',
					key: 'et',
					distance: 'aut',
					name: 'reprehenderit',
					address: 'sit',
					city: 'voluptatem',
					state: 'quos',
					postal_code: 'aut',
					id: 'error_71',
					phone: 'neque',
					rating_y: 'tenetur',
					name_y: 'reprehenderit',
					snippet_text_y: 'sunt',
					image_url_y: 'aliquid',
					snippet_image_url_y: 'quos',
					display_phone_y: 'ex',
					id_y: 'est',
					is_closed_y: 'quo'
				},
				{
					distanceUnit: 'debitis',
					key: 'doloribus',
					distance: 'eum',
					name: 'adipisci',
					address: 'aut',
					city: 'consectetur',
					state: 'ea',
					postal_code: 'molestias',
					id: 'assumenda_81',
					phone: 'dolor',
					rating_y: 'quis',
					name_y: 'labore',
					snippet_text_y: 'voluptatem',
					image_url_y: 'expedita',
					snippet_image_url_y: 'accusantium',
					display_phone_y: 'tempora',
					id_y: 'dolorem',
					is_closed_y: 'sed'
				},
				{
					distanceUnit: 'et',
					key: 'hic',
					distance: 'reprehenderit',
					name: 'tempora',
					address: 'corrupti',
					city: 'facilis',
					state: 'labore',
					postal_code: 'modi',
					id: 'pariatur_91',
					phone: 'vel',
					rating_y: 'reprehenderit',
					name_y: 'vel',
					snippet_text_y: 'et',
					image_url_y: 'nesciunt',
					snippet_image_url_y: 'animi',
					display_phone_y: 'excepturi',
					id_y: 'provident',
					is_closed_y: 'repudiandae'
				},
				{
					distanceUnit: 'libero',
					key: 'ipsum',
					distance: 'quae',
					name: 'repellendus',
					address: 'delectus',
					city: 'aliquid',
					state: 'pariatur',
					postal_code: 'sint',
					id: 'fugit_101',
					phone: 'ut',
					rating_y: 'quam',
					name_y: 'sequi',
					snippet_text_y: 'voluptatem',
					image_url_y: 'porro',
					snippet_image_url_y: 'consectetur',
					display_phone_y: 'officia',
					id_y: 'sunt',
					is_closed_y: 'corrupti'
				},
				{
					distanceUnit: 'ipsa',
					key: 'sed',
					distance: 'officiis',
					name: 'aut',
					address: 'iure',
					city: 'aut',
					state: 'molestiae',
					postal_code: 'debitis',
					id: 'quasi_111',
					phone: 'qui',
					rating_y: 'libero',
					name_y: 'ipsa',
					snippet_text_y: 'porro',
					image_url_y: 'quasi',
					snippet_image_url_y: 'et',
					display_phone_y: 'ullam',
					id_y: 'ut',
					is_closed_y: 'voluptatem'
				},
				{
					distanceUnit: 'quam',
					key: 'voluptatem',
					distance: 'hic',
					name: 'eius',
					address: 'suscipit',
					city: 'ut',
					state: 'ipsam',
					postal_code: 'voluptatem',
					id: 'similique_121',
					phone: 'eum',
					rating_y: 'molestias',
					name_y: 'enim',
					snippet_text_y: 'maxime',
					image_url_y: 'sunt',
					snippet_image_url_y: 'nostrum',
					display_phone_y: 'laborum',
					id_y: 'aut',
					is_closed_y: 'et'
				},
				{
					distanceUnit: 'tenetur',
					key: 'assumenda',
					distance: 'in',
					name: 'eos',
					address: 'exercitationem',
					city: 'harum',
					state: 'dolores',
					postal_code: 'omnis',
					id: 'esse_131',
					phone: 'et',
					rating_y: 'corrupti',
					name_y: 'dolor',
					snippet_text_y: 'enim',
					image_url_y: 'nostrum',
					snippet_image_url_y: 'non',
					display_phone_y: 'repellendus',
					id_y: 'ut',
					is_closed_y: 'aliquam'
				},
				{
					distanceUnit: 'expedita',
					key: 'incidunt',
					distance: 'debitis',
					name: 'voluptate',
					address: 'blanditiis',
					city: 'dolor',
					state: 'magni',
					postal_code: 'aut',
					id: 'natus_141',
					phone: 'rem',
					rating_y: 'consequatur',
					name_y: 'tempore',
					snippet_text_y: 'fuga',
					image_url_y: 'tempore',
					snippet_image_url_y: 'ipsam',
					display_phone_y: 'dolor',
					id_y: 'aut',
					is_closed_y: 'quia'
				},
				{
					distanceUnit: 'esse',
					key: 'quam',
					distance: 'sunt',
					name: 'sunt',
					address: 'vitae',
					city: 'et',
					state: 'praesentium',
					postal_code: 'inventore',
					id: 'saepe_151',
					phone: 'neque',
					rating_y: 'porro',
					name_y: 'laboriosam',
					snippet_text_y: 'mollitia',
					image_url_y: 'dignissimos',
					snippet_image_url_y: 'omnis',
					display_phone_y: 'facere',
					id_y: 'et',
					is_closed_y: 'at'
				},
				{
					distanceUnit: 'saepe',
					key: 'nemo',
					distance: 'inventore',
					name: 'ipsum',
					address: 'dolor',
					city: 'commodi',
					state: 'rerum',
					postal_code: 'magni',
					id: 'aut_161',
					phone: 'optio',
					rating_y: 'dolore',
					name_y: 'sed',
					snippet_text_y: 'voluptate',
					image_url_y: 'temporibus',
					snippet_image_url_y: 'adipisci',
					display_phone_y: 'reprehenderit',
					id_y: 'ea',
					is_closed_y: 'eaque'
				},
				{
					distanceUnit: 'temporibus',
					key: 'qui',
					distance: 'assumenda',
					name: 'et',
					address: 'autem',
					city: 'qui',
					state: 'accusantium',
					postal_code: 'asperiores',
					id: 'dignissimos_171',
					phone: 'fugiat',
					rating_y: 'dolorem',
					name_y: 'aut',
					snippet_text_y: 'est',
					image_url_y: 'sit',
					snippet_image_url_y: 'facere',
					display_phone_y: 'esse',
					id_y: 'autem',
					is_closed_y: 'et'
				},
				{
					distanceUnit: 'ipsum',
					key: 'eum',
					distance: 'ex',
					name: 'aut',
					address: 'sit',
					city: 'qui',
					state: 'aperiam',
					postal_code: 'et',
					id: 'laudantium_181',
					phone: 'consequatur',
					rating_y: 'culpa',
					name_y: 'quas',
					snippet_text_y: 'labore',
					image_url_y: 'aut',
					snippet_image_url_y: 'assumenda',
					display_phone_y: 'rem',
					id_y: 'quam',
					is_closed_y: 'animi'
				},
				{
					distanceUnit: 'quia',
					key: 'qui',
					distance: 'aut',
					name: 'modi',
					address: 'et',
					city: 'sit',
					state: 'voluptatibus',
					postal_code: 'atque',
					id: 'voluptate_191',
					phone: 'voluptatem',
					rating_y: 'non',
					name_y: 'necessitatibus',
					snippet_text_y: 'tenetur',
					image_url_y: 'doloribus',
					snippet_image_url_y: 'labore',
					display_phone_y: 'magni',
					id_y: 'dolores',
					is_closed_y: 'reprehenderit'
				},
				{
					distanceUnit: 'illum',
					key: 'consectetur',
					distance: 'velit',
					name: 'dolores',
					address: 'qui',
					city: 'laudantium',
					state: 'vel',
					postal_code: 'sed',
					id: 'ut_201',
					phone: 'dicta',
					rating_y: 'necessitatibus',
					name_y: 'repudiandae',
					snippet_text_y: 'qui',
					image_url_y: 'qui',
					snippet_image_url_y: 'deserunt',
					display_phone_y: 'modi',
					id_y: 'rerum',
					is_closed_y: 'molestias'
				},
				{
					distanceUnit: 'unde',
					key: 'quaerat',
					distance: 'rerum',
					name: 'nam',
					address: 'aut',
					city: 'praesentium',
					state: 'quis',
					postal_code: 'modi',
					id: 'id_211',
					phone: 'omnis',
					rating_y: 'provident',
					name_y: 'ut',
					snippet_text_y: 'fugiat',
					image_url_y: 'assumenda',
					snippet_image_url_y: 'consequatur',
					display_phone_y: 'facilis',
					id_y: 'qui',
					is_closed_y: 'dolor'
				},
				{
					distanceUnit: 'vel',
					key: 'et',
					distance: 'consequuntur',
					name: 'pariatur',
					address: 'et',
					city: 'deleniti',
					state: 'est',
					postal_code: 'velit',
					id: 'dolorem_221',
					phone: 'asperiores',
					rating_y: 'dolor',
					name_y: 'ut',
					snippet_text_y: 'facilis',
					image_url_y: 'enim',
					snippet_image_url_y: 'non',
					display_phone_y: 'quae',
					id_y: 'impedit',
					is_closed_y: 'in'
				},
				{
					distanceUnit: 'esse',
					key: 'repudiandae',
					distance: 'qui',
					name: 'deleniti',
					address: 'quia',
					city: 'explicabo',
					state: 'incidunt',
					postal_code: 'blanditiis',
					id: 'odit_231',
					phone: 'nam',
					rating_y: 'molestiae',
					name_y: 'voluptas',
					snippet_text_y: 'et',
					image_url_y: 'nesciunt',
					snippet_image_url_y: 'accusantium',
					display_phone_y: 'fugiat',
					id_y: 'cum',
					is_closed_y: 'architecto'
				},
				{
					distanceUnit: 'distinctio',
					key: 'officia',
					distance: 'voluptas',
					name: 'quae',
					address: 'esse',
					city: 'consequatur',
					state: 'voluptatem',
					postal_code: 'accusantium',
					id: 'et_241',
					phone: 'aut',
					rating_y: 'optio',
					name_y: 'rerum',
					snippet_text_y: 'vel',
					image_url_y: 'blanditiis',
					snippet_image_url_y: 'nemo',
					display_phone_y: 'autem',
					id_y: 'qui',
					is_closed_y: 'sit'
				},
				{
					distanceUnit: 'quia',
					key: 'reprehenderit',
					distance: 'laudantium',
					name: 'recusandae',
					address: 'quia',
					city: 'unde',
					state: 'omnis',
					postal_code: 'aut',
					id: 'quod_251',
					phone: 'quas',
					rating_y: 'aperiam',
					name_y: 'quam',
					snippet_text_y: 'aliquam',
					image_url_y: 'quaerat',
					snippet_image_url_y: 'necessitatibus',
					display_phone_y: 'dolorem',
					id_y: 'voluptatem',
					is_closed_y: 'adipisci'
				},
				{
					distanceUnit: 'amet',
					key: 'deserunt',
					distance: 'est',
					name: 'quia',
					address: 'est',
					city: 'et',
					state: 'libero',
					postal_code: 'rerum',
					id: 'vel_261',
					phone: 'perspiciatis',
					rating_y: 'aspernatur',
					name_y: 'non',
					snippet_text_y: 'est',
					image_url_y: 'tempora',
					snippet_image_url_y: 'saepe',
					display_phone_y: 'quisquam',
					id_y: 'nemo',
					is_closed_y: 'dolor'
				},
				{
					distanceUnit: 'sit',
					key: 'eum',
					distance: 'ut',
					name: 'et',
					address: 'voluptas',
					city: 'consequatur',
					state: 'maiores',
					postal_code: 'voluptates',
					id: 'ea_271',
					phone: 'quia',
					rating_y: 'corporis',
					name_y: 'quasi',
					snippet_text_y: 'ipsum',
					image_url_y: 'et',
					snippet_image_url_y: 'perferendis',
					display_phone_y: 'pariatur',
					id_y: 'adipisci',
					is_closed_y: 'et'
				},
				{
					distanceUnit: 'modi',
					key: 'est',
					distance: 'odio',
					name: 'qui',
					address: 'in',
					city: 'ut',
					state: 'rem',
					postal_code: 'officia',
					id: 'ducimus_281',
					phone: 'et',
					rating_y: 'ratione',
					name_y: 'error',
					snippet_text_y: 'ad',
					image_url_y: 'ab',
					snippet_image_url_y: 'similique',
					display_phone_y: 'quis',
					id_y: 'veritatis',
					is_closed_y: 'qui'
				},
				{
					distanceUnit: 'quia',
					key: 'provident',
					distance: 'cum',
					name: 'occaecati',
					address: 'labore',
					city: 'est',
					state: 'veritatis',
					postal_code: 'quae',
					id: 'illo_291',
					phone: 'inventore',
					rating_y: 'reiciendis',
					name_y: 'sit',
					snippet_text_y: 'reiciendis',
					image_url_y: 'unde',
					snippet_image_url_y: 'odit',
					display_phone_y: 'aut',
					id_y: 'amet',
					is_closed_y: 'iusto'
				}
			],
			fields: [
				{
					name: 'distanceUnit'
				},
				{
					name: 'uniqueId'
				},
				{
					name: 'distance'
				},
				{
					name: 'name'
				},
				{
					name: 'address'
				},
				{
					name: 'city'
				},
				{
					name: 'state'
				},
				{
					name: 'postal_code'
				},
				{
					name: 'phone'
				},
				{
					name: 'rating_y'
				},
				{
					name: 'name_y'
				},
				{
					name: 'snippet_text_y'
				},
				{
					name: 'image_url_y'
				},
				{
					name: 'snippet_image_url_y'
				},
				{
					name: 'display_phone_y'
				},
				{
					name: 'id_y'
				},
				{
					name: 'is_closed_y'
				}
			]
		}
	}

});