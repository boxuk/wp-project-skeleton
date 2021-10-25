const fs = require('fs');

module.exports = {
	/**
	 * Returns whether the file exists at the specified path.
	 *
	 * @param {string} path The path to validate.
	 * @returns {boolean}
	 */
	doesFileExist: function(path) {
		return fs.existsSync(path);
	},

	/**
	 * Returns whether a specified operation is valid.
	 *
	 * @param {string} operation The operation to validate.
	 * @param {array} validOperations array of operations which are considered valid.
	 *
	 * @returns {boolean}
	 */
	isOperationValid: function(operation, validOperations) {
		return validOperations.indexOf(operation) !== -1
	},

	/**
	 * Exit the process and display the reason in the console.
	 *
	 * @param {string} reason The reason for the early exit.
	 */
	exitWithReason: function(reason) {
		console.log(reason);
		process.exit();
	},

	/**
	 * Read file contents of JSON file and return a JSON object.
	 *
	 * If parameters object is passed, they will be injected into the JSON object before it is returned. Parameters used
	 * in the JSON file being parsed must be in the format %my_parameter%.
	 *
	 * @param {string} path
	 * @param {{}} parameters Reviver function.
	 */
	parseJsonFile: function(path, parameters = {}) {
		return JSON.parse(
			fs.readFileSync(path).toString(), function(key, value) {
				if (typeof value !== 'string') {
					return value;
				}

				// If no parameters are provided, no need to inject them into the results.
				if (Object.entries(parameters).length === 0) {
					return value;
				}

				let replacementToMake = false;

				let result = value.replace(/%.*?%/g, function(old) {
					let key = old.split("%").join("");
					replacementToMake = parameters.hasOwnProperty(key);

					return replacementToMake ? parameters[key] : old
				});

				// Ensure we don't transform our boolean variables into strings.
				if (replacementToMake && result === 'true') {
					return true;
				}

				// Ensure we don't transform our boolean variables into strings.
				if (replacementToMake && result === 'false') {
					return false;
				}

				return result;
			}
		);
	}
};
