.PHONY: dev dev-build dev-down prod prod-build prod-down staging staging-down restart logs seed fresh shell

# ===== Development (hot reload, volume mounts) =====
dev:
	docker compose up

dev-build:
	docker compose up --build

dev-down:
	docker compose down

# Restart picks up new .env without rebuilding
restart:
	docker compose restart

# ===== Production =====
prod:
	docker compose -f docker-compose.prod.yml up -d

prod-build:
	docker compose -f docker-compose.prod.yml up -d --build

prod-down:
	docker compose -f docker-compose.prod.yml down

# ===== Staging =====
staging:
	docker compose -f docker-compose.staging.yml up -d

staging-down:
	docker compose -f docker-compose.staging.yml down

# ===== Utilities =====
logs:
	docker compose logs -f

shell:
	docker compose exec app sh

seed:
	docker compose exec app php artisan migrate:fresh --seed

fresh:
	docker compose down -v && docker compose up --build
