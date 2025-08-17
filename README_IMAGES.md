# Image URL System for Local Development

This document explains how the image URL system works for local development.

## How It Works

The system includes a `getImageUrl()` function that properly handles image paths for local development:

### Supported Image URL Formats:

1. **Full URLs** (https://example.com/image.jpg)
   - These are passed through unchanged

2. **Relative paths with uploads/** (uploads/filename.jpg)
   - These are used as-is for local development

3. **Relative paths without uploads/** (filename.jpg)
   - These are automatically prefixed with "uploads/"

4. **Empty paths**
   - These return a default placeholder image

## Files Updated

The following files have been updated to use the new image URL system:

- `dis.php` - Main discovery page
- `view_food.php` - Admin view page
- `display.php` - Public display page
- `config.php` - Configuration file
- `test_images.php` - Test page for debugging

## How to Use

### For Developers:

1. **Adding Images via Form:**
   - Enter full URL: `https://example.com/image.jpg`
   - Enter relative path: `uploads/myimage.jpg`
   - Enter filename: `myimage.jpg` (will become `uploads/myimage.jpg`)

2. **Testing Images:**
   - Visit `test_images.php` to see how image URLs are processed
   - Check browser console for any image loading errors

### For Local Development:

1. **Place images in the `uploads/` folder**
2. **Use relative paths in the database:**
   - `uploads/image1.jpg`
   - `uploads/image2.png`

3. **The system will automatically:**
   - Handle missing images gracefully
   - Show placeholder images when needed
   - Support both local files and external URLs

## Configuration

In `config.php`, you can set:

```php
$is_local = true; // Set to false for production
```

This controls how image paths are processed.

## Troubleshooting

### Images Not Showing:

1. Check if the image file exists in the `uploads/` folder
2. Verify the file permissions (should be readable by web server)
3. Check the browser console for 404 errors
4. Use `test_images.php` to debug image paths

### Common Issues:

1. **Wrong file path:** Make sure images are in the `uploads/` folder
2. **File permissions:** Ensure web server can read the files
3. **Case sensitivity:** File names are case-sensitive
4. **File extensions:** Make sure the extension matches the actual file

## Example Usage

```php
// In your PHP files
require_once 'config.php';

$image_path = "myimage.jpg";
$processed_url = getImageUrl($image_path);
// Result: "uploads/myimage.jpg"

$image_path = "https://example.com/image.jpg";
$processed_url = getImageUrl($image_path);
// Result: "https://example.com/image.jpg"
```

## Testing

Visit `test_images.php` to see:
- How image URLs are processed
- Which images load successfully
- Which images fail to load
- Test cases for different URL formats 