import dotenv from 'dotenv';
import path from 'path';

// Import the environment variables from the .env file in the root folder
dotenv.config({ path: path.resolve(__dirname, '..', '..', '.env')});

export default {
	homeUrl: process.env.HOME_URL || '',
};
