/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

function saveNewSSH() {
	let response = jeeDialog.get('#mod_add_sshmanager', 'content')

	let new_name = response.querySelector('.eqLogicAttr[data-l1key="name"]').value
	let new_host = response.querySelector('.eqLogicAttr[data-l2key="host"]').value
	let new_port = response.querySelector('.eqLogicAttr[data-l2key="port"]').value
	let new_timeout = response.querySelector('.eqLogicAttr[data-l2key="timeout"]').value
	let new_user = response.querySelector('.eqLogicAttr[data-l2key="username"]').value
	let new_password = response.querySelector('.eqLogicAttr[data-l2key="password"]').value
	let new_key = response.querySelector('.eqLogicAttr[data-l2key="ssh-key"]').value
	let new_passphrase = response.querySelector('.eqLogicAttr[data-l2key="ssh-passphrase"]').value
	let new_auth_method = response.querySelector('.eqLogicAttr[data-l2key="' + CONFIG_AUTH_METHOD + '"]').value

	jeedom.eqLogic.save({
		type: 'sshmanager',
		eqLogics: [{
			name: new_name,
			isEnable: 1,
			isVisible: 0,
			configuration: {
				'host': new_host,
				'port': new_port,
				'timeout': new_timeout,
				'username': new_user,
				'password': new_password,
				'ssh-key': new_key,
				'ssh-passphrase': new_passphrase,
				'auth-method': new_auth_method
			}
		}],
		error: function (error) {
			jeedomUtils.showAlert({
				title: "SSH Manager - Add New SSH Conf",
				message: "Error: " + error.message,
				level: 'danger',
				emptyBefore: false
			});
		},
		success: function (data) {
			jeedomUtils.showAlert({
				title: "SSH Manager - Add New SSH Conf",
				message: "Success: {{Equipement créé}} :: " + data.name + " (" + data.configuration['auth-method'] + ")",
				level: 'success',
				emptyBefore: false
			});
			jeeDialog.get('#mod_add_sshmanager').destroy()
		}

	});
}