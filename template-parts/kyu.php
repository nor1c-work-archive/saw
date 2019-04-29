<li style="float:right">
    <select name="kyu_select" id="kyu_select" class="form-control">
        <?php
			$query = $pdo->prepare('SELECT * FROM kyu ORDER BY kyu_title DESC');			
			$query->execute();
			$query->setFetchMode(PDO::FETCH_ASSOC);	
            echo "<option value='' selected>Show All</option>";
			while ($hasil = $query->fetch()) {
				echo "<option value='".$hasil['id_kyu']."'>".$hasil['kyu_title'] . ' | ' . $hasil['kyu_description'] ."</option>";
			}
		?>
    </select>
</li>