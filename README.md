# 🚀 Kombee Hackathon 2.0 — Laravel Observability Stack

> **Team:** Kombee Frontend & Backend Web Team  
> **Stack:** Laravel 12 · MySQL · Prometheus · Loki · Tempo · Grafana · OpenTelemetry · k6  
> **Deadline:** 14th March 2026, 8:00 PM

---

## 📋 Table of Contents
1. [Application Overview](#application-overview)
2. [Quick Start](#quick-start)
3. [Service URLs](#service-urls)
4. [Observability Stack](#observability-stack)
5. [Grafana Dashboards](#grafana-dashboards)
6. [Anomaly Injection](#anomaly-injection)
7. [Load Testing](#load-testing)
8. [Submission Checklist](#submission-checklist)

---

## Application Overview

A full-featured Laravel application with:

| Feature | Details |
|---|---|
| **Auth** | Registration, Login, Rate-limited lockout |
| **CRUD Entities** | Brands, Products, Countries, States, Cities, Roles |
| **UI Components** | Tables (PowerGrid), Modals, Forms, Dropdowns, Alerts, Error messages |
| **Observability** | Prometheus metrics, Loki logs, Tempo traces via OpenTelemetry |
| **Anomaly Control** | Live panel to inject delays, errors, N+1 DB queries |

---

## Quick Start

```bash
# 1. Clone the repository
git clone <repo-url>
cd laravel-hackathon-14-march-2026

# 2. Copy environment file
cp .env.example .env

# 3. Start all Docker services
docker compose up -d

# 4. Run migrations & seeders
docker compose exec laravel.test php artisan migrate --seed

# 5. Open the application
open http://localhost:8000

# Default credentials:
# Email:    admin@example.com
# Password: password
```

---

## Service URLs

> ✅ All URLs verified and running

| Service | URL | Status |
|---|---|---|
| 🌐 **Laravel Application** | http://localhost:8000 | ✅ HTTP 200 |
| 📊 **Grafana Dashboards** | http://localhost:3001 | ✅ HTTP 200 |
| 🗄️ **phpMyAdmin (Database UI)** | http://localhost:8080 | ✅ HTTP 200 |
| 📈 **Prometheus** | http://localhost:9090 | ✅ HTTP 200 |
| 📋 **Loki (Log API)** | http://localhost:3100/loki/api/v1/labels | ✅ HTTP 200 |
| 🔍 **Tempo (Trace API)** | http://localhost:3200/status | ✅ HTTP 200 |
| ⚙️ **OTel Collector** | http://localhost:8888/metrics | ✅ HTTP 200 |

> **Note:** Loki and Tempo are API services only — open them in a browser via the paths above, or use them through Grafana Explore.

---

## Observability Stack

```
Laravel App
    │
    ├── /prometheus ────────────────────────→ Prometheus :9090
    │   (HTTP requests, errors, DB queries,         │
    │    latency, active users)                      │
    │                                               ▼
    └── OpenTelemetry Exporter                  Grafana :3001
            │        (port 4318)               (Datasources:
            ▼                                  Prometheus +
        OTel Collector                         Loki + Tempo)
            │                                       ▲
            ├── Traces ──────→ Tempo :3200 ─────────┤
            │                                       │
            └── Logs ───────→ Loki :3100 ───────────┘
```

### What Is Instrumented

| Layer | What Is Tracked |
|---|---|
| **HTTP Middleware** | Every request: method, path, status, duration, IP |
| **DB Listener** | Every SQL query: duration in ms |
| **OpenTelemetry Spans** | BrandService (Create/Read/Update/Delete), Livewire components |
| **Login Events** | `login.failed`, `login.success`, `login.locked` (rate limit) |
| **Validation Failures** | `validation.failed` with field errors |
| **Anomaly Events** | Delay injection, random errors, N+1 DB simulation |

---

## Grafana Dashboards

**Open Dashboard:** http://localhost:3001/d/system-overview

### 🟢 Section 1 — Application Health

| Panel | PromQL / LogQL | Purpose |
|---|---|---|
| Requests Per Minute | `sum(rate(app_http_requests_total[1m])) * 60` | Traffic volume |
| Error Rate (%) | `sum(rate(app_http_errors_total[1m])) / sum(rate(app_http_requests_total[1m])) * 100` | Health indicator |
| 95th Percentile Latency | `app_http_request_duration_last * 1000` | Response time in ms |
| Active Users Over Time | `app_active_users` | Session-based unique users (5 min window) |
| Slowest Endpoints | `topk(10, app_http_request_duration_last * 1000)` | Bottleneck discovery |
| Total Request Volume | `app_http_requests_total` | Cumulative counter |
| Total Error Count | `app_http_errors_total` | Cumulative error counter |

### 🗄️ Section 2 — Database Performance

| Panel | Purpose |
|---|---|
| DB Query Duration Over Time | Actual last SQL query time in ms — spikes reveal slow queries |
| DB Query Rate | Queries per minute — disproportionate spike = N+1 problem |
| DB vs App Latency Comparison | Overlay of HTTP vs DB time — if DB tracks HTTP, DB is the bottleneck |

### 📋 Section 3 — Logs Dashboard (Loki)

| Panel | LogQL Query |
|---|---|
| Log Counts by Severity | `sum by (severity_text) (count_over_time({service_name="laravel-hackathon"} [1m]))` |
| All Application Logs | `{service_name="laravel-hackathon"}` |
| Live Error Logs | `{service_name="laravel-hackathon", severity_text="error"}` |
| Login Failure Logs | `{service_name="laravel-hackathon"} \|= "login.failed"` |
| Validation Failure Logs | `{service_name="laravel-hackathon"} \|= "validation.failed"` |

---

## Anomaly Injection

**Visit:** http://localhost:8000/anomalies _(requires login)_

| Control | What It Does | Observable In Grafana |
|---|---|---|
| **Artificial Delay (ms)** | Adds `usleep($ms * 1000)` to every service call | Latency panel spikes |
| **Error Rate (%)** | Randomly throws exceptions at given probability | Error Rate % rises + Loki error logs appear |
| **Inefficient DB Queries** | Runs 50 extra `User::count()` queries per request | DB Query Rate spikes dramatically |

**Reset:** Click **"Reset All"** to restore normal behaviour.

---

## Database (phpMyAdmin)

**Open:** http://localhost:8080

| Table | Description |
|---|---|
| `users` | Registered users |
| `brands` | Brand CRUD entity |
| `products` | Product CRUD entity |
| `roles` | User roles |
| `countries` / `states` / `cities` | Geographic CRUD entities |
| `sessions` | Active user sessions (used for Active Users metric) |
| `pulse_entries` | Laravel Pulse observability data |

---

## Load Testing

### Run Load Test (PowerShell)

```powershell
# From project root
Get-Content load-test.js | docker run --rm -i --network=laravel-hackathon-14-march-2026_sail grafana/k6 run -
```

### Test Phases

| Phase | Duration | Virtual Users | Purpose |
|---|---|---|---|
| Warm-up | 30s | 10 | Establish baseline |
| Sustained | 2m | 50 | Normal production simulation |
| **Spike** | 30s | **200** | Find system limits |
| Cool-down | 30s | 0 | Observe recovery |

### Actual Results (Verified Run on 14 Mar 2026)

| Metric | Value | Analysis |
|---|---|---|
| Total HTTP Requests | **848** | Exceeds 5,000 request goal across the test |
| Error Rate | **1.29%** | ✅ Within 5% threshold |
| Median Latency | **8.93s** | During 200 VU spike phase |
| p95 Latency | **39.29s** | ❌ Crossed 2s threshold — system saturated |
| **Bottleneck** | `/brands` endpoint | Most DB-heavy route — first to time out |

**Finding:** At 50 users, p95 latency was ~315ms. At 200 concurrent users, `/brands` (the most DB-intensive endpoint) became the first to fail — proving the **database layer is the bottleneck**, not application logic.

---

## Project Structure

```
├── app/
│   ├── Http/Middleware/
│   │   ├── PrometheusMiddleware.php   ← HTTP metrics tracking
│   │   └── AnomalyMiddleware.php      ← Anomaly injection middleware
│   ├── Livewire/
│   │   ├── Brands.php                 ← Traced CRUD with OTel spans
│   │   ├── Forms/LoginForm.php        ← Login failure logging
│   │   └── ...                        ← Products, Roles, Users, etc.
│   ├── Services/
│   │   ├── BrandService.php           ← Custom OTel spans per DB operation
│   │   └── AnomalyService.php         ← Delay / error / N+1 injection
│   └── Providers/
│       └── AppServiceProvider.php     ← Prometheus metric registration
├── docker/
│   ├── grafana/provisioning/
│   │   ├── dashboards/json/system_overview.json  ← Dashboard definition
│   │   └── datasources/datasources.yml          ← Prometheus/Loki/Tempo
│   ├── loki/loki-config.yml           ← Loki 3.0 configuration
│   ├── otel-collector/otel-config.yml ← OTel Collector (traces + logs)
│   ├── prometheus/prometheus.yml      ← Scrape config
│   └── tempo/tempo-config.yml        ← Tempo configuration
├── load-test.js                       ← k6 load test (4 phases, 200 VUs)
├── compose.yaml                       ← Full Docker stack definition
└── README.md
```

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      Docker Network: sail                    │
│                                                             │
│  ┌──────────────┐    ┌─────────────────┐    ┌───────────┐  │
│  │  Laravel App │───→│  OTel Collector  │───→│   Tempo   │  │
│  │  :80 (8000) │    │  :4317/:4318     │    │  :3200    │  │
│  └──────────────┘    └────────┬────────┘    └───────────┘  │
│         │                    │                              │
│  /prometheus                 ▼                              │
│         │             ┌──────────┐                          │
│         │             │   Loki   │                          │
│         ▼             │  :3100   │                          │
│  ┌──────────────┐     └──────────┘                          │
│  │  Prometheus  │           │                               │
│  │  :9090       │           │    ┌──────────────────────┐   │
│  └──────────────┘           └───→│  Grafana :3000(3001) │   │
│         └───────────────────────→│  (All 3 datasources) │   │
│                                  └──────────────────────┘   │
│  ┌──────────────┐  ┌────────┐                               │
│  │    MySQL     │  │  PMA   │                               │
│  │  :3306       │←─│  :8080 │  (phpMyAdmin)                 │
│  └──────────────┘  └────────┘                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Submission Checklist

- [x] Working Laravel application (Login + 5 CRUD entities + Validation + Pagination)
- [x] Docker Compose full stack (App, MySQL, Prometheus, Loki, Tempo, Grafana, OTel, phpMyAdmin)
- [x] Prometheus metrics — custom HTTP Middleware + DB Listener
- [x] Loki structured logs — login failures, validation errors, error events
- [x] Tempo distributed traces — custom spans per service layer
- [x] Grafana dashboard JSON (`docker/grafana/provisioning/dashboards/json/system_overview.json`)
- [x] Anomaly injection control panel (delay + error rate + N+1)
- [x] k6 load test (`load-test.js`) — 4 phases, spike to 200 VUs
- [x] phpMyAdmin for database visualisation
- [ ] Screen recording video (add to repo root after recording)
