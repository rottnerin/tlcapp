# Playwright Test: Admin Creates PL Day and Verifies in User View

## Test Objective
Verify that when an admin creates a PL Day event and sets it as active, it appears correctly in the end user view (welcome page).

## Test Steps

### 1. Admin Login
- Navigate to: `http://localhost:8000/admin/login`
- Fill in admin credentials:
  - Email: `admin@aes.ac.in` (or any admin email from seeder)
  - Password: `admin123`
- Submit login form
- Verify redirect to admin dashboard

### 2. Create PL Day
- Navigate to: `http://localhost:8000/admin/pddays`
- Click "Add New PL Day" button
- Fill in the form:
  - **Title**: `Playwright Test PL Day - December 2025`
  - **Description**: `This PL Day was created by Playwright automation for testing purposes.`
  - **Start Date**: `2025-12-20` (use Flatpickr calendar)
  - **End Date**: `2025-12-21` (use Flatpickr calendar)
  - **Set as Active PL Day**: ✓ (check the checkbox)
- Click "Create PL Day" button
- Verify success message: "PL Day created successfully."
- Verify redirect to PL Days index page
- Verify the new PL Day appears in the list with "Active" status

### 3. Verify in User View
- Navigate to: `http://localhost:8000/` (welcome page)
- Verify the PL Day date range appears on the welcome page
- Expected: The welcome page should display the date range (e.g., "December 20-21, 2025")
- If logged in as a user, navigate to `/dashboard` and verify PL Day information appears there as well

## Test Data
- **PL Day Title**: `Playwright Test PL Day - December 2025`
- **Start Date**: `2025-12-20`
- **End Date**: `2025-12-21`
- **Active Status**: Yes

## Expected Results
1. ✅ PL Day is created successfully in admin panel
2. ✅ PL Day is set as active (other PL Days are deactivated)
3. ✅ PL Day date range appears on welcome page (`/`)
4. ✅ PL Day information appears on user dashboard (if user is logged in)

## Notes
- The Flatpickr date picker requires interactive selection through the calendar UI
- For automated testing, you may need to:
  - Use JavaScript to set Flatpickr values programmatically
  - Or use Playwright's date picker interaction methods
  - Or interact with the Flatpickr calendar elements directly

## Manual Test Performed
A manual test was performed using the browser automation tools:
- ✅ Successfully navigated to admin login (already logged in)
- ✅ Navigated to PL Days create page
- ✅ Filled in title and description fields
- ⚠️ Date picker interaction requires manual calendar selection or JavaScript injection
- ✅ Checked "Set as Active PL Day" checkbox
- ⚠️ Form submission requires date fields to be filled

## Next Steps for Full Automation
1. Use JavaScript evaluation to set Flatpickr date values:
   ```javascript
   // Set dates via Flatpickr instance
   const startPicker = document.querySelector('#start_date')._flatpickr;
   startPicker.setDate('2025-12-20');
   const endPicker = document.querySelector('#end_date')._flatpickr;
   endPicker.setDate('2025-12-21');
   ```

2. Or interact with Flatpickr calendar elements:
   - Click date input to open calendar
   - Navigate to correct month/year
   - Click on desired date

3. Complete form submission and verify results
