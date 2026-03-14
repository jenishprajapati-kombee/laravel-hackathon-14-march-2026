# 🚀 Kombee Hackathon 2.0 — Laravel Observability Stack

> **Team:** Kombee Frontend & Backend Web Team  
> **Stack:** Laravel 12 · MySQL · Prometheus · Loki · Tempo · Grafana · OpenTelemetry · k6  
> **Deadline:** 14th March 2026, 8:00 PM

---

## 📋 Table of Contents
1. [Application Overview](#application-overview)
2. [Quick Start](#quick-start)
3. [Observability Stack](#observability-stack)
4. [Grafana Dashboards](#grafana-dashboards)
5. [Anomaly Injection](#anomaly-injection)
6. [Load Testing](#load-testing)
7. [Submission Checklist](#submission-checklist)

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

### Service URLs

| Service | URL |
|---|---|
| **Application** | http://localhost:8000 |
| **Grafana** | http://localhost:3001 |
| **Prometheus** | http://localhost:9090 |
| **Loki** | http://localhost:3100 |
| **Tempo** | http://localhost:3200 |

---

## Observability Stack

```
Laravel App
    │
    ├── Prometheus Metrics ─────────────────→ Prometheus (port 9090)
    │   (HTTP requests, errors, DB queries,              │
    │    latency, active users)                          │
    │                                                    ▼
    └── OpenTelemetry Exporter → OTel Collector → Grafana (port 3001)
            │                         │                  ▲
            ├── Traces ────────────→ Tempo (port 3200)   │
            │                                            │
            └── Logs ─────────────→ Loki (port 3100) ───┘
```

### Instrumented Layers

- **HTTP Middleware** — Every request logged with status, duration, IP
- **DB Listener** — Every query duration tracked
- **OpenTelemetry Spans** — Custom spans in BrandService, Livewire components
- **Login Events** — `login.failed`, `login.success`, `login.locked`
- **Validation Failures** — `validation.failed` events

---

## Grafana Dashboards

Dashboard: **System Observability Overview** → http://localhost:3001

### 🟢 Section 1 — Application Health
| Panel | Query | Purpose |
|---|---|---|
| Requests Per Minute | `sum(rate(app_http_requests_total[1m])) * 60` | Traffic volume |
| Error Rate (%) | `sum(rate(app_http_errors_total...` | Health indicator |
| 95th Percentile Latency | `app_http_request_duration_last * 1000` | User experience |
| Active Users Over Time | `app_active_users` | Session-based tracking |
| Slowest Endpoints | `topk(10, ...)` | Bottleneck discovery |

### 🗄️ Section 2 — Database Performance
| Panel | Purpose |
|---|---|
| DB Query Duration | Actual last query time in ms |
| DB Query Rate | Queries per minute |
| DB vs App Latency | Compares HTTP total vs DB time to find bottleneck origin |

### 📋 Section 3 — Logs (Loki)
| Panel | LogQL Query |
|---|---|
| All Logs | `{service_name="laravel-hackathon"}` |
| Error Logs | `{service_name="laravel-hackathon", severity_text="error"}` |
| Login Failures | `{service_name="laravel-hackathon"} \|= "login.failed"` |
| Validation Failures | `{service_name="laravel-hackathon"} \|= "validation.failed"` |
| Log Severity Count | `sum by (severity_text) (count_over_time(...[1m]))` |

---

## Anomaly Injection

Visit: **http://localhost:8000/anomalies** (requires login)

| Control | Effect | Observable In |
|---|---|---|
| **Artificial Delay (ms)** | Adds `usleep()` to every request | Latency panel spikes |
| **Error Rate (%)** | Randomly throws exceptions | Error rate % rises, error logs appear in Loki |
| **Inefficient DB** | Runs 50 extra `User::count()` queries | DB Query Rate spikes dramatically |

**Reset all anomalies** — Click "Reset All" button to restore normal behaviour.

---

## Load Testing

### Run with k6 (Docker)

```powershell
# PowerShell
Get-Content load-test.js | docker run --rm -i --network=laravel-hackathon-14-march-2026_sail grafana/k6 run -
```

### Test Phases

| Phase | Duration | VUs | Purpose |
|---|---|---|---|
| Warm-up | 30s | 10 | Establish baseline |
| Sustained | 2m | 50 | Normal production load |
| **Spike** | 30s | **200** | Stress test, find limits |
| Cool-down | 30s | 0 | Observe recovery |

### Results Summary (Actual Run)

| Metric | Value |
|---|---|
| Total Requests | 848 |
| Error Rate | 1.29% |
| Median Latency | 8.93s (spike phase) |
| p95 Latency | 39.29s (spike phase) |
| **Bottleneck** | `/brands` endpoint (DB query heavy) |

**Finding:** At 200 concurrent users, the `/brands` endpoint becomes the first to fail due to DB query saturation, proving the database is the performance bottleneck.

---

## Submission Checklist

- [x] Working Laravel application (Login + 5 CRUD entities)
- [x] Docker Compose: full stack (App, MySQL, Prometheus, Loki, Tempo, Grafana, OTel)
- [x] Prometheus metrics with custom middleware
- [x] Loki logs via OpenTelemetry (structured login/validation/error events)
- [x] Tempo traces with custom spans (BrandService, Livewire layers)
- [x] Grafana dashboard JSON (`docker/grafana/provisioning/dashboards/json/system_overview.json`)
- [x] Anomaly injection control panel
- [x] k6 load test script (`load-test.js`)
- [ ] Screen recording video (see VIDEO_SCRIPT.md)

---

## Architecture

```
┌─────────────────────────────────────────────────────┐
│                    Docker Network                    │
│                                                      │
│  ┌──────────┐    ┌──────────────┐    ┌───────────┐  │
│  │  Laravel │───→│ OTel Collect │───→│   Tempo   │  │
│  │  :80     │    │ (Traces+Logs)│    │  :3200    │  │
│  └──────────┘    └──────┬───────┘    └───────────┘  │
│       │                 │                            │
│       │ /prometheus     ▼                            │
│       │          ┌──────────┐                        │
│       │          │   Loki   │                        │
│       ▼          │  :3100   │                        │
│  ┌──────────┐    └──────────┘                        │
│  │Prometheus│                                        │
│  │  :9090   │    ┌──────────────────────────────┐   │
│  └──────────┘    │          Grafana :3000        │   │
│       └─────────→│  (Prometheus + Loki + Tempo)  │   │
│                  └──────────────────────────────┘    │
└─────────────────────────────────────────────────────┘
```
