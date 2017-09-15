<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Soal;
class soalUjian extends Controller
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
    public function getSoalUjian(Request $req,$id){
		$data = new \stdClass;
		$data->status = true;
		$limit = $req->query('limit');
		$offset = $req->query('offset');
		$db = DB::table('tbsoal_ujian')->where('id_ujian',$id)->limit($limit)->offset($offset);
		if($req->query('belumDitambahkan') == null){
			$data->data = $db->select('tbsoal_ujian.id','tbsoal_ujian.id_soal','tbsoal.isi_soal','tbsoal.jawaban')->leftJoin('tbsoal','tbsoal_ujian.id_soal','=','tbsoal.id_soal')->get();
		}else{
			$notIn = DB::table('tbsoal_ujian')->where('id_ujian',$id)->pluck('id_soal');
			$data->data = DB::table('tbsoal')->select('id_soal','isi_soal')->whereNotIn('id_soal',$notIn)->get();
		}
		$data->row = DB::table('tbsoal_ujian')->where('id_ujian',$id)->count();
		$data->current_row = count($data->data);
		return response()->json($data);
	}
	public function deleteSoalUjian($id){
		$data = new \stdClass;
		$data->status = true;
		DB::table('tbsoal_ujian')->where('id',$id)->delete();
		return response()->json($data);
	}
	public function addSoalUjian(Request $req,$id){
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
			DB::table('tbsoal_ujian')->insert([
				'id_ujian'=>$id,
				'id_soal'=>$idSoal
			]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
