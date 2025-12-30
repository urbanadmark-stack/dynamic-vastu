# Edit Project Form - Pending Field Updates

The edit-project.php file has been created by copying add-project.php and making initial modifications. However, many form fields still need to be updated to pre-populate with existing project data.

## Already Updated
- Project loading at top of file
- POST handler changed to use updateProject() instead of addProject()
- File upload handling preserves existing files
- JSON field decoding for display
- Basic fields: project_name, project_type, project_status, rera_number, developer_name, city, address

## Still Need to Update

All remaining form input fields need to be updated from:
```php
value="<?php echo isset($_POST['field']) ? htmlspecialchars($_POST['field']) : ''; ?>"
```

To:
```php
value="<?php echo isset($_POST['field']) ? htmlspecialchars($_POST['field']) : htmlspecialchars($project['database_column'] ?? ''); ?>"
```

### Fields to Update (mapping $_POST name to database column):

- `locality` → `$project['locality']`
- `landmark` → `$project['landmark']`
- `pincode` → `$project['pincode']`
- `latitude` → `$project['google_maps_latitude']`
- `longitude` → `$project['google_maps_longitude']`
- `short_description` → `$project['short_description']`
- `long_description` → `$project['long_description']`
- `price_range_min` → `$project['price_range_min']`
- `price_range_max` → `$project['price_range_max']`
- `structure_type` → `$project['structure_type']`
- `flooring` → `$project['flooring']`
- `kitchen` → `$project['kitchen']`
- `bathroom_fittings` → `$project['bathroom_fittings']`
- `doors_windows` → `$project['doors_windows']`
- `electrical` → `$project['electrical']`
- `launch_date` → `$project['project_launch_date']`
- `possession_date` → `$project['possession_date_rera']`
- `construction_status` → `$project['construction_status_percentage']`
- `walkthrough_video` → `$project['walkthrough_video_link']`
- `sales_contact_name` → `$project['sales_contact_name']`
- `sales_mobile` → `$project['sales_mobile_number']`
- `sales_email` → `$project['sales_email_id']`
- `seo_title` → `$project['seo_title']`
- `meta_description` → `$project['meta_description']`
- `target_keywords` → `$project['target_keywords']`

### Complex Fields Needing Special Handling:

1. **State dropdown** - Already partially handled, but needs verification
2. **Checkboxes** (all_inclusive_price, whatsapp_enabled, featured_project, etc.) - Need to check `$project['field']` for checked state
3. **JSON array fields**:
   - Project highlights (checkboxes)
   - Unit configurations (repeatable fields - need JavaScript to populate existing)
   - Amenities (checkboxes)
   - Nearby locations (repeatable fields - need JavaScript to populate existing)
   - Payment plans (checkboxes)

### File Display:
- Existing project images should be displayed with option to remove
- Existing PDFs should be shown with links to view/download
- 3D renders display

## Quick Fix Script

A script could be created to bulk-update all remaining text input fields, but checkbox and complex fields need manual handling.

