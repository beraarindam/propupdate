<?php

namespace App\Support;

final class ProjectDetailTextParsers
{
    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function parsePipeKeyValueRows(string $raw, int $max = 60): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || ! str_contains($line, '|')) {
                continue;
            }
            [$k, $v] = array_map('trim', explode('|', $line, 2));
            if ($k === '') {
                continue;
            }
            $out[] = ['label' => $k, 'value' => $v];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{unit_type: string, size_sqft: string, price_label: string}>
     */
    public static function parseUnitPricingRows(string $raw, int $max = 60): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 2) {
                continue;
            }
            $out[] = [
                'unit_type' => $parts[0] ?? '',
                'size_sqft' => $parts[1] ?? '',
                'price_label' => $parts[2] ?? '',
            ];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, string>
     */
    public static function parseBulletLines(string $raw, int $max = 120): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            $out[] = $line;
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    public static function parseFaqs(string $raw, int $max = 50): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }
        $blocks = preg_split('/\R\s*-{3,}\s*\R/', $raw);
        if (! is_array($blocks)) {
            return [];
        }
        $out = [];
        foreach ($blocks as $block) {
            $block = trim((string) $block);
            if ($block === '' || ! str_contains($block, ':::')) {
                continue;
            }
            [$q, $a] = explode(':::', $block, 2);
            $q = trim($q);
            $a = trim((string) $a);
            if ($q === '') {
                continue;
            }
            $out[] = ['question' => $q, 'answer' => $a];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }
}
