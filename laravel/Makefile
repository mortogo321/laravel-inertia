.PHONY: dev dev-build dev-down prod prod-build prod-down staging staging-down restart logs seed fresh shell

DOCKER_DIR := ../docker

# ===== Development (hot reload, volume mounts) =====
dev:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml up

dev-build:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml up --build

dev-down:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml down

# Restart picks up new .env without rebuilding
restart:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml restart

# ===== Production =====
prod:
	docker compose -f $(DOCKER_DIR)/docker-compose.prod.yml up -d

prod-build:
	docker compose -f $(DOCKER_DIR)/docker-compose.prod.yml up -d --build

prod-down:
	docker compose -f $(DOCKER_DIR)/docker-compose.prod.yml down

# ===== Staging =====
staging:
	docker compose -f $(DOCKER_DIR)/docker-compose.staging.yml up -d

staging-down:
	docker compose -f $(DOCKER_DIR)/docker-compose.staging.yml down

# ===== Utilities =====
logs:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml logs -f

shell:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml exec app sh

seed:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml exec app php artisan migrate:fresh --seed

fresh:
	docker compose -f $(DOCKER_DIR)/docker-compose.yml down -v && docker compose -f $(DOCKER_DIR)/docker-compose.yml up --build
