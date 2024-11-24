<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nampan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = ['created_at', 'updated_at', 'deleted_at']; // Menyembunyikan created_at dan updated_at secara global
    protected $table = "nampan";
    protected $fillable = [
        'nama',
        'jenis_id',
        'status',
    ];

    /**
     * Get the jenis that owns the Nampan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class, 'jenis_id', 'id');
    }

    /**
     * Get all of the comments for the Nampan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nampanProduk(): HasMany
    {
        return $this->hasMany(NampanProduk::class, 'nampan_id');
    }
}
