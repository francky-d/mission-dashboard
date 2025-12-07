# ===========================================
# Mission Dashboard - Production Makefile
# ===========================================

.PHONY: help build up down restart logs shell migrate fresh seed backup

# Colors
GREEN := \033[0;32m
YELLOW := \033[0;33m
NC := \033[0m

help: ## Show this help
	@echo "$(GREEN)Mission Dashboard - Production Commands$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

# ===========================================
# Docker Commands
# ===========================================

build: ## Build production Docker images
	docker compose -f docker-compose.prod.yml build --no-cache

up: ## Start all production services
	docker compose -f docker-compose.prod.yml up -d

down: ## Stop all production services
	docker compose -f docker-compose.prod.yml down

restart: ## Restart all production services
	docker compose -f docker-compose.prod.yml restart

logs: ## View logs from all services
	docker compose -f docker-compose.prod.yml logs -f

logs-app: ## View logs from app service
	docker compose -f docker-compose.prod.yml logs -f app

logs-queue: ## View logs from queue worker
	docker compose -f docker-compose.prod.yml logs -f queue

logs-reverb: ## View logs from Reverb WebSocket server
	docker compose -f docker-compose.prod.yml logs -f reverb

# ===========================================
# Application Commands
# ===========================================

shell: ## Open shell in app container
	docker compose -f docker-compose.prod.yml exec app sh

migrate: ## Run database migrations
	docker compose -f docker-compose.prod.yml exec app php artisan migrate --force

fresh: ## Fresh database with migrations
	docker compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --force

seed: ## Seed database
	docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force

tinker: ## Open Laravel Tinker
	docker compose -f docker-compose.prod.yml exec app php artisan tinker

# ===========================================
# Cache Commands
# ===========================================

cache: ## Cache all configuration
	docker compose -f docker-compose.prod.yml exec app php artisan config:cache
	docker compose -f docker-compose.prod.yml exec app php artisan route:cache
	docker compose -f docker-compose.prod.yml exec app php artisan view:cache
	docker compose -f docker-compose.prod.yml exec app php artisan event:cache

cache-clear: ## Clear all caches
	docker compose -f docker-compose.prod.yml exec app php artisan config:clear
	docker compose -f docker-compose.prod.yml exec app php artisan route:clear
	docker compose -f docker-compose.prod.yml exec app php artisan view:clear
	docker compose -f docker-compose.prod.yml exec app php artisan cache:clear

# ===========================================
# Maintenance Commands
# ===========================================

maintenance-on: ## Enable maintenance mode
	docker compose -f docker-compose.prod.yml exec app php artisan down --refresh=15

maintenance-off: ## Disable maintenance mode
	docker compose -f docker-compose.prod.yml exec app php artisan up

# ===========================================
# Queue Commands
# ===========================================

queue-restart: ## Restart queue workers
	docker compose -f docker-compose.prod.yml restart queue

queue-failed: ## List failed jobs
	docker compose -f docker-compose.prod.yml exec app php artisan queue:failed

queue-retry: ## Retry all failed jobs
	docker compose -f docker-compose.prod.yml exec app php artisan queue:retry all

queue-flush: ## Flush all failed jobs
	docker compose -f docker-compose.prod.yml exec app php artisan queue:flush

# ===========================================
# Database Commands
# ===========================================

backup: ## Backup database
	@mkdir -p backups
	docker compose -f docker-compose.prod.yml exec pgsql pg_dump -U $${DB_USERNAME:-mission_dashboard} $${DB_DATABASE:-mission_dashboard} > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)Backup created in backups/$(NC)"

psql: ## Open PostgreSQL shell
	docker compose -f docker-compose.prod.yml exec pgsql psql -U $${DB_USERNAME:-mission_dashboard} $${DB_DATABASE:-mission_dashboard}

# ===========================================
# Deployment
# ===========================================

deploy: ## Full deployment (build + up + migrate + cache)
	@echo "$(GREEN)Starting deployment...$(NC)"
	$(MAKE) build
	$(MAKE) up
	@echo "$(GREEN)Waiting for services to be ready...$(NC)"
	@sleep 10
	$(MAKE) migrate
	$(MAKE) cache
	@echo "$(GREEN)Deployment complete!$(NC)"

update: ## Update deployment (pull + rebuild + migrate)
	@echo "$(GREEN)Updating application...$(NC)"
	git pull
	$(MAKE) build
	docker compose -f docker-compose.prod.yml up -d --no-deps app queue scheduler reverb
	$(MAKE) migrate
	$(MAKE) cache
	$(MAKE) queue-restart
	@echo "$(GREEN)Update complete!$(NC)"

status: ## Show status of all services
	docker compose -f docker-compose.prod.yml ps
