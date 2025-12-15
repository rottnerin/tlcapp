/**
 * Helper script to set Flatpickr dates programmatically
 * Run this in the browser console on the PL Day create page
 */

// Set start date
const startInput = document.getElementById('start_date');
if (startInput && startInput._flatpickr) {
    startInput._flatpickr.setDate('2025-12-20', false);
    console.log('Start date set to:', startInput.value);
} else {
    // Fallback: try to set value directly
    startInput.removeAttribute('readonly');
    startInput.value = '2025-12-20';
    startInput.setAttribute('readonly', 'readonly');
    // Trigger change event
    startInput.dispatchEvent(new Event('change', { bubbles: true }));
    console.log('Start date set (fallback):', startInput.value);
}

// Set end date
const endInput = document.getElementById('end_date');
if (endInput && endInput._flatpickr) {
    endInput._flatpickr.setDate('2025-12-21', false);
    console.log('End date set to:', endInput.value);
} else {
    // Fallback: try to set value directly
    endInput.removeAttribute('readonly');
    endInput.value = '2025-12-21';
    endInput.setAttribute('readonly', 'readonly');
    // Trigger change event
    endInput.dispatchEvent(new Event('change', { bubbles: true }));
    console.log('End date set (fallback):', endInput.value);
}

// Return values for verification
return {
    startDate: startInput.value,
    endDate: endInput.value,
    success: startInput.value && endInput.value
};
