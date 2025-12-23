# Admin Manual
## Dawn's ArtisanCraft Marketplace

**Version:** 1.0  
**Last Updated:** 2024-12-19  
**Website:** https://dawn1.infinityfreeapp.com/513week7

---

## Table of Contents

1. [Admin Introduction](#1-admin-introduction)
2. [Admin Login & Dashboard](#2-admin-login--dashboard)
3. [Managing Products via JSON Admin Page](#3-managing-products-via-json-admin-page)
4. [Managing Users with FluentCRM](#4-managing-users-with-fluentcrm)
5. [Managing Orders](#5-managing-orders)
6. [Managing the Discussion Forum](#6-managing-the-discussion-forum)
7. [Managing Website Content](#7-managing-website-content)
8. [Website Maintenance](#8-website-maintenance)
9. [Security & Best Practices](#9-security--best-practices)
10. [Hosting-Specific Instructions (InfinityFree)](#10-hosting-specific-instructions-infinityfree)
11. [Troubleshooting for Admins](#11-troubleshooting-for-admins)
12. [Appendix](#12-appendix)

---

## 1. Admin Introduction

### 1.1 Purpose of This Manual

This admin manual is designed to guide site administrators to effectively manage products, users, orders, forum content, website content, and perform routine maintenance and troubleshooting for Dawn's ArtisanCraft Marketplace e-commerce platform.

### 1.2 Admin Responsibilities

As an administrator, your key responsibilities include:

- **Product Management**: Manage the product catalog via the JSON Admin Page (`admin/products-crud.php`)
- **User Management**: Oversee user accounts and subscriptions through FluentCRM and the Manage Subscribers page (`admin/manage-subscribers.php`)
- **Order Processing**: Process and update customer orders in the custom `orders` table
- **Forum Moderation**: Moderate forum posts and manage categories to maintain a positive community environment
- **Content Management**: Update website content (banners, pages, job postings on the Recruitment page)
- **Database Maintenance**: Perform regular database backups and site maintenance
- **Security**: Ensure website security and apply updates
- **Troubleshooting**: Resolve technical issues (errors, sync problems, performance issues)

### 1.3 Access Control & Roles

The website supports distinct admin roles with different permission levels:

- **Super Admin**: Full access to all admin functions, including user role management and security settings
- **Content Admin**: Access to website content management (banners, pages, job postings)
- **Product Admin**: Limited to product management (add/edit/delete products via JSON Admin Page)
- **Forum Admin**: Restricted to forum moderation and category management

**Note:** Role permissions are managed through the `users` table in the database. Contact the system administrator to modify user roles.

---

## 2. Admin Login & Dashboard

### 2.1 Accessing the Admin Panel

**Admin Product Management URL:** https://dawn1.infinityfreeapp.com/513week7/admin/products-crud.php

**Admin Subscriber Management URL:** https://dawn1.infinityfreeapp.com/513week7/admin/manage-subscribers.php

**Admin Orders URL:** https://dawn1.infinityfreeapp.com/513week7/admin/order-detail.php (if available)

**Login Requirements:**
- You must have an admin account with appropriate permissions
- Use your registered email and password to log in through the main website login page
- After logging in, navigate to the admin pages listed above

> **Screenshot Placeholder:** [Figure 2.1: Admin login page with admin access URLs]

### 2.2 Admin Dashboard Overview

The admin interface consists of several key pages:

- **Products CRUD Page**: Manage products via JSON (`admin/products-crud.php`)
- **Manage Subscribers Page**: Manage user subscriptions (`admin/manage-subscribers.php`)
- **Order Management**: View and process orders (if order management page is available)
- **Registered Users Page**: View all registered users (`admin/registered-users.php`)

> **Screenshot Placeholder:** [Figure 2.2: Admin dashboard overview showing all admin pages]

### 2.3 Navigating the Admin Menu

The admin interface is accessed through direct URLs. Key admin pages include:

- **Products CRUD**: `/admin/products-crud.php` - JSON-based product management
- **Manage Subscribers**: `/admin/manage-subscribers.php` - User subscription management
- **Registered Users**: `/admin/registered-users.php` - View all registered users
- **Analytics** (if available): `/admin/analytics.php` - View site analytics
- **Inventory** (if available): `/admin/inventory.php` - Manage inventory

---

## 3. Managing Products via JSON Admin Page

### 3.1 Accessing the Custom Admin CRUD Page

1. **Log into the Website**
   - Go to: https://dawn1.infinityfreeapp.com/513week7
   - Click **"Login"** and enter your admin credentials

2. **Navigate to Products CRUD Page**
   - Direct URL: https://dawn1.infinityfreeapp.com/513week7/admin/products-crud.php
   - Or access through the admin menu if available

> **Screenshot Placeholder:** [Figure 3.1: Products CRUD page with product list and action buttons]

### 3.2 CRUD Operations

#### Adding a New Product

1. **Open the Add Product Form**
   - On the Products CRUD page, click the **"Add New Product"** or **"Create Product"** button
   - A form will appear with fields for product information

2. **Fill Out Product Information**
   - **Name**: Enter the product name (e.g., "Elegant Bracelet")
   - **Price**: Enter the product price (e.g., 59.00)
   - **Discount Percentage**: Enter discount percentage if applicable (e.g., 10 for 10% off)
   - **Quantity**: Enter available stock quantity
   - **Short Description**: Brief product description for product cards
   - **Description**: Full product description for product details page
   - **Supplier**: Enter artisan/supplier name (e.g., "Artisan A")
   - **Category**: Select or enter category (Jewelry, Home Decor, Textiles, Furniture, Art, Kitchenware)
   - **Origin Country**: Country where product was made
   - **Warranty Period**: Warranty duration (e.g., "12 months")
   - **Material Type**: Materials used in the product
   - **Image URL**: Enter image URL or upload an image file
   - **Image Link**: Optional link for product image

3. **Upload Product Image** (Optional)
   - Click **"Choose File"** under "Upload Image"
   - Select an image file from your device (JPEG, PNG, or GIF)
   - The image will be uploaded to `/image/uploads/` directory

4. **Save the Product**
   - Click **"Save Product"** or **"Create Product"** button
   - The product will be added to `data/products.json` and appear on the products page

> **Screenshot Placeholder:** [Figure 3.2: Add product form with all fields labeled]

#### Editing an Existing Product

1. **Find the Product**
   - On the Products CRUD page, locate the product you want to edit
   - Use the search function if available, or scroll through the product list

2. **Open Edit Form**
   - Click the **"Edit"** button next to the product
   - The edit form will open with current product data pre-filled

3. **Modify Product Data**
   - Update any fields you want to change (name, price, description, etc.)
   - Upload a new image if you want to replace the existing one

4. **Save Changes**
   - Click **"Update Product"** or **"Save Changes"** button
   - Changes will be saved to `data/products.json` and reflected on the live site

> **Screenshot Placeholder:** [Figure 3.3: Edit product form with updated fields]

#### Deleting a Product

1. **Locate the Product**
   - Find the product you want to delete on the Products CRUD page

2. **Delete the Product**
   - Click the **"Delete"** button next to the product
   - A confirmation prompt will appear

3. **Confirm Deletion**
   - Click **"Confirm Delete"** or **"Yes, Delete"** in the prompt
   - **Warning**: Deletion cannot be undone—ensure you want to remove the product before confirming
   - The product will be removed from `data/products.json` and disappear from the live site

> **Screenshot Placeholder:** [Figure 3.4: Delete confirmation dialog]

#### Viewing All Products

The Products CRUD page displays a table of all products with the following information:

- **ID**: Unique product identifier
- **Name**: Product name
- **Price**: Product price
- **Discount**: Discount percentage (if applicable)
- **Category**: Product category
- **Image**: Product image thumbnail
- **Actions**: Edit and Delete buttons

You can scroll through the table to view all products, or use search/filter options if available.

### 3.3 JSON Validation Rules

When adding or editing products, ensure the following rules are followed:

**Mandatory Fields:**
- `id`: Unique numeric value (auto-generated if not provided)
- `name`: String, 2-100 characters
- `price`: Numeric value, 0.01 or higher
- `description`: String, 10-500 characters
- `image_url`: Valid URL string or relative path pointing to a product image
- `category`: String matching existing site categories

**Data Format Rules:**
- Use double quotes for string values
- Use commas to separate key-value pairs
- No trailing commas
- Numeric values should not be quoted

**Example Valid JSON Structure:**
```json
{
  "id": 123,
  "name": "Wireless Headphones",
  "price": 99.99,
  "discount_percentage": 15,
  "quantity": 10,
  "short_description": "Noise-canceling wireless headphones",
  "description": "Premium wireless headphones with 20-hour battery life and active noise cancellation",
  "image_url": "image/headphones.jpg",
  "image_link": "https://example.com/headphones",
  "supplier": "Artisan A",
  "category": "Electronics",
  "origin_country": "USA",
  "warranty_period": "12 months",
  "material_type": "Plastic, Metal"
}
```

### 3.4 Error Handling for JSON

If a validation error occurs, you will see an error message at the top of the page. Common errors include:

- **"Missing required field: price"**: Add the missing field to your product data
- **"Invalid JSON syntax"**: Check for missing commas, unquoted strings, or trailing commas
- **"Invalid price value"**: Ensure price is a positive number

**To Resolve Errors:**
1. Review the error message to identify the issue
2. Return to the product form and fix the problem
3. Re-validate by clicking **"Save Product"** again
4. Once validation passes, the product will be saved

### 3.5 Location of the products.json File

The `products.json` file is stored in the site's directory at:
- **Path**: `/data/products.json` (relative to site root)
- **Full Path**: `C:\Users\34428\Desktop\513week7\data\products.json` (local) or `/htdocs/data/products.json` (server)

**Access Methods:**
- **Via FTP/File Manager**: Connect via FTP or use your hosting control panel's File Manager to access and edit the file directly
- **Via Admin Page**: Use the Products CRUD page (recommended) to avoid JSON syntax errors
- **Backup**: Always create a backup before modifying the file directly

---

## 4. Managing Users with FluentCRM

### 4.1 Viewing the Customer List

1. **Access the Manage Subscribers Page**
   - Navigate to: https://dawn1.infinityfreeapp.com/513week7/admin/manage-subscribers.php
   - You must be logged in as an admin to access this page

2. **View Subscriber List**
   - The page displays a table of all registered users/subscribers
   - Columns include:
     - **ID**: Unique subscriber identifier
     - **Last Name**: Subscriber's last name
     - **First Name**: Subscriber's first name
     - **Email**: Subscriber's email address
     - **Current Status**: Subscription status (active, pending, bounced, complained, unsubscribed)
     - **Change Status**: Dropdown to update status
     - **Action**: Update button and email icon

> **Screenshot Placeholder:** [Figure 4.1: Manage Subscribers page with subscriber table]

### 4.2 Managing Subscribers

#### Updating Subscriber Status

1. **Find the Subscriber**
   - Locate the subscriber in the table (use search if available)

2. **Change Status**
   - Select a new status from the **"Change Status"** dropdown:
     - **Active**: Subscriber is active and receiving emails
     - **Pending**: Subscriber is pending activation
     - **Bounced**: Email bounced (invalid email)
     - **Complained**: Subscriber reported spam
     - **Unsubscribed**: Subscriber opted out

3. **Update Status**
   - Click the **"Update"** button next to the subscriber
   - The status will be updated in the database
   - A success message will confirm the update

> **Screenshot Placeholder:** [Figure 4.2: Status update dropdown and update button]

#### Understanding Subscriber Tables

The system checks multiple database tables for subscribers:

1. **wpah_fc_subscribers**: Primary FluentCRM subscribers table (checked first)
2. **ac_users**: Alternative users table (checked if wpah_fc_subscribers doesn't exist)
3. **users**: Standard users table (checked last)

The admin page automatically detects which table contains the subscriber and updates the correct table.

### 4.3 Understanding FluentCRM Subscriber Fields

Key fields in the subscriber tables include:

- **Name** (`first_name`, `last_name`): Subscriber's full name
- **Email** (`email`): Unique identifier for the contact
- **Tags** (`tags`): Categorization (e.g., 'Customer', 'Newsletter Subscriber', 'VIP')
- **Subscription Status** (`status`): 'Active' or 'Inactive' for newsletter
- **Date Added** (`created_at`): Date the contact was added to FluentCRM

---

## 5. Managing Orders

### 5.1 Viewing Orders

Orders are stored in the custom `orders` table in the `if0_37969254_513week7` database. To view orders:

1. **Access Order Information**
   - Orders are created when customers complete checkout
   - Order data is stored in the `orders` table with the following structure:
     - `order_id`: Unique order identifier
     - `user_id`: Links to the customer's user ID
     - `order_number`: Unique order number (e.g., AC202412191234)
     - `status`: Order status (pending, processing, shipped, delivered, cancelled, refunded)
     - `subtotal`: Order subtotal before tax and shipping
     - `tax_amount`: Calculated tax (8% of subtotal)
     - `shipping_amount`: Shipping cost ($9.99 or 0 if free shipping)
     - `total_amount`: Final order total
     - `payment_status`: Payment status (paid, pending, failed)
     - `payment_method`: Payment method used
     - `created_at`: Date and time order was placed

2. **View Order Details via Database**
   - Access phpMyAdmin through your hosting control panel
   - Select the `if0_37969254_513week7` database
   - Open the `orders` table to view all orders
   - Use the `order_items` table to view individual items in each order

> **Screenshot Placeholder:** [Figure 5.1: Orders table in phpMyAdmin showing order structure]

### 5.2 Updating Order Status

To update an order status:

1. **Access the Order**
   - Use phpMyAdmin or a custom admin order management page (if available)
   - Locate the order by `order_id` or `order_number`

2. **Update Status**
   - Edit the `status` field in the `orders` table
   - Available statuses:
     - **Pending**: Order is pending processing
     - **Processing**: Order is being prepared
     - **Shipped**: Order has been shipped
     - **Delivered**: Order has been delivered
     - **Cancelled**: Order has been cancelled
     - **Refunded**: Order has been refunded

3. **Save Changes**
   - Update the `status` field and save
   - Customers can view updated status in "My Orders"

### 5.3 Searching/Filtering Orders

In phpMyAdmin, you can:

1. **Search by Order ID**
   - Use the search function in phpMyAdmin
   - Enter the `order_id` or `order_number`

2. **Filter by Status**
   - Use SQL queries to filter orders:
     ```sql
     SELECT * FROM orders WHERE status = 'processing';
     ```

3. **Filter by Date Range**
   - Use SQL queries to filter by date:
     ```sql
     SELECT * FROM orders WHERE created_at BETWEEN '2024-12-01' AND '2024-12-31';
     ```

### 5.4 Order Table Structure

The `orders` table structure:

- **order_id** (INT, PRIMARY KEY): Unique identifier for each order
- **user_id** (INT): Links to the customer's user ID in the `users` table
- **order_number** (VARCHAR): Unique order number (e.g., AC202412191234)
- **status** (VARCHAR): Order status
- **subtotal** (DECIMAL): Order subtotal
- **tax_amount** (DECIMAL): Tax amount (8%)
- **shipping_amount** (DECIMAL): Shipping cost
- **total_amount** (DECIMAL): Final total
- **payment_status** (VARCHAR): Payment status
- **payment_method** (VARCHAR): Payment method
- **created_at** (DATETIME): Order creation date

The `order_items` table structure:

- **item_id** (INT, PRIMARY KEY): Unique identifier for each order item
- **order_id** (INT, FOREIGN KEY): Links to the `orders` table
- **product_id** (INT): Product ID from `products.json`
- **quantity** (INT): Quantity ordered
- **price** (DECIMAL): Price per item at time of order
- **subtotal** (DECIMAL): Line total (quantity × price)

---

## 6. Managing the Discussion Forum

### 6.1 Overview of Forum Setup

The forum is a custom-built discussion board integrated into the website. The forum structure follows:

- **Categories**: Main sections (e.g., 'General Discussion', 'Product Questions', 'Reviews')
- **Topics/Posts**: Individual discussion threads within categories
- **Replies**: User comments/replies within topics

**Database Tables:**
- `forum_posts`: Stores forum topics/posts
- `forum_replies`: Stores replies to forum posts

### 6.2 Moderating Posts

#### Accessing Forum Posts

1. **View Forum Posts**
   - Navigate to: https://dawn1.infinityfreeapp.com/513week7/forum.php
   - You must be logged in as an admin to moderate posts

2. **Moderate Posts via Database**
   - Access phpMyAdmin
   - Select the `if0_37969254_513week7` database
   - Open the `forum_posts` table to view all posts
   - Open the `forum_replies` table to view all replies

#### Editing Posts

1. **Find the Post**
   - Locate the post in the `forum_posts` table in phpMyAdmin

2. **Edit Content**
   - Click "Edit" on the post row
   - Modify the `title` or `content` fields
   - Update the `status` field if needed (e.g., 'published', 'pending', 'deleted')

3. **Save Changes**
   - Click "Go" to save changes
   - The updated post will appear on the forum

#### Deleting Posts

1. **Find the Post**
   - Locate the post in the `forum_posts` table

2. **Delete the Post**
   - Click "Delete" on the post row
   - Confirm deletion
   - **Warning**: Deleting a post may delete all replies to that post

#### Managing Replies

1. **View Replies**
   - Open the `forum_replies` table in phpMyAdmin
   - Replies are linked to posts via `post_id`

2. **Edit or Delete Replies**
   - Edit the `content` field to modify a reply
   - Delete the row to remove a reply

### 6.3 Managing Forum Categories

Forum categories are stored in the database. To manage categories:

1. **Access Categories**
   - Categories may be hardcoded in `forum.php` or stored in a database table
   - Check the `forum.php` file for category definitions

2. **Add/Edit Categories**
   - Modify the category list in `forum.php` or the database table (if exists)
   - Common categories include:
     - General Discussion
     - Product Questions
     - Reviews
     - Support

---

## 7. Managing Website Content

### 7.1 Updating Homepage Banner and Slogan

The homepage banner and slogan are defined in `index.php`. To update:

1. **Access the Homepage File**
   - Via FTP/File Manager, navigate to `/index.php`
   - Or edit locally and upload via FTP

2. **Update Banner Image**
   - Locate the banner section in `index.php` (around line 71-84)
   - Update the image URL:
     ```php
     <img src="<?php echo SITE_URL; ?>/image/logo.jpg" alt="ArtisanCraft Logo">
     ```
   - Replace `logo.jpg` with your new image filename
   - Upload the new image to `/image/` directory

3. **Update Slogan**
   - Locate the slogan text in `index.php`:
     ```php
     <p>"Where Art Meets Craft, Quality Meets Passion"</p>
     ```
   - Modify the text as needed

4. **Save and Upload**
   - Save the file and upload via FTP if editing locally
   - Refresh the homepage to see changes

> **Screenshot Placeholder:** [Figure 7.1: Homepage banner section in index.php code]

### 7.2 Editing the About Us Page

1. **Access the About Page**
   - File location: `/about.php`
   - Open via FTP/File Manager or edit locally

2. **Update Content**
   - Modify the HTML content in `about.php`
   - Update text, images, and embedded maps as needed

3. **Save Changes**
   - Save and upload the file
   - View the About page to verify changes

### 7.3 Managing Job Postings on the Recruitment Page

1. **Access the Recruitment Page**
   - File location: `/recruitment.php`
   - Open via FTP/File Manager

2. **Update Job Postings**
   - Job postings are defined in the PHP code
   - Locate the job positions array in `recruitment.php`
   - Add, edit, or remove job entries

3. **Save Changes**
   - Save and upload the file
   - View the Recruitment page to verify changes

> **Screenshot Placeholder:** [Figure 7.2: Recruitment page code showing job postings structure]

### 7.4 Updating Support/Contact Information

1. **Update Contact Page**
   - File location: `/contact.php` or `/feedback.php`
   - Modify contact details (email, phone, address) in the file

2. **Update Footer Contact Info**
   - File location: `/includes/footer.php`
   - Modify footer contact information

3. **Save Changes**
   - Save and upload files
   - Verify updates on the Contact page and footer

---

## 8. Website Maintenance

### 8.1 Backing Up the Database via phpMyAdmin

1. **Log into Hosting Control Panel**
   - Access your InfinityFree control panel
   - Navigate to phpMyAdmin

2. **Select Database**
   - In phpMyAdmin, select your website's database from the left menu:
     - `if0_37969254_513week7` (main database)
     - `if0_37969254_wp802` (user/registration database)

3. **Export Database**
   - Click the **"Export"** tab at the top
   - Select **"Quick"** as the export method (or **"Custom"** for specific tables)
   - Choose **"SQL"** as the output format
   - Click **"Go"**
   - The database backup (SQL file) will download to your device

4. **Store Backup**
   - Save the backup in a secure location (external drive, cloud storage)
   - Label it with the date (e.g., `513week7_backup_2024-12-19.sql`)

> **Screenshot Placeholder:** [Figure 8.1: phpMyAdmin export interface]

### 8.2 Exporting/Importing Data

#### Exporting Data

1. **Export products.json**
   - Via FTP/File Manager, navigate to `/data/products.json`
   - Download the file to your local device
   - Store in a backup location

2. **Export User Data** (if needed)
   - Use phpMyAdmin to export the `users` table or entire database

#### Importing Data

1. **Import Database**
   - In phpMyAdmin, select your database
   - Click the **"Import"** tab
   - Choose the SQL backup file
   - Click **"Go"** to import

2. **Import products.json**
   - Via FTP/File Manager, upload the `products.json` file to `/data/`
   - Ensure the file is in valid JSON format

### 8.3 Updating WordPress, Themes, and Plugins

**Note:** This website is a custom PHP application, not a WordPress site. However, if WordPress components are used:

1. **Check for Updates**
   - Log into WordPress Admin (if applicable)
   - Check the "Updates" section for available updates

2. **Backup Before Updating**
   - Always backup the database and files before updating

3. **Apply Updates**
   - Update WordPress core, themes, and plugins one at a time
   - Test the site after each update

### 8.4 Monitoring Site Performance and Logs

1. **Site Performance**
   - Use tools like Google PageSpeed Insights: https://pagespeed.web.dev/
   - Monitor loading times and optimize images/content as needed

2. **Error Logs**
   - Access error logs via your hosting control panel
   - InfinityFree: Check the "Logs" section in the control panel
   - Review logs for PHP errors, database connection issues, etc.

3. **Database Performance**
   - Monitor database size and query performance in phpMyAdmin
   - Optimize tables if needed (phpMyAdmin > Table > Optimize table)

---

## 9. Security & Best Practices

### 9.1 Keeping the Site Secure

1. **Strong Passwords**
   - Use passwords with:
     - Minimum 12 characters
     - Mix of uppercase, lowercase, numbers, and special characters
     - No common passwords or personal information
   - Update passwords every 90 days

2. **Limit Admin Access**
   - Only grant admin access to trusted personnel
   - Review admin accounts regularly

3. **Input Sanitization**
   - All user inputs are sanitized using functions in `includes/functions.php`:
     - `sanitize_text_field()`: For text inputs
     - `sanitize_email()`: For email addresses
     - `sanitize_textarea_field()`: For textarea inputs

4. **File Permissions**
   - Set appropriate file permissions:
     - Directories: 755
     - Files: 644
     - Sensitive files (config): 600

### 9.2 Regular Updates

1. **Code Updates**
   - Review and apply security patches regularly
   - Keep PHP version up to date (if possible on hosting)

2. **Backup Schedule**
   - Perform database backups weekly
   - Store backups in multiple locations

### 9.3 Security Headers

The site includes security headers in `config/database.php` and `.htaccess`:

- `X-Content-Type-Options: nosniff`
- `Content-Security-Policy: frame-ancestors 'self'`
- `Referrer-Policy: strict-origin-when-cross-origin`

These headers help protect against XSS attacks and clickjacking.

---

## 10. Hosting-Specific Instructions (InfinityFree)

### 10.1 Managing Files via FTP/File Manager

#### FTP Access

1. **Obtain FTP Credentials**
   - Log into InfinityFree control panel
   - Navigate to "FTP Accounts" or "File Manager"
   - Note your FTP server, username, and password

2. **Connect via FTP Client**
   - Install an FTP client (e.g., FileZilla, Cyberduck)
   - Enter FTP server, username, and password
   - Connect to access site files

3. **Navigate Site Files**
   - Root directory: `/htdocs/` or `/public_html/`
   - Key files:
     - `index.php`: Homepage
     - `products.php`: Products page
     - `data/products.json`: Product data
     - `config/database.php`: Database configuration

#### File Manager (Hosting Control Panel)

1. **Access File Manager**
   - Log into InfinityFree control panel
   - Click **"File Manager"** under the "Files" section

2. **Edit Files**
   - Navigate to the desired file
   - Right-click and select **"Edit"**
   - Make changes and click **"Save"**

3. **Upload Files**
   - Click **"Upload"** in the top menu
   - Select files from your device
   - Wait for upload to complete

### 10.2 Email Configuration

The site uses PHP's `mail()` function for emails. For better delivery:

1. **Configure SMTP** (if available)
   - Install an SMTP plugin or configure in `includes/email_notifications.php`
   - Use InfinityFree SMTP settings if provided

2. **Test Email Delivery**
   - Send test emails to verify delivery
   - Check spam folders if emails don't arrive

### 10.3 Handling .htaccess and Permalink Issues

#### .htaccess File Management

1. **Locate .htaccess**
   - File location: `/htdocs/.htaccess` (root directory)

2. **Edit .htaccess**
   - Via FTP/File Manager, open `.htaccess`
   - **Important**: Create a backup before editing
   - Make changes and save

3. **Common .htaccess Rules**
   - Security headers (already configured)
   - URL rewriting rules
   - Cache control

#### Permalink Issues

If URLs are not working correctly:

1. **Check .htaccess**
   - Ensure rewrite rules are present
   - Verify Apache mod_rewrite is enabled

2. **Test URLs**
   - Test product pages and other dynamic URLs
   - Contact hosting support if issues persist

### 10.4 Backup Procedures for InfinityFree

1. **InfinityFree Built-in Backup**
   - Log into InfinityFree control panel
   - Navigate to **"Backups"** (if available)
   - Create a full backup (database and files)

2. **Manual Backup**
   - Export database via phpMyAdmin (see Section 8.1)
   - Download files via FTP/File Manager
   - Store backups in cloud storage or external drive

3. **Backup Schedule**
   - Perform backups monthly (last day of each month)
   - Keep multiple backup versions (last 3 months)

---

## 11. Troubleshooting for Admins

### 11.1 Common Errors and Solutions

#### 500 Internal Server Error

**Possible Causes:**
- Corrupted `.htaccess` file
- PHP syntax errors
- Exhausted PHP memory limit
- Database connection issues

**Solutions:**
1. **Rename .htaccess**
   - Via FTP/File Manager, rename `.htaccess` to `.htaccess_old`
   - If site loads, create a new `.htaccess` file

2. **Check PHP Errors**
   - Review error logs in hosting control panel
   - Fix syntax errors in PHP files

3. **Increase PHP Memory Limit**
   - Add to `config/database.php` or `.htaccess`:
     ```php
     ini_set('memory_limit', '256M');
     ```

4. **Check Database Connection**
   - Verify database credentials in `config/database.php`
   - Test connection via phpMyAdmin

#### 404 Not Found Error

**Possible Causes:**
- Broken URLs
- Missing files
- Incorrect .htaccess rules

**Solutions:**
1. **Verify File Exists**
   - Check that the requested file exists in the correct location

2. **Check URLs**
   - Verify URLs match actual file paths
   - Test direct file access

3. **Review .htaccess**
   - Check rewrite rules
   - Temporarily disable .htaccess to test

#### JSON File Corruption

**Symptoms:**
- Products not loading on the site
- JSON validation errors in admin

**Solutions:**
1. **Restore from Backup**
   - Restore `products.json` from a recent backup
   - Upload via FTP/File Manager

2. **Create New products.json**
   - If no backup, create a new file with valid JSON structure:
     ```json
     []
     ```
   - Upload to `/data/products.json`

3. **Validate JSON**
   - Use the Products CRUD page to validate JSON
   - Fix syntax errors

#### Database Connection Issues

**Symptoms:**
- Site fails to load
- "Error establishing a database connection" message

**Solutions:**
1. **Check Database Credentials**
   - Verify in `config/database.php`:
     - `DB_NAME`: Database name
     - `DB_USER`: Database username
     - `DB_PASSWORD`: Database password
     - `DB_HOST`: Database host (typically `sql100.infinityfree.com`)

2. **Verify in phpMyAdmin**
   - Log into phpMyAdmin
   - Confirm database exists and credentials are correct

3. **Reset Database Password**
   - If needed, reset password in hosting control panel
   - Update `config/database.php` with new password

4. **Contact Hosting Support**
   - If credentials are correct but connection fails, contact InfinityFree support

#### FluentCRM Sync Problems

**Symptoms:**
- User data not updating
- Subscribers not appearing in Manage Subscribers page

**Solutions:**
1. **Check Database Tables**
   - Verify tables exist: `wpah_fc_subscribers`, `ac_users`, `users`
   - Check table structure in phpMyAdmin

2. **Verify Table Detection**
   - The Manage Subscribers page automatically detects which table contains subscribers
   - Check error logs if detection fails

3. **Manual Sync** (if needed)
   - Export user data from one table
   - Import into the correct table

---

## 12. Appendix

### 12.1 ERD Diagram Explanation

**Entity-Relationship Diagram Overview:**

The website uses the following key relationships:

1. **Users → Orders** (One-to-Many)
   - One user can have multiple orders
   - Linked via `user_id` in the `orders` table

2. **Users → Forum Posts** (One-to-Many)
   - One user can create multiple forum posts
   - Linked via `user_id` in the `forum_posts` table

3. **Forum Posts → Forum Replies** (One-to-Many)
   - One forum post can have multiple replies
   - Linked via `post_id` in the `forum_replies` table

4. **Orders → Order Items** (One-to-Many)
   - One order can include multiple items
   - Linked via `order_id` in the `order_items` table

5. **Products (JSON) → Order Items** (Many-to-Many via order_items)
   - Products are stored in `products.json`
   - Order items reference products via `product_id`

> **Screenshot Placeholder:** [Figure 12.1: ERD diagram showing all relationships]

### 12.2 Database Schema Details

**Key Database Tables:**

1. **users** (in `if0_37969254_wp802` database)
   - `id` (INT, PRIMARY KEY): User ID
   - `email` (VARCHAR): User email
   - `password` (VARCHAR): Hashed password
   - `first_name` (VARCHAR): First name
   - `last_name` (VARCHAR): Last name
   - `phone` (VARCHAR): Phone number
   - `role` (VARCHAR): User role (customer, admin, artisan)
   - `status` (VARCHAR): Account status

2. **orders** (in `if0_37969254_513week7` database)
   - `order_id` (INT, PRIMARY KEY): Order ID
   - `user_id` (INT, FOREIGN KEY): Links to users table
   - `order_number` (VARCHAR): Unique order number
   - `status` (VARCHAR): Order status
   - `subtotal` (DECIMAL): Subtotal
   - `tax_amount` (DECIMAL): Tax amount
   - `shipping_amount` (DECIMAL): Shipping cost
   - `total_amount` (DECIMAL): Final total
   - `payment_status` (VARCHAR): Payment status
   - `created_at` (DATETIME): Order date

3. **order_items**
   - `item_id` (INT, PRIMARY KEY): Item ID
   - `order_id` (INT, FOREIGN KEY): Links to orders
   - `product_id` (INT): Product ID from products.json
   - `quantity` (INT): Quantity
   - `price` (DECIMAL): Price per item

4. **forum_posts**
   - `post_id` (INT, PRIMARY KEY): Post ID
   - `user_id` (INT, FOREIGN KEY): Links to users
   - `title` (VARCHAR): Post title
   - `content` (TEXT): Post content
   - `category` (VARCHAR): Post category
   - `created_at` (DATETIME): Post date

5. **forum_replies**
   - `reply_id` (INT, PRIMARY KEY): Reply ID
   - `post_id` (INT, FOREIGN KEY): Links to forum_posts
   - `user_id` (INT, FOREIGN KEY): Links to users
   - `content` (TEXT): Reply content
   - `created_at` (DATETIME): Reply date

### 12.3 File Structure

**Key Directories and Files:**

```
513week7/
├── admin/
│   ├── products-crud.php       # Product management
│   ├── manage-subscribers.php  # Subscriber management
│   └── registered-users.php    # User list
├── auth/
│   ├── login.php               # Login page
│   ├── register.php            # Registration page
│   └── reset-password.php      # Password reset
├── cart/
│   ├── index.php               # Shopping cart
│   └── checkout.php            # Checkout page
├── config/
│   └── database.php            # Database configuration
├── data/
│   └── products.json           # Product data (JSON)
├── includes/
│   ├── functions.php           # Utility functions
│   ├── header.php              # Site header
│   └── footer.php              # Site footer
├── user/
│   ├── orders.php              # Order history
│   └── profile.php             # User profile
├── forum.php                   # Forum page
├── products.php                # Products page
├── feedback.php                # Feedback form
├── recruitment.php             # Recruitment page
└── index.php                   # Homepage
```

### 12.4 Configuration Constants

Key constants defined in `config/database.php`:

- `SITE_NAME`: "Dawn ArtisanCraft Marketplace"
- `SITE_URL`: "https://dawn1.infinityfreeapp.com/513week7"
- `CURRENCY`: "USD"
- `CURRENCY_SYMBOL`: "$"
- `TAX_RATE`: 0.08 (8%)
- `SHIPPING_RATE`: 9.99
- `FREE_SHIPPING_THRESHOLD`: 75.00
- `ADMIN_EMAIL`: "admin@artisancraft.com"
- `SUPPORT_EMAIL`: "support@artisancraft.com"

---

**End of Admin Manual**

