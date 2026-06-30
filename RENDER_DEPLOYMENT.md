# Laravel Deployment Guide for Render.com

This guide covers deploying your Laravel application to Render.com using Docker.

---

## Prerequisites

- Render.com account (free tier available)
- GitHub/GitLab repository with your Laravel code
- MySQL database (use external service like PlanetScale, or upgrade Render plan)

---

## Required Environment Variables

Configure these in Render Dashboard → Web Service → Environment:

### Core Laravel Settings
```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

# Generate APP_KEY with: php artisan key:generate --show
```

### Database Configuration (MySQL)
```env
DB_CONNECTION=mysql
DB_HOST=your-mysql-host.example.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password
```

**MySQL Options:**
- **PlanetScale** (Free tier): https://planetscale.com
- **Railway** (Free tier): https://railway.app
- **Render MySQL** (Paid): $7/month

### Session & Cache
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Mail Configuration (Optional)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Filesystem (Optional - if using S3)
```env
FILESYSTEM_DISK=local
# For S3: Set to 's3' and configure AWS credentials
```

---

## Deployment Steps

### 1. Create MySQL Database

**Option A: PlanetScale (Recommended for free tier)**
```bash
# Visit https://planetscale.com
# Create free database
# Get connection string and set DB_* env vars
```

**Option B: Railway**
```bash
# Visit https://railway.app
# Create MySQL service
# Copy credentials to Render env vars
```

### 2. Prepare Repository

Ensure these files are in your repository root:
```
your-laravel-app/
├── Dockerfile
├── .dockerignore
├── docker/
│   └── entrypoint.sh
└── ... (other Laravel files)
```

Commit and push:
```bash
git add Dockerfile .dockerignore docker/
git commit -m "Add Docker configuration for Render deployment"
git push origin main
```

### 3. Create Render Web Service

1. Go to https://dashboard.render.com
2. Click **New +** → **Web Service**
3. Connect your GitHub/GitLab repository
4. Configure:
   - **Name**: your-app-name
   - **Environment**: Docker
   - **Region**: Choose closest to your users
   - **Branch**: main
   - **Dockerfile Path**: ./Dockerfile (default)
   - **Instance Type**: Free

### 4. Configure Environment Variables

In Render Dashboard → Environment tab, add all variables from the "Required Environment Variables" section above.

**CRITICAL**: Set `APP_KEY` or your app will fail to start:
```bash
# Generate locally:
php artisan key:generate --show

# Copy output (includes 'base64:' prefix)
# Example: base64:8dKj3mL9nP2qR5sT7vW8xY1zA3bC4dE5fG6hI7jK8lM=
```

### 5. Deploy

1. Click **Create Web Service**
2. Render will automatically:
   - Clone your repository
   - Build Docker image (takes 5-10 minutes first time)
   - Run database migrations
   - Start Laravel application
3. Monitor build logs for errors
4. Access your app at: `https://your-app-name.onrender.com`

---

## Common Errors & Solutions

### ❌ Error: "Vite manifest not found"
**Cause**: Frontend assets not built during Docker build

**Solution**: Check these in Dockerfile:
```dockerfile
# Ensure this stage exists
FROM node:20-alpine AS node-builder
RUN npm run build

# Ensure this line exists in final stage
COPY --from=node-builder /app/public/build ./public/build
```

### ❌ Error: "Database connection failed"
**Cause**: Wrong DB credentials or database not accessible

**Solution**:
1. Verify DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
2. Check database is running and accessible from internet
3. For PlanetScale: Use SSL connection settings
4. Check entrypoint.sh logs for connection attempts

### ❌ Error: "No application encryption key"
**Cause**: Missing or invalid APP_KEY

**Solution**:
```bash
# Generate key locally
php artisan key:generate --show

# Copy output to Render env var APP_KEY
# Must include 'base64:' prefix
```

### ❌ Error: "Permission denied" for storage/logs
**Cause**: Incorrect file permissions in Docker image

**Solution**: Verify Dockerfile has:
```dockerfile
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage
```

### ❌ Error: "Class 'X' not found"
**Cause**: Composer dependencies not installed or autoload not optimized

**Solution**: Check Dockerfile:
```dockerfile
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache
```

### ❌ Build timeout / Image too large
**Cause**: Docker image exceeds free tier limits or build takes too long

**Solution**:
1. Check .dockerignore includes node_modules, vendor, .git
2. Use multi-stage build (already configured)
3. Remove unnecessary dependencies
4. Consider upgrading to Render paid tier

---

## Performance Tips

### 1. Enable OPcache
Already configured in Dockerfile:
```dockerfile
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini
```

### 2. Cache Laravel Config
Automatically done during build:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Optimize Composer Autoloader
Already configured:
```bash
composer install --optimize-autoloader
```

### 4. Use CDN for Assets
Consider using Cloudflare or similar CDN for static assets.

---

## Monitoring & Logs

View application logs in Render Dashboard:
1. Go to your web service
2. Click **Logs** tab
3. Filter by:
   - Build logs: Docker build process
   - Deploy logs: Application startup
   - Runtime logs: Laravel application logs

Laravel logs location inside container:
```
/var/www/html/storage/logs/laravel.log
```

---

## Scaling Considerations

**Free Tier Limitations:**
- Service spins down after 15 minutes of inactivity
- First request after spin-down takes 30-60 seconds (cold start)
- Limited RAM (512MB) and CPU

**Upgrading to Paid ($7/month):**
- Always-on service (no cold starts)
- More RAM and CPU
- Better performance for concurrent users

---

## Support

If you encounter issues:
1. Check Render build/deploy logs
2. Review this troubleshooting guide
3. Check Laravel logs in storage/logs/
4. Render documentation: https://render.com/docs

---

**Deployment complete! Your Laravel app should be live at:**
`https://your-app-name.onrender.com`
