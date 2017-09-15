@extends('template.dasar')
@section('judul','test judul')
@section('konten')
<h2>Daftar Ujian</h2>
<table class="w3-table-all">
	<tr class="w3-teal">
		<th>No</th>
		<th>Nama Ujian</th>
		<th>Aksi</th>
	</tr>
	@foreach($daftarUjian as $list)
		<tr>
			<td>{{$loop->index+1}}</td>
			<td>{{$list->nm_ujian}}</td>
			<td>
				<button class="w3-button w3-small w3-red">Hapus</button>
				<button class="w3-button w3-small w3-green">Edit</button>
			</td>
		</tr>
	@endforeach
</table>
@endsection
