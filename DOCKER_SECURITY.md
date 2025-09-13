# Docker Security & Secrets Management

## Overview
This document outlines the security practices for Docker files and environment management in this Laravel application.

## Environment Files

### Sensitive Files (Ignored by Git)
These files contain actual secrets and credentials and should **NEVER** be committed to git:

- `.env` - Main Laravel environment file
- `.env.docker` - Docker-specific environment variables
- `.env.external` - External database configuration
- `.env.production` - Production environment settings
- `.env.staging` - Staging environment settings
- `.env.local` - Local development overrides

### Example Files (Tracked in Git)
These files serve as templates and contain placeholder values:

- `.env.example` - Standard Laravel example
- `.env.docker.example` - Docker environment template
- `.env.external.example` - External database template
- `.env.production.example` - Production environment template

## Docker Files

### Potentially Sensitive Docker Files (Ignored)
- `docker-compose.override.yml` - Local overrides with secrets
- `docker-compose.prod.yml` - Production compose with credentials
- `docker-compose.staging.yml` - Staging compose with credentials
- `docker-compose.local.yml` - Local development with secrets

### Safe Docker Files (Tracked)
- `docker-compose.yml` - Base configuration without secrets
- `Dockerfile` - Application container definition
- `.dockerignore` - Docker build exclusions

## Setup Instructions

### For New Developers

1. **Copy example files:**
   ```bash
   cp .env.example .env
   cp .env.docker.example .env.docker
   cp .env.external.example .env.external
   ```

2. **Update with actual values:**
   - Generate APP_KEY: `php artisan key:generate`
   - Set database credentials
   - Configure external services

3. **Never commit sensitive files:**
   ```bash
   # These should show "ignored by .gitignore"
   git check-ignore .env.docker .env.external
   ```

### For Production Deployment

1. **Use environment-specific files:**
   ```bash
   cp .env.production.example .env.production
   # Edit with production values
   ```

2. **Use Docker secrets or external secret management:**
   ```bash
   # For production, consider using:
   # - Docker Swarm secrets
   # - Kubernetes secrets
   # - AWS Secrets Manager
   # - Azure Key Vault
   # - HashiCorp Vault
   ```

## Security Best Practices

### Environment Variables
- ✅ Use strong, unique passwords
- ✅ Rotate secrets regularly
- ✅ Use different secrets per environment
- ❌ Never hardcode secrets in Dockerfiles
- ❌ Never commit `.env` files with real values

### Docker Compose
- ✅ Use environment file references: `env_file: .env.production`
- ✅ Use Docker secrets for sensitive data
- ✅ Keep base `docker-compose.yml` generic
- ❌ Don't put credentials directly in compose files

### CI/CD Pipeline
```yaml
# Example GitHub Actions setup
env:
  APP_KEY: ${{ secrets.APP_KEY }}
  DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
```

## Files Protected by .gitignore

### Environment Files
```gitignore
.env
.env.local
.env.production
.env.staging
.env.docker
.env.external
.env.*.local
!.env.example
!.env.*.example
```

### Docker Files with Secrets
```gitignore
docker-compose.override.yml
docker-compose.prod.yml
docker-compose.production.yml
docker-compose.staging.yml
docker-compose.local.yml
docker-compose.*.yml
!docker-compose.yml
!docker-compose.example.yml
```

### Other Sensitive Files
```gitignore
*.sql
*.dump
*.backup
*.pem
*.key
*.crt
*.cert
config/secrets.php
config/credentials.php
auth.json
```

## Emergency Response

If sensitive data was accidentally committed:

1. **Immediately rotate all exposed secrets**
2. **Remove from git history:**
   ```bash
   git filter-branch --force --index-filter \
   'git rm --cached --ignore-unmatch .env.docker' \
   --prune-empty --tag-name-filter cat -- --all
   ```
3. **Force push (if safe to do so):**
   ```bash
   git push origin --force --all
   ```
4. **Update all environments with new secrets**

## Contact
For security concerns or questions about secret management, contact the development team.