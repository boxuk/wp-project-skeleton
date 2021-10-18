module.exports = {
	vars: {},

	/**
	 * Bulk set variables.
	 *
	 * @param {{}} vars Object containing variables.
	 */
	setVars: function(vars) {
		for(let key in vars) {
			if (vars.hasOwnProperty(key)) {
				this.setVar(key, vars[key]);
			}
		}
	},

	/**
	 * Bulk return the variables.
	 *
	 * @returns {{}}
	 */
	getVars: function() {
		return this.vars;
	},

	/**
	 * Set a variable.
	 *
	 * @param {string} key Variable name.
	 * @param {*} value Variable value.
	 */
	setVar: function(key, value) {
		this.vars[key] = value;
	},

	/**
	 * Retrieve a variable.
	 *
	 * @param {string} key Variable name
	 * @returns {*}
	 */
	getVar: function(key) {
		return this.vars[key];
	}
};
