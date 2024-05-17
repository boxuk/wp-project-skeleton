import defaultConfig from '@wordpress/scripts/config/playwright.config';
import { defineConfig } from '@playwright/test';

process.env.WP_BASE_URL ??= 'http://127.0.0.1:80';
process.env.WP_ARTIFACTS_PATH ??= '../../../artifacts';
process.env.STORAGE_STATE_PATH ??= '../../../artifacts/storage-state.json';

module.exports = defineConfig( {
	...defaultConfig,
	webServer: null,
	globalSetup: require.resolve( './specs/global-setup.ts' ),
	use: {
		...defaultConfig.use,
		baseURL: process.env.WP_BASE_URL,
	},
} );
