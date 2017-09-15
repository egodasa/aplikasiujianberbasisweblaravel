<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mPeserta extends Model
{
    protected $table = 'tbpeserta';
	protected $primaryKey = 'id_peserta';
	protected $fillable = ['id_peserta','nm_peserta'];
	public $incrementing = true;
	public $timestamps = false;
}
