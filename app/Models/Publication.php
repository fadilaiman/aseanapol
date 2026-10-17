<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /** Cover gradient presets (Tailwind classes — kept literal so the build scanner picks them up). */
    public const COLORS = [
        'from-amber-700 to-amber-900'     => 'Amber',
        'from-cyan-700 to-cyan-900'       => 'Cyan',
        'from-primary/90 to-primary'      => 'Navy (dark)',
        'from-teal-700 to-teal-900'       => 'Teal',
        'from-indigo-700 to-indigo-900'   => 'Indigo',
        'from-primary to-primary/70'      => 'Navy (light)',
        'from-blue-700 to-blue-900'       => 'Blue',
        'from-rose-700 to-rose-900'       => 'Rose',
        'from-emerald-700 to-emerald-900' => 'Emerald',
    ];

    /** Admin-side preview gradients (the admin panel uses the Tailwind CDN, which doesn't know our theme colours). */
    public const SWATCH = [
        'from-amber-700 to-amber-900'     => ['#b45309', '#78350f'],
        'from-cyan-700 to-cyan-900'       => ['#0e7490', '#164e63'],
        'from-primary/90 to-primary'      => ['#313b73', '#1B2660'],
        'from-teal-700 to-teal-900'       => ['#0f766e', '#134e4a'],
        'from-indigo-700 to-indigo-900'   => ['#4338ca', '#312e81'],
        'from-primary to-primary/70'      => ['#1B2660', '#5a6290'],
        'from-blue-700 to-blue-900'       => ['#1d4ed8', '#1e3a8a'],
        'from-rose-700 to-rose-900'       => ['#be123c', '#881337'],
        'from-emerald-700 to-emerald-900' => ['#047857', '#064e3b'],
    ];

    protected $fillable = [
        'edition', 'slug', 'kind', 'title', 'theme', 'year', 'size', 'summary',
        'color', 'cover_path', 'pdf_path', 'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order')->orderByDesc('id');
    }

    public function getTypeAttribute(): string
    {
        return $this->kind === 'magazine' ? 'Magazine' : 'Bulletin';
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->resolve($this->pdf_path);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->resolve($this->cover_path);
    }

    public function getSwatchStyleAttribute(): string
    {
        [$a, $b] = self::SWATCH[$this->color_class] ?? self::SWATCH['from-primary to-primary/70'];

        return "background:linear-gradient(135deg,{$a},{$b})";
    }

    public function getColorClassAttribute(): string
    {
        return $this->color ?: 'from-primary to-primary/70';
    }

    private function resolve(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return preg_match('#^https?://#i', $path) ? $path : asset(ltrim($path, '/'));
    }
}
