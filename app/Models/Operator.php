<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
  use HasFactory;
  protected $table = 'mas_operators';

  protected $fillable = ['nm_operator', 'divisi'];
}