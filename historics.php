<?php

include_once("db.php");
    
$infos = $db->getInformationHis();

if (empty($infos)): ?>
     List is empty
<?php endif; ?>
         
<table id="infoTable">
  <thead>
    <tr>
      <?php if (!empty($infos)): ?>
        <?php foreach (array_keys($infos[0]) as $col): ?>
          <th><?= htmlspecialchars($col) ?></th>
        <?php endforeach; ?>
      <?php endif; ?>
    </tr>
    <tr>
      <?php if (!empty($infos)): ?>
        <?php foreach (array_keys($infos[0]) as $col): ?>
          <th><input class="filter" type="text" data-col="<?= $col ?>" placeholder="Filtrer <?= htmlspecialchars($col) ?>"></th>
        <?php endforeach; ?>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($infos as $info): ?>
      <tr>
        <?php foreach ($info as $val): ?>
          <td><?= htmlspecialchars($val) ?></td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const inputs = document.querySelectorAll("input.filter");
  const table = document.getElementById("infoTable");
  const rows = Array.from(table.tBodies[0].rows);

  inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
      const filters = Array.from(inputs).map(input => input.value.toLowerCase());

      rows.forEach(row => {
        const cells = Array.from(row.cells);
        const visible = filters.every((filter, i) =>
          !filter || cells[i].textContent.toLowerCase().includes(filter)
        );
        row.style.display = visible ? "" : "none";
      });
    });
  });
});
</script>
