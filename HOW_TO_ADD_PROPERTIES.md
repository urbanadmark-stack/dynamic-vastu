# How to Add New Properties

## Step 1: Access Admin Panel

1. Go to your website's admin login page:
   - **URL:** `https://yoursite.com/admin/` or `https://yoursite.com/admin/login.php`
   - Example: `https://linen-frog-939435.hostingersite.com/admin/`

2. **Login with your credentials:**
   - Username: `admin` (or your custom username)
   - Password: (the password you set up)

## Step 2: Navigate to Add Property

Once logged in:
1. You'll see the **Properties Dashboard**
2. Click the **"Add New Property"** button (usually at the top)
3. Or go directly to: `https://yoursite.com/admin/add-property.php`

## Step 3: Fill in Property Details

### Required Fields (marked with *):

- **Title** - Property title/name (e.g., "Beautiful 3-Bedroom House")
- **Property Type** - Select from: House, Apartment, Villa, Commercial, Land
- **Status** - For Sale, For Rent, Sold, or Rented
- **Price** - Property price (numeric value)
- **Address** - Street address
- **City** - City name
- **Description** - Detailed description of the property

### Optional Fields:

- **Bedrooms** - Number of bedrooms
- **Bathrooms** - Number of bathrooms (can use decimals like 2.5)
- **Area** - Property size
- **Area Unit** - Square Feet, Square Meters, or Acres
- **State** - State/Province
- **Zip Code** - Postal/ZIP code
- **Country** - Defaults to "USA"
- **Year Built** - Year the property was constructed
- **Parking Spaces** - Number of parking spaces
- **Features** - List features one per line (e.g., Swimming Pool, Garage, Garden)
- **Images** - Upload multiple property images (JPG, PNG, GIF, WebP - max 5MB each)

### Agent Information (Optional):

- **Agent Name** - Name of the listing agent
- **Agent Phone** - Contact phone number
- **Agent Email** - Contact email address

## Step 4: Upload Images

1. Click the **"Choose Files"** button under "Images"
2. Select one or multiple images from your computer
3. **Tips:**
   - Supported formats: JPG, JPEG, PNG, GIF, WebP
   - Maximum file size: 5MB per image
   - First image will be the main/featured image
   - You can upload multiple images for a gallery

## Step 5: Submit

1. Review all the information you entered
2. Click the **"Add Property"** button
3. You'll be redirected to the properties list with a success message
4. Your new property is now live on the website!

## View Your Property

After adding:
- Visit the **Properties page**: `https://yoursite.com/listings.php`
- Or go directly to the property: `https://yoursite.com/property.php?id=X` (X = property ID)
- The property will appear in search results and featured listings

## Editing Properties

To edit a property later:
1. Go to Admin Panel → Properties List
2. Click **"Edit"** next to the property you want to modify
3. Make your changes
4. Click **"Update Property"**

## Deleting Properties

To delete a property:
1. Go to Admin Panel → Properties List
2. Click **"Delete"** next to the property
3. Confirm the deletion
4. ⚠️ **Note:** This will permanently delete the property and its images

## Troubleshooting

### Images not uploading?
- Check file size (must be under 5MB)
- Verify file format (JPG, PNG, GIF, WebP only)
- Ensure `uploads/` folder has write permissions (755 or 777)

### Form not submitting?
- Make sure all required fields (*) are filled
- Check that price is greater than 0
- Verify you're logged in to admin panel

### Property not showing on website?
- Check property status (Sold/Rented properties may be hidden)
- Verify the property was saved successfully
- Clear browser cache and refresh

## Quick Tips

✅ **Best Practices:**
- Use clear, descriptive titles
- Write detailed descriptions (200+ words work best for SEO)
- Upload high-quality images (at least 1200x800px recommended)
- Include as many details as possible
- Add multiple images to showcase the property
- Keep agent contact information updated

📸 **Image Tips:**
- First image should be the best exterior/main photo
- Include interior, kitchen, bathroom, and other key areas
- Use natural lighting when possible
- Keep images consistent in style
- Optimize images before uploading (compress to reduce file size)

