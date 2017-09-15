<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Peserta extends Controller
{
	public $cekPeserta = [
		'nm_peserta'=>'bail|required|max:50|min:1'
	];
	public $errPeserta = [
		'nm_peserta.required'=>'Nama Peserta harus diisi!',
		'nm_peserta.max'=>'Nama Peserta terlalu panjang!',
		'nm_peserta.min'=>'Nama Peserta terlalu pendek!'
	];
    public function __construct(){
		
	}
    public function getPeserta(Request $req,$id = null){
		if(!empty($req->query('limit'))){
			$limit = $req->query('limit');
			$offset = $req->query('offset');
		}else{
			$limit = $offset = 0;
		}
		$data = new \stdClass;
		if($id == null){
			$data->data = DB::select('CALL getPeserta(0,?,?);',[$limit,$offset]);
		}else{
			$data->data = DB::select('CALL getPeserta(?,0,0);',[$id]);
		}
		$data->row = DB::table('tbpeserta')->count();;
		return response()->json($data);
	}
	public function addPeserta(Request $req){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekPeserta, $this->errPeserta);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			DB::select('CALL createPeserta(?);',[$req->nm_peserta]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
	public function deletePeserta($id){
		DB::select('call deletePeserta(?);',[$id]);
		return response()->json(['status'=>true]);
	}
	public function updatePeserta(Request $req,$id){
		$data = new \stdClass;
		$validator = \Validator::make($req->all(), $this->cekPeserta, $this->errPeserta);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			DB::select('CALL updatePeserta(?,?);',[$id,$req->nm_peserta]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
