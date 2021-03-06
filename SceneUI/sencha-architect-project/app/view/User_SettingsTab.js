/*
 * File: app/view/User_SettingsTab.js
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

Ext.define('Scene.view.User_SettingsTab', {
	extend: 'Ext.Container',
	alias: 'widget.user_settingstab',

	requires: [
		'Scene.view.User_SettingsTabViewModel',
		'Ext.Button'
	],

	viewModel: {
		type: 'user_settingstab'
	},
	defaultListenerScope: true,

	items: [
		{
			xtype: 'button',
			itemId: 'mybutton3',
			text: 'Logout',
			listeners: {
				tap: 'onMybutton3Tap'
			}
		}
	],

	onMybutton3Tap: function(button, e, eOpts) {
		FB.logout(function(response) {
		   // Person is now logged out
			Ext.query('#ext-user_settingstab-1', false)[0].component.fireEvent( 'logoutSuccess' );

		});
	}

});