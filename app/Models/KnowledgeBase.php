<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'knowledge_bases';

    protected $fillable = [
        'title',
        'content',
        'file_path',
        'file_type',
        'category',
        'vector_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const CATEGORY_SOP = 'sop';
    const CATEGORY_POLICY = 'kebijakan';
    const CATEGORY_TATA_TERTIB = 'tata_tertib';
    const CATEGORY_CALENDAR = 'kalender_akademik';
    const CATEGORY_ANNOUNCEMENT = 'pengumuman';
    const CATEGORY_FAQ = 'faq';
    const CATEGORY_GUIDE = 'panduan';
}
