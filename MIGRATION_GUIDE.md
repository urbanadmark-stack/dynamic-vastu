# Database Migration Guide - Add RERA Column

## Problem
You're getting this error when submitting property forms:
```
Database error: The rera_number column is missing. Please run the migration: database/add_rera_column.sql
```

This happens because your database was created before the RERA number feature was added.

## Solution Options

### Option 1: Use the Migration Script (Easiest) ⭐

1. **Open your browser** and navigate to:
   ```
   https://yourdomain.com/run-migration.php
   ```

2. **Click "Run Migration"** button

3. **Verify success** - You should see a success message

4. **Delete the file** `run-migration.php` for security

That's it! The column will be added automatically.

### Option 2: Via phpMyAdmin

1. **Log into phpMyAdmin** in your Hostinger hPanel

2. **Select your database** (e.g., `u698056983_realtor`)

3. **Click on the SQL tab**

4. **Copy and paste this SQL query:**
   ```sql
   ALTER TABLE `properties` 
   ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`;
   ```

5. **Click "Go"** to execute

6. **Verify** - Check the `properties` table structure to confirm the column was added

### Option 3: Via SSH/Command Line

If you have SSH access:

```bash
# Connect to your database
mysql -u your_db_user -p your_database_name

# Then run:
ALTER TABLE `properties` 
ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`;

# Exit
exit;
```

## Verification

After running the migration, verify it worked:

1. Go to phpMyAdmin
2. Select your database
3. Click on the `properties` table
4. Click "Structure" tab
5. Look for `rera_number` column - it should be there!

## What This Migration Does

- Adds a new column `rera_number` to the `properties` table
- Column type: `varchar(100)` (can store up to 100 characters)
- Default value: `NULL` (optional field)
- Position: After the `agent_email` column

## After Migration

Once the migration is complete:
- ✅ You can add RERA numbers when creating/editing properties
- ✅ The error message will disappear
- ✅ RERA numbers will be displayed on property pages

## Troubleshooting

### Error: "Duplicate column name 'rera_number'"
- The column already exists! No action needed.

### Error: "Table 'properties' doesn't exist"
- Check your database name in `config.php`
- Make sure you're running the migration on the correct database

### Error: "Access denied"
- Verify your database user has ALTER TABLE permissions
- Check your database credentials in `config.php`

## Security Reminder

**IMPORTANT:** After running the migration, delete `run-migration.php` from your server to prevent unauthorized access.

---

**Need help?** Check your database connection settings in `config.php` and ensure your database user has proper permissions.

