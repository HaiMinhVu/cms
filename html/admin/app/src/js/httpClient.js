import axios from 'axios';
axios.defaults.headers.post['Content-Type'] ='application/x-www-form-urlencoded';

export default class httpClient {
	constructor(baseURL = null) {
		const config = {
			mode: 'no-cors',
			headers: {
				'Access-Control-Allow-Origin': '*',
				'Access-Control-Allow-Headers': 'Content-Type, Authorization'
			}
		};
		if(CONFIG.services.slmk.api_key) {
			config.headers['X-Api-Key'] = CONFIG.services.slmk.api_key;
		}
		config.baseURL = baseURL ? baseURL : CONFIG.services.slmk.api;
		this._client = new axios.create(config);
	}

	_setOptions(options) {
		['baseUri', 'api-token'].map(option => {
			if(`${option}` in options) {
				this[option] = options[option];
			}
		});
	}

	async get(endpoint, options = {}) {
		return await this._client.get(endpoint, options);
	}

	async post(endpoint, params = {}, options = {}) {
		return await this._client.post(endpoint, params, options);
	}

	async put(endpoint, data, options = {}) {
		return await this._client.patch(endpoint, data, {
			crossDomain: true,
			...options
		});
	}

	async delete(endpoint, data, options = {}) {
		return await this._client.delete(endpoint, data, {
			crossDomain: true,
			...options
		});
	}
}
