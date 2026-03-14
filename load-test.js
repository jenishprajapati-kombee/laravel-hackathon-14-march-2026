import http from 'k6/http';
import { sleep, check, group } from 'k6';

/**
 * k6 Load Test — Hackathon Observability Demo
 * ─────────────────────────────────────────────
 * Simulates 5,000+ requests across multiple endpoints.
 * Run with: k6 run load-test.js --env BASE_URL=http://laravel.test
 *
 * Stages:
 *  Phase 1 – Warm up (ramp to 10 users, 30s)
 *  Phase 2 – Sustained load (50 users, 2m)
 *  Phase 3 – Spike (200 users, 30s) ← anomaly-visible zone
 *  Phase 4 – Cool down (ramp down, 30s)
 */

const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000';

export const options = {
  stages: [
    { duration: '30s', target: 10 },   // warm up
    { duration: '2m',  target: 50 },   // sustained load (~5,000 requests)
    { duration: '30s', target: 200 },  // spike test
    { duration: '30s', target: 0 },    // ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<2000'],   // 95% of requests under 2s
    http_req_failed:   ['rate<0.05'],    // less than 5% failure rate
  },
};

export default function () {
  // ───────────────────────────
  // Group 1: Public routes
  // ───────────────────────────
  group('Public – Home', () => {
    const res = http.get(`${BASE_URL}/`);
    check(res, { 'home: status 200 or 302': (r) => r.status === 200 || r.status === 302 });
  });

  // ───────────────────────────
  // Group 2: Auth routes (unauthenticated hits)
  // ───────────────────────────
  group('Auth – Login page', () => {
    const res = http.get(`${BASE_URL}/login`);
    check(res, { 'login page: status 200': (r) => r.status === 200 });
  });

  // ───────────────────────────
  // Group 3: Protected routes — Brands (will redirect but still traces)
  // ───────────────────────────
  group('App – Brands', () => {
    const res = http.get(`${BASE_URL}/brands`);
    check(res, { 'brands: ok or redirect': (r) => r.status === 200 || r.status === 302 });
  });

  // ───────────────────────────
  // Group 4: Prometheus scrape endpoint
  // ───────────────────────────
  group('Metrics – Prometheus', () => {
    const res = http.get(`${BASE_URL}/prometheus`);
    check(res, { 'prometheus: status 200': (r) => r.status === 200 });
  });

  // ───────────────────────────
  // Group 5: Anomaly trigger (error injection)
  // ───────────────────────────
  group('Anomaly – Trigger Error', () => {
    const res = http.get(`${BASE_URL}/trigger-error`);
    check(res, { 'trigger-error: status 200': (r) => r.status === 200 });
  });

  sleep(Math.random() * 1 + 0.5); // 0.5–1.5s between iterations
}
