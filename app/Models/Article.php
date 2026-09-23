<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'image',
        'excerpt',
        'content',
        'author',
        'views',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $slug = Str::slug($article->title);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }
                $article->slug = $slug;
            }
            if (empty($article->published_at) && $article->status === 'published') {
                $article->published_at = now();
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Otomatis mengonversi teks ber-Enter ganda menjadi paragraf <p> jika belum ada tag block HTML.
     */
    public function getFormattedContentAttribute(): string
    {
        $content = $this->content ?? '';
        if (trim($content) === '') {
            return '';
        }

        // Jika sudah mengandung tag block HTML
        if (preg_match('/<\s*(p|div|ul|ol|h[1-6]|blockquote|table)\b[^>]*>/i', $content)) {
            return $content;
        }

        // Pisahkan teks berdasarkan Enter ganda / kosong
        $paragraphs = array_filter(array_map('trim', preg_split('/\r\n\r\n|\n\n|\r\r/', $content)));
        if (empty($paragraphs)) {
            return '<p>' . nl2br($content) . '</p>';
        }

        $formatted = '';
        foreach ($paragraphs as $paragraph) {
            $formatted .= '<p>' . nl2br($paragraph) . '</p>' . "\n";
        }

        return $formatted;
    }
}
