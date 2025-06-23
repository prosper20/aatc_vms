#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

print_status "Starting AATC VMS Docker setup..."

# Check if Docker is installed
print_status "Checking if Docker is installed..."
if command_exists docker; then
    print_success "Docker is installed"
    docker --version
else
    print_error "Docker is not installed. Please install Docker first."
    print_status "Visit: https://docs.docker.com/get-docker/"
    exit 1
fi

# Check if Docker daemon is running
print_status "Checking if Docker daemon is running..."
if docker info >/dev/null 2>&1; then
    print_success "Docker daemon is running"
else
    print_error "Docker daemon is not running. Please start Docker first."
    exit 1
fi

# Check if .env file exists
if [ ! -f .env ]; then
    print_warning ".env file not found. Creating from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        print_success ".env file created from .env.example"
    else
        print_error ".env.example file not found. Please create .env file manually."
        exit 1
    fi
fi

# Update .env file for Docker setup
print_status "Updating .env file for Docker setup..."
if grep -q "DB_HOST=127.0.0.1" .env; then
    sed -i.bak 's/DB_HOST=127.0.0.1/DB_HOST=host.docker.internal/' .env
    print_success "Updated DB_HOST to host.docker.internal for Docker"
elif grep -q "DB_HOST=localhost" .env; then
    sed -i.bak 's/DB_HOST=localhost/DB_HOST=host.docker.internal/' .env
    print_success "Updated DB_HOST to host.docker.internal for Docker"
fi

# Stop any running containers
print_status "Stopping any existing containers..."
docker compose down

# Build and start containers
print_status "Building and starting Docker containers..."
if docker compose up --build -d; then
    print_success "Docker containers started successfully"
else
    print_error "Failed to start Docker containers"
    exit 1
fi

# Wait for container to be ready
print_status "Waiting for container to be ready..."
sleep 10

# Run Laravel artisan commands
print_status "Running Laravel artisan commands..."

# Run migrations
print_status "Running database migrations..."
if docker compose exec laravel-app php artisan migrate --force; then
    print_success "Database migrations completed"
else
    print_warning "Database migrations failed. Please check your database connection."
fi

# Cache configuration
print_status "Caching configuration..."
if docker compose exec laravel-app php artisan config:cache; then
    print_success "Configuration cached"
else
    print_warning "Configuration caching failed"
fi

# Cache routes
print_status "Caching routes..."
if docker compose exec laravel-app php artisan route:cache; then
    print_success "Routes cached"
else
    print_warning "Route caching failed"
fi

# Clear and cache views
print_status "Optimizing views..."
docker compose exec laravel-app php artisan view:clear
docker compose exec laravel-app php artisan view:cache

print_success "Setup completed successfully!"
print_status "Your Laravel application is now running at: http://localhost:8000"
print_status "Container name: aatc-vms-app"
print_status ""
print_status "Useful commands:"
print_status "  - Stop containers: docker compose down"
print_status "  - View logs: docker compose logs -f"
print_status "  - Execute commands: docker compose exec laravel-app [command]"
print_status "  - Access container shell: docker compose exec laravel-app bash"
