<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    // 【20220906】▼▼▼登録フォームに画像を追加▼▼▼
    protected $table = "forms";
	protected $fillable = [
        "name",
        "name_kana",
        "title",
        "body",
        "contact_type",
        "email",
        "age",
        "pref",
        "agree",
        "file_name",
        "file_path"];
    // 【20220906】▲▲▲登録フォームに画像を追加▲▲▲
    
}
