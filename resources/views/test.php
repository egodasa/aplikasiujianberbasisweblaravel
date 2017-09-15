<html>
	<head>
		<title>Halaman Pertama On Laravel</title>
	</head>
	<body>
		<table>
			<tr>
				<th>No</th>
				<th>Nama</th>
			</tr>
			<?php
			echo json_encode($daftar);
				foreach($daftar as $daftars){
					echo "<tr><td>",$daftars->id_peserta,"</td>";
					echo "<td>",$daftars->nm_peserta,"</td></tr>";
					}
			?>
		</table>
	</body>
</html>
