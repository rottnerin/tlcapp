# Schedule Import CSV Template

This CSV template is used to import schedule items into the system.

## Column Descriptions

### Required Columns
- **title**: The title of the schedule item (required)
- **date**: The date of the event in YYYY-MM-DD format (e.g., 2025-01-15) (required)
- **start_time**: Start time in 24-hour/military format HH:MM (e.g., 09:00 for 9:00 AM, 14:30 for 2:30 PM) (required)
- **end_time**: End time in 24-hour/military format HH:MM (e.g., 10:30 for 10:30 AM, 16:00 for 4:00 PM) (required)
- **divisions**: Division names separated by pipe (|). Valid values: ALL, ES, MS, HS. Example: "ES|MS" or "ALL" (required - at least one division must be specified)

### Optional Columns
- **description**: Detailed description of the schedule item
- **location**: Where the event will take place
- **presenter_primary**: Primary presenter name
- **presenter_secondary**: Secondary/co-presenter name
- **presenter_bio**: Biography or background information about the presenter
- **equipment_needed**: List of equipment or materials needed
- **special_requirements**: Any special requirements or notes
- **link_title**: Title for an associated link
- **link_url**: URL for an associated link (include http:// or https://)
- **link_description**: Description for the link
- **is_active**: Whether the schedule item is active (1 for yes, 0 for no, default: 1)
- **session_type**: Type of session (e.g., regular, workshop, keynote, break, ttt, etc.)
- **p_d_day_id**: ID of the Professional Development (PD) Day this schedule item belongs to. PD Days are special events like "Spring PL Day 2025" or "Fall PL Day 2024". Leave empty if the schedule item is not part of a specific PD Day event. You can find PD Day IDs in the admin panel under PD Days management.
- **wellness_session_id**: ID of the Wellness Session this links to (leave empty if not applicable)

## Date/Time Format
- **date**: YYYY-MM-DD (e.g., 2025-01-15)
- **start_time**: HH:MM in 24-hour/military format (e.g., 09:00 for 9:00 AM, 14:30 for 2:30 PM)
- **end_time**: HH:MM in 24-hour/military format (e.g., 10:30 for 10:30 AM, 16:00 for 4:00 PM)

### Time Format Examples:
- 09:00 = 9:00 AM
- 12:00 = 12:00 PM (noon)
- 13:30 = 1:30 PM
- 14:00 = 2:00 PM
- 16:45 = 4:45 PM
- 17:00 = 5:00 PM

## Division Names
Use the following division names (separated by pipe | if multiple):
- **ALL**: All School (PreK-12)
- **ES**: Elementary School
- **MS**: Middle School
- **HS**: High School

### How to Input Multiple Divisions:
Separate division names with a **pipe character (|)**. Examples:
- Single division: `ES`
- Multiple divisions: `ES|MS` (for both Elementary and Middle School)
- Multiple divisions: `ES|MS|HS` (for all three divisions)
- All divisions: `ALL` (for all school PreK-12)

**Important:** Do NOT use commas, semicolons, or spaces. Only use the pipe character (|).

## Notes
- Leave optional fields empty if not applicable
- For boolean fields (is_active), use 1 for true/yes and 0 for false/no
- Use 24-hour/military time format (HH:MM) for start_time and end_time - no AM/PM needed
- Times should be on the same date specified in the date field
- **Multiple divisions**: Separate division names with a pipe character (|), e.g., `ES|MS` or `ES|MS|HS`
- Make sure URLs include the protocol (http:// or https://)
- **p_d_day_id**: Only needed if this schedule item is part of a specific Professional Development Day event. Leave empty for regular schedule items.
- **Note**: This template is for regular schedule sessions. TTT and Wellness sessions have capacity limits and are managed separately through their respective systems.
