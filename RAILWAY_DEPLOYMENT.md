# Railway Deployment Guide

## Required Environment Variables

Set these in Railway dashboard:

```
APP_NAME=LedeCore
APP_ENV=production
APP_KEY=(generate with: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=(provided by Railway)
DB_PORT=3306
DB_DATABASE=(provided by Railway)
DB_USERNAME=(provided by Railway)
DB_PASSWORD=(provided by Railway)

SESSION_DRIVER=database
SESSION_LIFETIME=120
```

## Build Process

Railway will automatically:
1. Install PHP and Node.js dependencies
2. Run `npm run build` to compile Vite assets
3. Create storage symlink
4. Cache config, routes, and views
5. Start the application

## Important Notes

- `/public/build` is in .gitignore (correct - built on Railway)
- Vite assets are compiled during build phase
- Storage link is created automatically
- All caches are generated for performance

## Troubleshooting

If CSS/Tailwind doesn't load:
1. Check Railway build logs for `npm run build` success
2. Verify `public/build` directory exists after build
3. Check `APP_URL` matches your Railway domain
4. Ensure `APP_ENV=production` is set

