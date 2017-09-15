<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cekUjian extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nm_ujian' => 'bail|required|min:3|max:30',
	        'durasi_ujian' => 'required'
        ];
    }
    public function messages(){
		return [
			'nm_ujian.required'=>'Nama Ujian harus diisi!',
			'nm_ujian.min'=>'Nama Ujian terlalu pendek!',
			'nm_ujian.max'=>'Nama Ujian terlalu panjang!',
			'durasi_ujian.required'=>'Durasi Ujian harus diisi!'
		];
	}
}
