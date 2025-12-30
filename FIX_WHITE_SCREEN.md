# Fix White Screen Issue - Edit Property Form

## Problem
After submitting changes in the edit-property form, the screen goes completely white.

## Most Likely Cause
The `rera_number` column doesn't exist in your database on the server. The code tries to update this column, causing a database error.

## Solution

### Step 1: Add the RERA column to your database

Log into phpMyAdmin in Hostinger hPanel and run this SQL:

```sql
ALTER TABLE `properties` 
ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`;
```

Or import the migration file:
- Go to phpMyAdmin
- Select your database
- Click "Import"
- Upload `database/add_rera_column.sql`

### Step 2: Verify the column was added

Run this query in phpMyAdmin to check:

```sql
DESCRIBE properties;
```

You should see `rera_number` in the column list.

### Step 3: Test the edit form again

After adding the column, the edit form should work without white screen errors.

## Alternative: Check Server Error Logs

If the issue persists after adding the column:

1. Check your server's PHP error log
2. Look for PDO or MySQL errors
3. Common errors:
   - "Unknown column 'rera_number'"
   - "SQLSTATE[42S22]: Column not found"

## Prevention

The code now includes try-catch error handling, so database errors will be caught and displayed as error messages instead of white screens.

