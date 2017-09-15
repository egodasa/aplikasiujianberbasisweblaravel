<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Ujian extends Controller
{
	public $cekUjian = [
            'nm_ujian' => 'bail|required|min:3|max:30',
	        'jam' => 'bail|required',
	        'menit' => 'bail|required'
        ];
    public $errUjian = [
			'nm_ujian.required'=>'Nama Ujian harus diisi!',
			'nm_ujian.min'=>'Nama Ujian terlalu pendek!',
			'nm_ujian.max'=>'Nama Ujian terlalu panjang!',
			'jam.required'=>'Jam harus diisi minimal 0!',
			'menit.required'=>'Menit harus diisi minimal 0!'
		];
	public function __construct(){
		
	}
    public function hasilUjian($id){
		$data = new \stdClass;
		$data->status = true;
		$data->data = DB::select('SELECT tbpeserta.nm_peserta,tbhasil_ujian.benar,tbhasil_ujian.salah,tbhasil_ujian.nilai FROM tbhasil_ujian inner join tbpeserta on tbhasil_ujian.id_peserta=tbpeserta.id_peserta inner join tbujian on tbhasil_ujian.id_ujian=tbujian.id_ujian WHERE tbhasil_ujian.id_ujian = ?;',[$id]);
		$data->row = count($data->data);
		return response()->json($data);
	}
	public function addHasilUjian(Request $req,$id){
		$data = new \stdClass;
		$data->status = true;
		DB::select('CALL createHasilUjian(?,?,?,?);',[$id,$req->id_peserta,$req->benar,$req->salah]);
		return response()->json($data);
	}
    public function getUjian(Request $req,$id = "0000000"){
		$data = new \stdClass;
		$data->status = true;
		if(!empty($req->query('limit'))){
			$limit = $req->query('limit');
			$offset = $req->query('offset');
		}else{
			$limit = $offset = 0;
		}
		$data->data = DB::select('call getUjian(?,?,?);',[$id,$limit,$offset]);
		$data->row = DB::table('tbujian')->count();
		return response()->json($data);
	}
	public function addUjian(Request $req){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekUjian, $this->errUjian);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$data->status = true;
			$data->error = null;
			DB::select('CALL createUjian(?,?,?);',[$req->nm_ujian,$req->jam,$req->menit]);
		}
		return response()->json($data);
	}
	public function deleteUjian($id){
		DB::select('call deleteUjian(?);',[$id]);
		return response()->json(['status'=>true]);
	}
	public function updateUjian(Request $req,$id){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekUjian, $this->errUjian);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			DB::select('CALL updateUjian(?,?,?,?);',[$id,$req->nm_ujian,$req->jam,$req->menit]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
