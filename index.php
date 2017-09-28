<?php

$pdo = new PDO("mysql:host=localhost;dbname=krivoshein;charset=utf8", "krivoshein", "neto1229", [
	  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
if (!empty($_POST['create_table']) && !empty($_POST['table_name'])) {
		$sqlCreateTable = "CREATE TABLE {$_POST['table_name']} (id int(11) NOT NULL AUTO_INCREMENT, name VARCHAR(50) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$stmtCreateTable = $pdo->prepare($sqlCreateTable);
		$stmtCreateTable->execute();
}
if (!empty($_GET['table']) && !empty($_POST['add']) && empty($_POST['name_field']) && !empty($_POST['name_field_new']) && !empty($_POST['typeofdata'])) {
		$sqlAddField = "ALTER TABLE {$_GET['table']} ADD {$_POST['name_field_new']} {$_POST['typeofdata']}";
		$stmtAddField = $pdo->prepare($sqlAddField);
		$stmtAddField->execute();
}
if (!empty($_GET['table']) && !empty($_POST['change']) && !empty($_POST['name_field']) && !empty($_POST['typeofdata'])) {
		if (!empty($_POST['name_field_new'])) {
				$sqlChange = "ALTER TABLE {$_GET['table']} CHANGE {$_POST['name_field']} {$_POST['name_field_new']} {$_POST['typeofdata']}";
		} else {
				$sqlChange = "ALTER TABLE {$_GET['table']} MODIFY {$_POST['name_field']} {$_POST['typeofdata']}";
		}
				$stmtChange = $pdo->prepare($sqlChange);
				$stmtChange->execute();
}
if (!empty($_GET['table']) && !empty($_POST['delete']) && !empty($_POST['name_field']) && empty($_POST['name_field_new']) && empty($_POST['typeofdata'])) {
		$sqlDelete = "ALTER TABLE {$_GET['table']} DROP COLUMN {$_POST['name_field']}";
		$stmtDelete = $pdo->prepare($sqlDelete);
		$stmtDelete->execute();
}
$sqlTables = "SHOW TABLES";
$stmtTables = $pdo->query($sqlTables);
if (!empty($_GET['table'])) {
		$sqlTable = "DESCRIBE {$_GET['table']}";
		$stmtTable = $pdo->prepare($sqlTable);
		$stmtTable->execute();
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
	  <meta charset="utf-8">
    <title>Homework4.4</title>
	</head>
	<body>
	  <header>
	    <h1>PHPadmin</h1>
	  </header>

    <section>
			<div>
			  <h4>Доступные таблицы</h4>
				<ul>
					<?php foreach ($stmtTables as $rowTables) { ?>
						<li>
							<a class="table-name" href="?table=<?php echo htmlspecialchars($rowTables[0]); ?>">
								<?php echo htmlspecialchars($rowTables[0]); ?>
							</a>
						</li>
					<?php } ?>
				</ul>

				<form class="create-table" method="POST">
					<h4>Новая таблица</h4>
					<input class="table-name" type="text" name="table_name" placeholder="Имя таблицы">
					<input type="submit" name="create_table" value="Создать таблицу">
				</form>
			</div>

      <?php if (!empty($_GET['table'])) { ?>
			<form class="field-operation" method="POST">
				<table class="table">
					<tr>
						<th>Field</th>
						<th>Type</th>
						<th>Null</th>
						<th>Key</th>
						<th>Default</th>
						<th>Extra</th>
					</tr>

					<?php while ($rowTable = $stmtTable->fetch(PDO::FETCH_ASSOC)) { ?>
					<tr>
						<td>
							<input type="radio" name="name_field" value="<?php echo htmlspecialchars($rowTable['Field']); ?>" value="">
						</td>	
						<td><?php echo htmlspecialchars($rowTable['Field']); ?></td>
						<td><?php echo htmlspecialchars($rowTable['Type']); ?></td>
						<td><?php echo htmlspecialchars($rowTable['Null']); ?></td>
						<td><?php echo htmlspecialchars($rowTable['Key']); ?></td>
						<td><?php echo htmlspecialchars($rowTable['Default']); ?></td>
						<td><?php echo htmlspecialchars($rowTable['Extra']); ?></td>				
					</tr>
					<?php } ?>
				</table>
				<div class="field-operation-bar">
					<input class="name-field-new" type="text" name="name_field_new" placeholder="Имя поля">
					<input class="typeofdata" type="text" name="typeofdata" placeholder="Тип данных">
					<input class="add" type="submit" name="add" value="Добавить">
					<input class="change" type="submit" name="change" value="Изменить">
					<input class="delete" type="submit" name="delete" value="Удалить">				
				</div>
				<input class="reset" type="reset">
			</form>
			<?php } ?>
		</section>
	</body>
</html>
