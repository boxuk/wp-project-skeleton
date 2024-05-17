const defaultConfig = require( '@wordpress/scripts/config/jest-unit.config' );

module.exports = {
	...defaultConfig,
	collectCoverage: true,
	collectCoverageFrom: [
		'src/**/*.{ts,tsx}',
		'!src/editor.ts', // These are just entry points, no need to cover in tests
		'!src/frontend.ts', // These are just entry points, no need to cover in tests
	],
	testPathIgnorePatterns: [ '/node_modules/', '/specs/' ], // specs are for playwright
	coverageReporters: [ 'html', 'text-summary', 'lcov' ],
	coverageDirectory: '../../../coverage/jest',
	reporters: [
		'default',
		'github-actions',
		[ 'jest-junit', { outputDirectory: '../../../coverage/jest' } ],
	],
};
