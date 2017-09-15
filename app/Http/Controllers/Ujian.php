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
		$data->data = DB::table('tbhasil_ujian')->select('tbpeserta.nm_peserta','tbhasil_ujian.benar','tbhasil_ujian.salah','tbhasil_ujian.nilai')->where('id_ujian',$id)->leftJoin('tbpeserta','tbhasil_ujian.id_peserta','=','tbpeserta.id_peserta')->get();
		$data->row = count($data->data);
		return response()->json($data);
	}
	public function addHasilUjian(Request $req,$id){
		$data = new \stdClass;
		$data->status = true;
		$nilai = $req->benar * (100/($req->benar+$req->salah));
		$nilaiUjian = [
			'id_ujian'=>$id,
			'id_peserta'=>$req->id_peserta,
			'benar'=>$req->benar,
			'salah'=>$req->salah,
			'nilai'=>$nilai
		];
		DB::table('tbhasil_ujian')->insert($nilaiUjian);
		return response()->json($nilaiUjian);
	}
    public function getUjian(Request $req,$id = null){
		$data = new \stdClass;
		$limit = $req->query('limit');
		$offset = $req->query('offset');
		$db = DB::table('tbujian')->select('id_ujian','nm_ujian',DB::raw('miliToJam(durasi_ujian) as jam'),DB::raw('miliToMenit(durasi_ujian) as menit'),DB::raw('(select count(tbsoal_ujian.id_ujian) from tbsoal_ujian where tbsoal_ujian.id_ujian = tbujian.id_ujian) as banyak_soal'),DB::raw('(select count(tbpeserta_ujian.id_ujian) from tbpeserta_ujian where tbpeserta_ujian.id_ujian = tbujian.id_ujian) as banyak_peserta'))->limit($limit)->offset($offset);
		if($id == null) $data->data = $db->get();
		else $data->data = $db->where('id_ujian',$id)->get();
		$data->row = DB::table('tbujian')->count();;
		$data->current_row = count($data->data);
		return response()->json($data);
	}
	public function addUjian(Request $req){
		$data = new \stdClass;
		$db = DB::table('tbujian');
		$validator = \Validator::make($req->all(), $this->cekUjian, $this->errUjian);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$db->insert(['nm_ujian'=>$req->nm_ujian,'durasi_ujian'=>DB::raw('stringToTime('.$req->jam.','.$req->menit.')')]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
	public function deleteUjian($id){
		$db = DB::table('tbujian');
		$db->where('id_ujian',$id)->delete();
		return response()->json(['status'=>true]);
	}
	public function updateUjian(Request $req,$id){
		$data = new \stdClass;
		$db = DB::table('tbujian');
		$validator = \Validator::make($req->all(), $this->cekUjian, $this->errUjian);
		if($validator->fails()) {
			$data->status = false;
			$data->error = $validator->errors();
		}else{
			$db->where('id_ujian',$id)->update(['nm_ujian'=>$req->nm_ujian,'durasi_ujian'=>DB::raw('stringToTime('.$req->jam.','.$req->menit.')')]);
			$data->status = true;
			$data->error = null;
		}
		return response()->json($data);
	}
}
