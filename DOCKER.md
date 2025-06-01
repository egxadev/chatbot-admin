# Docker Setup for Laravel 12 + React Inertia

This project uses Docker for development and production environments. The setup includes:
- PHP 8.2 with FPM
- Nginx web server
- MySQL 8.0 database
- Redis for caching and queue
- Laravel Reverb for WebSocket
- Queue worker service

## Prerequisites

- Docker
- Docker Compose

## Directory Structure

```
.
├── docker/
│   ├── nginx/
│   │   └── conf.d/
│   │       └── app.conf
│   └── php/
│       └── Dockerfile
└── docker-compose.yml
```

## Getting Started

1. Make sure your `.env` file has the correct configuration for Docker:
   ```
   DB_HOST=mysql
   REDIS_HOST=redis
   REVERB_HOST=localhost
   ```

2. Build and start the containers:
```bash
docker-compose up -d --build
```

3. Install dependencies:
```bash
docker-compose exec app composer install
docker-compose exec app npm install
```

4. Generate application key:
```bash
docker-compose exec app php artisan key:generate
```

5. Run migrations:
```bash
docker-compose exec app php artisan migrate
```

6. Build assets:
```bash
docker-compose exec app npm run build
```

## Services

- **App**: PHP-FPM service running Laravel application
- **Nginx**: Web server
- **MySQL**: Database server
- **Redis**: Cache and queue server
- **Reverb**: WebSocket server
- **Queue**: Queue worker service

## Ports

- Web server: `http://localhost:80`
- MySQL: `localhost:3306`
- Redis: `localhost:6379`
- Reverb WebSocket: `ws://localhost:8080`

## Commands

- Start containers:
```bash
docker-compose up -d
```

- Stop containers:
```bash
docker-compose down
```

- View logs:
```bash
docker-compose logs -f
```

- Execute commands in app container:
```bash
docker-compose exec app <command>
```

## Development

For development, you can run:
```bash
docker-compose exec app npm run dev
```

## Production

For production deployment:
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Build assets:
```bash
docker-compose exec app npm run build
```

## Maintenance

- To clear cache:
```bash
docker-compose exec app php artisan cache:clear
```

- To restart queue worker:
```bash
docker-compose restart queue
```

- To restart WebSocket server:
```bash
docker-compose restart reverb
```

## Security Notes

1. Change default passwords in production
2. Use strong encryption keys
3. Enable SSL/TLS in production
4. Set appropriate file permissions
5. Use production-ready Redis and MySQL configurations 