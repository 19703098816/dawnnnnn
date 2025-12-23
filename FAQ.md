# Frequently Asked Questions (FAQ)
## Dawn's ArtisanCraft Marketplace

**Version:** 1.0  
**Last Updated:** 2024-12-19  
**Website:** https://dawn1.infinityfreeapp.com/513week7

---

## Table of Contents

- [User FAQs](#user-faqs)
  - [Account & Login](#account--login)
  - [Shopping & Cart](#shopping--cart)
  - [Orders & Checkout](#orders--checkout)
  - [Forum](#forum)
  - [General](#general)
- [Admin FAQs](#admin-faqs)
  - [Product Management](#product-management)
  - [User Management](#user-management)
  - [Order Management](#order-management)
  - [Technical Issues](#technical-issues)

---

## User FAQs

### Account & Login

**Q: How do I create an account?**  
A: Click "Login" in the top-right corner, then click "Register". Fill out the registration form with your name, email, and password. After submitting, check your email for a verification link and click it to activate your account.

**Q: I forgot my password. How do I reset it?**  
A: On the login page, click "Forgot Password?" and enter your registered email address. Check your email for a password reset link, click it, and follow the instructions to create a new password.

**Q: Why can't I log in even though I'm sure my password is correct?**  
A: Check that Caps Lock is disabled and there are no extra spaces in your email or password. If the issue persists, use the "Forgot Password?" feature to reset your password. If your account is locked after multiple failed attempts, wait 1 hour or contact support.

**Q: How do I change my email address?**  
A: Log into your account, go to "My Profile", update your email address, and save changes. You may need to verify the new email address.

**Q: Can I have multiple accounts with the same email?**  
A: No, each email address can only be associated with one account. If you need to create a new account, use a different email address.

---

### Shopping & Cart

**Q: How do I add a product to my cart?**  
A: Browse to a product's details page, select the quantity you want, and click the "Add to Cart" button. You'll see a confirmation message, and the cart icon will update with the number of items.

**Q: Why isn't my item adding to the cart?**  
A: Ensure you're logged into your account (some features require login). Clear your browser cache and cookies, then try again. If the issue persists, contact support.

**Q: How do I remove an item from my cart?**  
A: Go to your cart page, click the trash icon (🗑️) next to the item you want to remove, and confirm the deletion.

**Q: Can I change the quantity of items in my cart?**  
A: Yes, on the cart page, use the + and - buttons next to each item to adjust the quantity. Changes are saved automatically.

**Q: Why do the prices in my cart look different from the product page?**  
A: The cart shows both the original price (crossed out) and the discounted price (if applicable), just like the product page. The final price includes any discounts. Check the "Price Verification" table at the top of the cart page to see the exact calculations.

**Q: What is the free shipping threshold?**  
A: Orders over $75.00 qualify for free shipping. Orders under $75.00 have a $9.99 shipping fee.

---

### Orders & Checkout

**Q: How do I change my shipping address after placing an order?**  
A: Once an order is placed, contact support at support@artisancraft.com with your order number to request an address change. Changes may not be possible if the order has already shipped.

**Q: Can I cancel an order?**  
A: Contact support immediately with your order number to request cancellation. Orders that have already been shipped cannot be cancelled, but you may be able to return the items.

**Q: How do I track my order?**  
A: Log into your account, go to "My Orders", and click on the order number to view order details and tracking information (if available).

**Q: What payment methods are accepted?**  
A: The site currently uses a test payment system for demonstration purposes. For actual purchases, contact support for available payment options.

**Q: Will I receive an order confirmation email?**  
A: Yes, after placing an order, you'll receive a confirmation email at your registered email address. Check your spam folder if you don't see it.

**Q: How long does shipping take?**  
A: Shipping times vary by location and product. Check your order confirmation email or order details page for estimated delivery dates.

---

### Forum

**Q: Why isn't my forum post showing up?**  
A: Some posts may require moderation before being published. Wait up to 24 hours for approval. If your post still doesn't appear, contact support.

**Q: Can I edit or delete my forum posts?**  
A: You can edit or delete your own posts if they don't have multiple replies. Click the "Edit" or "Delete" button next to your post (if available).

**Q: Why can't I create a forum post?**  
A: Ensure you're logged into your account (forum requires login). Check that your post title and content are not empty, and that your post doesn't contain spam or offensive language.

**Q: How do I reply to a forum post?**  
A: Click on the forum post title to view the full discussion, scroll to the bottom, enter your reply in the text box, and click "Post Reply".

**Q: What are the forum rules?**  
A: Be respectful, stay on-topic, don't share personal information, avoid spam and advertising, and use appropriate language. See the User Manual Section 3.4 for complete forum etiquette guidelines.

---

### General

**Q: How do I contact customer support?**  
A: Use the feedback form on the Contact page, or email support@artisancraft.com. We aim to respond within 24 business hours.

**Q: How do I submit feedback about the website?**  
A: Navigate to the Contact or Feedback page, fill out the feedback form with your name, email, subject, and message. You can also upload a file if needed. Click "Submit Feedback" to send.

**Q: How do I apply for a job?**  
A: Go to the Recruitment page, browse available positions, click "Apply Now" on a job posting, fill out the application form, upload your CV, and click "Submit Application".

**Q: What browsers are supported?**  
A: The website works best with Chrome 90+, Firefox 88+, Safari 14+, or Edge 90+. JavaScript and cookies must be enabled.

**Q: How do I clear my browser cache?**  
A: See the User Manual Section 7.4 for step-by-step instructions for Chrome, Firefox, Safari, and Edge. Clearing cache can resolve many website issues.

---

## Admin FAQs

### Product Management

**Q: How do I add a new product?**  
A: Log into the admin panel, go to Products CRUD page (`/admin/products-crud.php`), click "Add New Product", fill out all required fields (name, price, description, category, etc.), upload an image if needed, and click "Save Product".

**Q: Where is the products.json file located?**  
A: The file is located at `/data/products.json` relative to the site root. You can edit it via the Products CRUD admin page (recommended) or directly via FTP/File Manager.

**Q: How do I restore a deleted product?**  
A: If you have a backup of `products.json`, restore it via FTP/File Manager. If no backup exists, you'll need to recreate the product manually through the Products CRUD page.

**Q: Why are products not showing on the website?**  
A: Check that `products.json` exists and is in valid JSON format. Use the Products CRUD page to validate the JSON. If the file is corrupted, restore from backup or create a new file with `[]`.

**Q: How do I fix a corrupted products.json file?**  
A: Restore from a recent backup via FTP/File Manager. If no backup exists, create a new `products.json` file with `[]` and add products through the Products CRUD page.

**Q: Can I bulk import products?**  
A: Currently, products must be added individually through the Products CRUD page. For bulk imports, you can manually edit `products.json` via FTP, but ensure the JSON syntax is valid.

**Q: What image formats are supported for products?**  
A: JPEG, PNG, and GIF formats are supported. Upload images via the Products CRUD page, or provide image URLs in the `image_url` field.

---

### User Management

**Q: How do I update a subscriber's status?**  
A: Go to Manage Subscribers page (`/admin/manage-subscribers.php`), find the subscriber, select a new status from the dropdown (Active, Pending, Bounced, Complained, Unsubscribed), and click "Update".

**Q: Why are subscribers not showing in the Manage Subscribers page?**  
A: The page checks multiple database tables (`wpah_fc_subscribers`, `ac_users`, `users`). Verify which table contains your subscribers in phpMyAdmin. The page should automatically detect the correct table.

**Q: How do I export customer data?**  
A: Access phpMyAdmin, select the appropriate database (`if0_37969254_wp802` for users), select the users/subscribers table, click "Export", choose "SQL" format, and download the file.

**Q: Can I delete a user account?**  
A: Yes, via phpMyAdmin. Access the `users` table in the `if0_37969254_wp802` database, find the user, and delete the row. **Warning**: This will also delete associated orders and forum posts.

**Q: Why is the subscriber status update failing?**  
A: Ensure the subscriber exists in one of the checked tables (`wpah_fc_subscribers`, `ac_users`, `users`). Check error logs for specific error messages. Verify database connection in `config/database.php`.

---

### Order Management

**Q: How do I view all orders?**  
A: Access phpMyAdmin, select the `if0_37969254_513week7` database, and open the `orders` table. You'll see all orders with details like order number, user ID, total amount, status, and date.

**Q: How do I update an order status?**  
A: In phpMyAdmin, open the `orders` table, find the order by `order_id` or `order_number`, edit the `status` field (Pending, Processing, Shipped, Delivered, Cancelled, Refunded), and save changes.

**Q: Why are orders not syncing to the orders table?**  
A: Check that the `orders` table exists in the `if0_37969254_513week7` database. The table is created automatically during checkout, but if it's missing, create it manually or run the checkout process again.

**Q: How do I find orders for a specific customer?**  
A: In phpMyAdmin, use SQL query:
```sql
SELECT * FROM orders WHERE user_id = [USER_ID];
```
Replace `[USER_ID]` with the customer's user ID.

**Q: Can I refund an order?**  
A: Update the order status to "Refunded" in the `orders` table. For actual payment processing, you'll need to handle refunds through your payment gateway separately.

**Q: How do I view order items?**  
A: Open the `order_items` table in phpMyAdmin. Filter by `order_id` to see all items for a specific order:
```sql
SELECT * FROM order_items WHERE order_id = [ORDER_ID];
```

---

### Technical Issues

**Q: How do I fix a 500 Internal Server Error?**  
A: 1) Rename `.htaccess` to `.htaccess_old` via FTP. 2) Check PHP error logs in hosting control panel. 3) Increase PHP memory limit in `config/database.php`. 4) Verify database connection. See Admin Manual Section 11.1 for detailed steps.

**Q: How do I fix a 404 Not Found Error?**  
A: Verify the file exists in the correct location. Check URLs match actual file paths. Review `.htaccess` rewrite rules. Temporarily disable `.htaccess` to test. See Admin Manual Section 11.1.

**Q: Why is the database connection failing?**  
A: Check database credentials in `config/database.php` (DB_NAME, DB_USER, DB_PASSWORD, DB_HOST). Verify credentials in phpMyAdmin. Reset database password if needed and update `config/database.php`. Contact hosting support if credentials are correct but connection fails.

**Q: How do I backup the database?**  
A: Access phpMyAdmin, select your database (`if0_37969254_513week7` or `if0_37969254_wp802`), click "Export" tab, select "Quick" method and "SQL" format, click "Go", and download the SQL file. Store backups securely.

**Q: How do I restore from a backup?**  
A: In phpMyAdmin, select your database, click "Import" tab, choose the SQL backup file, and click "Go". **Warning**: This will overwrite existing data.

**Q: Why are FluentCRM subscribers not syncing?**  
A: Verify the subscriber tables exist (`wpah_fc_subscribers`, `ac_users`, `users`). Check table structure in phpMyAdmin. The Manage Subscribers page automatically detects which table contains subscribers. Check error logs for sync issues.

**Q: How do I increase PHP memory limit?**  
A: Add to `config/database.php`:
```php
ini_set('memory_limit', '256M');
```
Or add to `.htaccess`:
```
php_value memory_limit 256M
```

**Q: How do I fix broken permalinks/URLs?**  
A: Check `.htaccess` file for rewrite rules. Ensure Apache mod_rewrite is enabled. Verify file paths match URLs. Contact hosting support if issues persist.

**Q: How do I access error logs?**  
A: Log into your InfinityFree control panel, navigate to "Logs" or "Error Logs" section. Review logs for PHP errors, database connection issues, and other problems.

**Q: The site is loading slowly. How do I optimize it?**  
A: Optimize images (compress before uploading), enable browser caching via `.htaccess`, minimize database queries, and use a CDN if available. Test site speed with Google PageSpeed Insights.

---

## Still Have Questions?

- **User Support**: Email support@artisancraft.com or use the feedback form
- **Admin Support**: Review the Admin Manual for detailed troubleshooting guides
- **Response Time**: We aim to respond within 24 business hours

---

**For more detailed information, refer to the User Manual and Admin Manual.**

