const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		editor: './src/editor.ts',
		frontend: './src/frontend.ts',
	},
};
