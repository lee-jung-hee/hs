<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // 모델에서 타임스탬프를 사용하도록 설정
    public $timestamps = true;

    // 대량 할당 가능한 필드
    protected $fillable = ['body', 'user_id','created_at'];

    public function user(){
        return $this->belongsTo(User::class); //왼쪽처럼 하면 User라는 클래스에 Article이 속한다라는 뜻
    }

    // 아래는 블랙리스트 방식인데 화이트리스트 방식의 fillable과 같이 할 필요는 없음
    // protected $guarded = ['id']; 
}

