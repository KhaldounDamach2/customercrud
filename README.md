# Customer CRUD Application

A modern PHP-based Customer Management System with email notifications, built with Docker and deployed via CI/CD.

## 🚀 Features

- **Customer Management**: Create, Read, Update, Delete customers
- **Email Notifications**: Automated welcome emails via SendGrid
- **Dockerized**: Production-ready containerization
- **Secure**: Environment-based configuration, no secrets in code
- **Responsive**: Bootstrap-powered UI

## 📋 Prerequisites

- Docker & Docker Compose
- SendGrid API account
- Git

## 🛠️ Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/KhaldounDamach2/customercrud.git
cd customercrud
```

### 2. Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# Edit .env and add your SendGrid API key
nano .env
```

### 3. Configure SendGrid
1. Sign up at [SendGrid](https://sendgrid.com/)
2. Create an API key with "Mail Send" permissions
3. Add your API key to `.env`:
   ```
   SENDGRID_API_KEY=SG.your-actual-sendgrid-api-key-here
   ```
4. Verify your sender email in SendGrid dashboard

### 4. Start the application
```bash
# Build and start containers
docker-compose up -d

# Check status
docker-compose ps
```

### 5. Access the application
- **Web Application**: http://localhost:8080
- **Database**: localhost:3307 (if needed)

## 🏗️ Architecture

```
┌─────────────────┐     ┌─────────────────┐
│   Web Container │────▶│   DB Container  │
│   (PHP/Apache)  │     │     (MySQL)     │
│   Port: 8080    │     │   Port: 3307    │
└─────────────────┘     └─────────────────┘
         │
         ▼
┌─────────────────┐
│   SendGrid API  │
│ (Email Service) │
└─────────────────┘
```

## 🔧 Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `DB_HOST` | Database hostname | `db` |
| `DB_NAME` | Database name | `customerdb` |
| `DB_USER` | Database user | `customeruser` |
| `DB_PASSWORD` | Database password | `customerpass` |
| `SENDGRID_API_KEY` | SendGrid API key | `SG.xxx...` |

## 🐳 Docker Commands

```bash
# Start services
docker-compose up -d

# View logs
docker-compose logs -f web
docker-compose logs -f db

# Rebuild after code changes
docker-compose up -d --build

# Stop services
docker-compose down

# Remove everything (including volumes)
docker-compose down -v
```

## 🚀 Deployment

### GitHub Actions CI/CD
This project includes automated deployment via GitHub Actions:

1. **Push to main** triggers the workflow
2. **Docker image** is built and pushed to registry
3. **Deployment** to production server (configure secrets)

### Manual Deployment
```bash
# On your server
git pull origin main
docker-compose up -d --build
```

## 🔒 Security Features

- Environment-based configuration
- No secrets in source code
- Input validation and sanitization
- Duplicate email prevention
- Session-based data transfer
- Docker security best practices

## 🧪 Development

### Local Development
```bash
# Watch logs
docker-compose logs -f

# Access containers
docker-compose exec web bash
docker-compose exec db mysql -u root -p
```

### Database Management
```bash
# Access MySQL
docker-compose exec db mysql -u customeruser -p customerdb

# View tables
SHOW TABLES;
DESCRIBE customers;
```

## 📝 API Endpoints

- `GET /` - Customer list
- `GET /create_customer.php` - Create customer form
- `POST /create_customer.php` - Submit new customer
- `GET /update_customer.php?id=X` - Update customer form
- `POST /update_customer.php` - Submit customer updates
- `GET /delete_customer.php?id=X` - Delete customer

## 🐛 Troubleshooting

### Common Issues

**Port conflicts:**
```bash
# If port 3306 is busy
docker-compose down
# Ports are configured as 3307:3306 to avoid XAMPP conflicts
```

**SendGrid errors:**
- Verify API key is correct
- Check sender email is verified in SendGrid
- Review container logs: `docker-compose logs web`

**Database connection:**
```bash
# Check database health
docker-compose exec db mysqladmin ping -h localhost
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

For support, email your-email@domain.com or create an issue on GitHub.