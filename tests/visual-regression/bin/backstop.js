const backstop = require('backstopjs');
const envVars = require('../scripts/envVars.js');
const utils = require('../scripts/utils.js');
const { exec } = require("child_process");

const validOperations = [ 'test', 'approve' ];

const myArgs = process.argv.slice( 2 );
const operation = myArgs[ 0 ];
const env = myArgs[ 1 ] || 'local';

const configFolderPath = 'config/';
const envVarsFilePath = configFolderPath + env + '/env_vars.json';
const envSecretsFilePath = configFolderPath + env + '/secrets.json';
const profilePath = configFolderPath + 'profile.json';
const profilePathFallback = configFolderPath + 'profile.json.dist';
const backstopFilePath = 'backstop.json';

if ( ! utils.isOperationValid( operation, validOperations ) ) {
	utils.exitWithReason( '"' + operation + '" is not a valid operation. Valid operations are: ' + validOperations.join( ', ' ) );
}

if ( ! utils.doesFileExist( envVarsFilePath ) ) {
	utils.exitWithReason( 'Required file not found at ' + envVarsFilePath );
}

if ( ! utils.doesFileExist( envSecretsFilePath ) ) {
	utils.exitWithReason( 'Required file not found at ' + envSecretsFilePath );
}

if ( ! utils.doesFileExist( backstopFilePath ) ) {
	utils.exitWithReason( 'Required file not found at ' + backstopFilePath );
}

console.log( 'Using environment variables file ' + envVarsFilePath );
console.log( 'Using environment secrets file ' + envSecretsFilePath );
console.log( 'Using backstop file ' + backstopFilePath );

envVars.setVars( utils.parseJsonFile( envVarsFilePath ) );
envVars.setVars( utils.parseJsonFile( envSecretsFilePath ) );

const profilePathToUse = utils.doesFileExist( profilePath ) ? profilePath : profilePathFallback;
console.log( 'Using profile file ' + profilePathToUse );

envVars.setVars( utils.parseJsonFile( profilePathToUse ) );

// Add env_name to the configuration object, so it can be referenced from within the config.
envVars.setVar( 'env_name', env );

const config = utils.parseJsonFile( backstopFilePath, envVars.getVars() );

backstop( operation, config );
