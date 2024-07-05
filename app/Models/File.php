<?php

namespace App\Models;

use App\Traits\CreatorUpdaterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class File extends Model
{
    use HasFactory, CreatorUpdaterTrait, NodeTrait, SoftDeletes;
}
