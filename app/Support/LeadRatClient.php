<?php

namespace App\Support;

use App\Models\Enquiry;
use App\Models\ExclusiveResaleListing;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadRatClient
{
    public function pushFromEnquiry(Enquiry $enquiry, array $context = []): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        $payload = $this->payloadForEnquiry($enquiry, $context);

        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->withHeaders([
                    'API-Key' => (string) config('services.leadrat.api_key'),
                    'Content-Type' => 'application/json',
                ])
                ->post((string) config('services.leadrat.endpoint'), [$payload]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('LeadRat push failed.', [
                'status' => $response->status(),
                'body' => $response->body(),
                'enquiry_id' => $enquiry->id,
                'source' => $enquiry->source,
            ]);
        } catch (Throwable $e) {
            Log::warning('LeadRat push exception.', [
                'message' => $e->getMessage(),
                'enquiry_id' => $enquiry->id,
                'source' => $enquiry->source,
            ]);
        }

        return false;
    }

    private function isEnabled(): bool
    {
        return (bool) config('services.leadrat.enabled')
            && filled((string) config('services.leadrat.endpoint'))
            && filled((string) config('services.leadrat.api_key'));
    }

    private function payloadForEnquiry(Enquiry $enquiry, array $context): array
    {
        $property = $context['property_model'] ?? null;
        if (! $property instanceof Property) {
            $property = $enquiry->property;
        }

        $project = $context['project_model'] ?? null;
        if (! $project instanceof Project) {
            $project = $enquiry->project;
        }

        $resale = $context['exclusive_resale_model'] ?? null;
        if (! $resale instanceof ExclusiveResaleListing) {
            $resale = $enquiry->exclusiveResaleListing;
        }

        $state = (string) ($context['state'] ?? $property?->state ?? $project?->state ?? '');
        $city = (string) ($context['city'] ?? $property?->city ?? $project?->city ?? '');
        $location = (string) ($context['location'] ?? $property?->locality ?? $project?->locality ?? $resale?->location ?? '');
        $budget = (string) ($context['budget'] ?? $property?->price ?? $resale?->asking_price ?? '');

        $mobileRaw = (string) ($context['mobile'] ?? $enquiry->phone ?? '');
        $mobileDigits = preg_replace('/\D+/', '', $mobileRaw) ?: '';

        $countryCode = (string) ($context['countryCode'] ?? config('services.leadrat.country_code', '91'));
        if (str_starts_with($mobileDigits, $countryCode)) {
            $mobileDigits = (string) substr($mobileDigits, strlen($countryCode));
        }

        $sourceLabel = match ($enquiry->source) {
            Enquiry::SOURCE_PROPERTY => 'Property Enquiry',
            Enquiry::SOURCE_PROJECT => 'Project Enquiry',
            Enquiry::SOURCE_EXCLUSIVE_RESALE => 'Exclusive Resale Enquiry',
            Enquiry::SOURCE_PRE_REGISTER => 'Pre Register',
            default => 'Contact Enquiry',
        };

        $projectName = (string) ($context['project_name'] ?? $project?->title ?? '');
        $propertyName = (string) ($context['property_name'] ?? $property?->title ?? $resale?->title ?? '');
        $propertyType = (string) ($context['propertyType']
            ?? $resale?->property_type
            ?? $property?->category?->name
            ?? $project?->category?->name
            ?? '');

        $additionalProperties = array_filter([
            'source' => $enquiry->source,
            'sourceLabel' => $sourceLabel,
            'subject' => (string) ($enquiry->subject ?? ''),
            'ip' => (string) ($enquiry->ip_address ?? ''),
            'pageUrl' => (string) ($context['page_url'] ?? ''),
        ], static fn ($value) => $value !== '');

        return array_filter([
            'name' => (string) $enquiry->name,
            'state' => $state,
            'city' => $city,
            'location' => $location,
            'budget' => $budget,
            'notes' => (string) ($enquiry->message ?? ''),
            'email' => (string) ($enquiry->email ?? ''),
            'countryCode' => $countryCode,
            'mobile' => $mobileDigits,
            'project' => $projectName,
            'property' => $propertyName,
            'leadExpectedBudget' => (string) ($context['leadExpectedBudget'] ?? $budget),
            'propertyType' => $propertyType,
            'submittedDate' => now()->format('d-m-y'),
            'submittedTime' => now()->format('H:i:s'),
            'LeadId' => (string) $enquiry->id,
            'subsource' => (string) ($context['subsource'] ?? 'Website'),
            'leadStatus' => (string) ($context['leadStatus'] ?? 'New Lead'),
            'callRecordingUrl' => (string) ($context['callRecordingUrl'] ?? ''),
            'scheduledDate' => (string) ($context['scheduledDate'] ?? ''),
            'additionalProperties' => Arr::undot($additionalProperties),
        ], static fn ($value, $key) => $key === 'additionalProperties' || $value !== '', ARRAY_FILTER_USE_BOTH);
    }
}

