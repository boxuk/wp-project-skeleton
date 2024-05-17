import { test, expect } from '@playwright/test';
import config from '../config';

test('HomePage Loads', async ({ page }) => {
    await page.goto(config.homeUrl);
	await expect(page.locator('.qa-home-page')).toBeVisible();
	await expect(page).toHaveTitle('Home Page');
});
