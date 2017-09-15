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
		$data = new \stdClass;
		$limit = $req->query('limit');
		$offset = $req->query('offset');
		$db = DB::table('tbpeserta')->limit($limit)->offset($offset);
		if($id == null) $data->data = $db->get();
		else $data->data = $db->where('id_peserta',$id)->get();
		$data->row = DB::table('tbpeserta')->count();
		$data->current_row = count($data->data);
		return response()->json($data);
	}
	public function addPeserta(Request $req){
		$data = new \stdClass;
		$db = DB::table('tbpeserta');
		$validator = \Validator::make($req->all(), $this->cekPeserta, $this->errPeserta);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$db->insert(['nm_peserta'=>$req->nm_peserta]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
	public function deletePeserta($id){
		DB::table('tbpeserta')->where('id_peserta',$id)->delete();
		return response()->json(['status'=>true]);
	}
	public function updatePeserta(Request $req,$id){
		$data = new \stdClass;
		$db = DB::table('tbpeserta');
		$validator = \Validator::make($req->all(), $this->cekPeserta, $this->errPeserta);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$db->where('id_peserta',$id)->update(['nm_peserta'=>$req->nm_peserta]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
