<?php

namespace App\Services;

use App\Models\Brand;
use Keepsuit\LaravelOpenTelemetry\Facades\OpenTelemetry;
use Illuminate\Support\Facades\Log;

class BrandService
{
    public function getAllBrands()
    {
        return OpenTelemetry::tracer()->newSpan('Service: Fetch All Brands')->measure(function () {
            app(\App\Services\AnomalyService::class)->injectPerformanceIssues();
            return Brand::all();
        });
    }

    public function createBrand(array $data)
    {
        return OpenTelemetry::tracer()->newSpan('Service: Create Brand')->measure(function () use ($data) {
            app(\App\Services\AnomalyService::class)->injectPerformanceIssues();
            
            return OpenTelemetry::tracer()->newSpan('DB: Insert Brand')->measure(function () use ($data) {
                return Brand::create($data);
            });
        });
    }

    public function updateBrand($id, array $data)
    {
        return OpenTelemetry::tracer()->newSpan('Service: Update Brand')->measure(function () use ($id, $data) {
            app(\App\Services\AnomalyService::class)->injectPerformanceIssues();

            $brand = OpenTelemetry::tracer()->newSpan('DB: Find Brand')->measure(function () use ($id) {
                return Brand::findOrFail($id);
            });

            return OpenTelemetry::tracer()->newSpan('DB: Update Brand Record')->measure(function () use ($brand, $data) {
                $brand->update($data);
                return $brand;
            });
        });
    }

    public function deleteBrand($id)
    {
        return OpenTelemetry::tracer()->newSpan('Service: Delete Brand')->measure(function () use ($id) {
            app(\App\Services\AnomalyService::class)->injectPerformanceIssues();

            $brand = OpenTelemetry::tracer()->newSpan('DB: Find Brand for Delete')->measure(function () use ($id) {
                return Brand::findOrFail($id);
            });

            return OpenTelemetry::tracer()->newSpan('DB: Execute Delete')->measure(function () use ($brand) {
                return $brand->delete();
            });
        });
    }
}
