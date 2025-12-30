# Creating Placeholder Image

The website uses a placeholder image for properties that don't have images uploaded yet. 

## Option 1: Use the SVG Placeholder (Recommended for Web)

The file `assets/images/placeholder.svg` has been created. To use it, update your PHP files to reference `.svg` instead of `.jpg`:

```php
$main_image = !empty($images) ? 'uploads/' . $images[0] : 'assets/images/placeholder.svg';
```

## Option 2: Convert SVG to JPG/PNG

You can convert the SVG to a JPG or PNG file using:
- Online tools: https://cloudconvert.com/svg-to-jpg
- Image editors: Photoshop, GIMP, etc.
- Command line: ImageMagick

1. Open `assets/images/placeholder.svg`
2. Export/Save as `placeholder.jpg` (800x600px recommended)
3. Save it in `assets/images/placeholder.jpg`

## Option 3: Use a Professional Stock Photo

You can also use a free stock photo from:
- Unsplash (https://unsplash.com)
- Pexels (https://pexels.com)
- Pixabay (https://pixabay.com)

Search for "house" or "real estate" and download a 800x600px image, then save it as `assets/images/placeholder.jpg`

## Current Implementation

The code currently looks for `assets/images/placeholder.jpg`. If you want to use the SVG instead, you'll need to update the file references in:
- `index.php`
- `listings.php`
- `property.php`
- `admin/index.php`

