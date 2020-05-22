import axios from 'axios';
axios.defaults.headers.post['Content-Type'] ='application/x-www-form-urlencoded';


export default class httpClient {
	constructor(baseURL = null) {
		const config = {
			      mode: 'no-cors',

			headers: {
				'Access-Control-Allow-Origin': '*',
'Access-Control-Allow-Headers': 'Content-Type, Authorization',
			}
		};
		if(baseURL) {
			config.baseURL = baseURL;
		}
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

	async put(endpoint, data, options = {}) {
		return await this._client.patch(endpoint, data, {
			crossDomain: true,
			...options
		});
	}
}