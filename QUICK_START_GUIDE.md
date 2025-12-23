# Quick Start Guide
## Dawn's ArtisanCraft Marketplace

**Version:** 1.0  
**Last Updated:** 2024-12-19  
**Website:** https://dawn1.infinityfreeapp.com/513week7

---

## For Users

### 🔐 Getting Started

- **Website URL**: https://dawn1.infinityfreeapp.com/513week7
- **Register**: Click "Login" → "Register" → Fill form → Verify email
- **Login**: Click "Login" → Enter email & password → Click "Sign In"
- **Forgot Password**: Click "Forgot Password?" → Enter email → Check email → Reset password

### 🛍️ Shopping

- **Browse Products**: Click "Products" in menu → Select category → Click "Details" on any product
- **Add to Cart**: On product page → Select quantity → Click "Add to Cart"
- **View Cart**: Click cart icon (top-right) → Review items → Edit quantities or remove items
- **Checkout**: Click "Proceed to Checkout" → Enter shipping/billing address → Enter payment details → Click "Place Order"

### 💬 Forum

- **Create Post**: Click "Forum" → Click "Create New Post" → Enter title & content → Click "Submit Post"
- **Reply to Post**: Click on a post → Scroll to bottom → Enter reply → Click "Post Reply"

### 👤 Account Management

- **View Orders**: Click username → "My Orders" → View order history
- **Update Profile**: Click username → "My Profile" → Edit details → Click "Save Changes"
- **Change Password**: Go to "My Profile" → Enter current & new password → Click "Change Password"

### 📞 Support

- **Submit Feedback**: Click "Contact" → Fill feedback form → Click "Submit Feedback"
- **Upload CV**: Click "Recruitment" → Select job → Fill application form → Upload CV → Click "Submit Application"

---

## For Admins

### 🔑 Admin Access

- **Products CRUD**: https://dawn1.infinityfreeapp.com/513week7/admin/products-crud.php
- **Manage Subscribers**: https://dawn1.infinityfreeapp.com/513week7/admin/manage-subscribers.php
- **Login Required**: Must be logged in as admin to access admin pages

### 📦 Product Management

- **Add Product**: Products CRUD page → "Add New Product" → Fill form → Upload image → Click "Save Product"
- **Edit Product**: Products CRUD page → Find product → Click "Edit" → Modify fields → Click "Update Product"
- **Delete Product**: Products CRUD page → Find product → Click "Delete" → Confirm deletion
- **Product File**: `/data/products.json` (edit via admin page or FTP)

### 👥 User Management

- **View Subscribers**: Manage Subscribers page → View table of all users
- **Update Status**: Find subscriber → Select new status from dropdown → Click "Update"
- **Status Options**: Active, Pending, Bounced, Complained, Unsubscribed

### 📋 Order Management

- **View Orders**: Access phpMyAdmin → Select `if0_37969254_513week7` database → Open `orders` table
- **Update Status**: Edit `status` field in `orders` table → Save changes
- **Status Options**: Pending, Processing, Shipped, Delivered, Cancelled, Refunded

### 🗣️ Forum Moderation

- **View Posts**: Access phpMyAdmin → Open `forum_posts` table
- **Edit Post**: Click "Edit" on post row → Modify content → Save
- **Delete Post**: Click "Delete" on post row → Confirm
- **Manage Replies**: Open `forum_replies` table → Edit or delete as needed

### 💾 Database Backup

- **Via phpMyAdmin**: Select database → "Export" tab → "Quick" method → "SQL" format → Click "Go"
- **Backup Schedule**: Monthly (last day of month)
- **Storage**: Save to external drive or cloud storage

### 🔧 Troubleshooting

- **500 Error**: Rename `.htaccess` to `.htaccess_old` → Check PHP errors → Increase memory limit
- **404 Error**: Verify file exists → Check URLs → Review `.htaccess` rules
- **JSON Corruption**: Restore from backup → Or create new `products.json` with `[]`
- **Database Connection**: Check credentials in `config/database.php` → Verify in phpMyAdmin

---

## Quick Reference URLs

### User Pages
- Homepage: https://dawn1.infinityfreeapp.com/513week7
- Products: https://dawn1.infinityfreeapp.com/513week7/products.php
- Forum: https://dawn1.infinityfreeapp.com/513week7/forum.php
- Cart: https://dawn1.infinityfreeapp.com/513week7/cart/index.php
- My Orders: https://dawn1.infinityfreeapp.com/513week7/user/orders.php
- Feedback: https://dawn1.infinityfreeapp.com/513week7/feedback.php

### Admin Pages
- Products CRUD: https://dawn1.infinityfreeapp.com/513week7/admin/products-crud.php
- Manage Subscribers: https://dawn1.infinityfreeapp.com/513week7/admin/manage-subscribers.php

### Support
- Email: support@artisancraft.com
- Response Time: Within 24 business hours

---

**For detailed instructions, see the User Manual and Admin Manual.**

