<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Soal extends Controller
{
    public $cekSoal = [
		'isi_soal'=>'bail|required|max:750|min:3',
		'jawaban'=>'bail|required|max:5'
	];
	public $errSoal = [
		'isi_soal.required'=>'Isi soal tidak boleh kosong!',
		'isi_soal.max'=>'Isi soal terlalu panjang!',
		'jawaban.required'=>'Jawaban harus dipilih!',
		'jawaban.max'=>'Jawaban terlalu panjang!'
	];
    public function __construct(){
		
	}
    public function getSoal(Request $req,$id = null){
		$data = new \stdClass;
		$limit = $req->query('limit');
		$offset = $req->query('offset');
		$db = DB::table('tbsoal')->limit($limit)->offset($offset);
		if($id == null) $data->data = $db->get();
		else {
			$data->data = $db->where('id_soal',$id)->get();
			$data->data[0]->pilihanGanda = DB::table('tbpilihan_ganda')->where('id_soal',$id)->get();
		}
		$data->row = DB::table('tbsoal')->count();
		$data->current_row = count($data->data);
		return response()->json($data);
	}
	public function addSoal(Request $req){
		$data = new \stdClass;
		$db = DB::table('tbsoal');
		$validator = \Validator::make($req->all(), $this->cekSoal, $this->errSoal);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$idSoal = $db->insertGetId([
				'isi_soal'=>$req->isi_soal,
				'jawaban'=>$req->jawaban
			],'id_soal');
			$y = count($req->pilihanGanda);
			$pilihanGanda = [];
			for($x = 0;$x < $y;$x++){
				$pilihanGanda[$x]['id_soal'] = $idSoal;
				$pilihanGanda[$x]['isi_pilihan'] = $req->pilihanGanda[$x]['isi_pilihan'];
				$pilihanGanda[$x]['huruf'] = $req->pilihanGanda[$x]['huruf'];
			};
			DB::table('tbpilihan_ganda')->insert($pilihanGanda);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
	public function deleteSoal($id){
		DB::table('tbsoal')->where('id_soal',$id)->delete();
		return response()->json(['status'=>true]);
	}
	public function updateSoal(Request $req,$id){
		$data = new \stdClass;
		$db = DB::table('tbsoal');
		$validator = \Validator::make($req->all(), $this->cekSoal, $this->errSoal);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$db->where('id_soal',$id)->update([
				'isi_soal'=>$req->isi_soal,
				'jawaban'=>$req->jawaban
			]);
			DB::table('tbpilihan_ganda')->where('id_soal',$id)->delete();
			$y = count($req->pilihanGanda);
			$pilihanGanda = [];
			for($x = 0;$x < $y;$x++){
				$pilihanGanda[$x]['id_soal'] = $id;
				$pilihanGanda[$x]['isi_pilihan'] = $req->pilihanGanda[$x]['isi_pilihan'];
				$pilihanGanda[$x]['huruf'] = $req->pilihanGanda[$x]['huruf'];
			};
			DB::table('tbpilihan_ganda')->insert($pilihanGanda);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
