<?php
include "$root/inc/head.php";
?>

<div class="w3-content margin w3-border w3-padding" style="background: white;">
  <div class="dynamic-grid enseignants" id="gridEns" data-items='<?php getEnseignantsArrayData($db) ?>'></div>
</div>

<script>
function createGrid(container) {
  const screenWidth = window.innerWidth;
  const containerWidth = container.clientWidth;
  const listData = JSON.parse(container.getAttribute('data-items') || '[]');
  let columns;

  if (screenWidth < 600) {
    columns = 1;
  } else if (screenWidth < 900) {
    columns = 2;
  } else if (screenWidth < 1200) {
    columns = 3;
  } else {
    columns = 4;
  }

  if (listData.length < columns) {
    columns = listData.length;
  }

  container.innerHTML = '';
  container.style.display = 'grid';
  container.style.gridTemplateColumns = `repeat(${columns}, minmax(100px, auto))`;

  listData.forEach(itemData => {
    
    if(itemData[0] != null) {
      const item = document.createElement('div');
      item.style.border = 'black solid 1px';
      item.style.minHeight = '40px';
      item.style.Position = 'relative';
      item.style.display = 'flex';
      item.style.justifyContent = 'center';
      item.style.top = '50%';
      item.style.left = '50%';
      item.style.transform = 'translate("-50%", "-50%")';
      item.innerHTML = `
        <button class="w3-text-blue" onclick="displayCard(event, '${itemData[0]}')" 
          style="cursor: pointer; border: none; background-color: white; font-size: 16px;">
          <b>${itemData[1]}</b>
        </button>
        <div id="${itemData[0]}" class="w3-dropdown-content w3-card-2 w3-bar-block w3-padding" style="min-width: 30vw;">
          ${itemData[1] != null ? "<h4>" + itemData[1] + "</h4>" : ''}
          ${itemData[1] != null ? "<h5>Position : " + itemData[6] + "</h5>" : ''}
          ${itemData[2] != null ? "<h5>Tel : " + itemData[2] + "</h5>" : ''}
          ${itemData[3] != null ? "<h5>mail : " + itemData[3] + "</h5>" : ''}
          ${itemData[4] != null ? "<h5>ville : " + itemData[4] + "</h5>": ''}
          ${itemData[5] != null ? "<mark style='background-color:cyan;'>" + itemData[5] + "</mark>" : ''}
        </div>`;
      container.appendChild(item);
    }
  });
}

function updateGrids() {
  document.querySelectorAll('.dynamic-grid').forEach(container => {
    createGrid(container);
  });
}

window.addEventListener('load', updateGrids);
window.addEventListener('resize', updateGrids);

function displayCard(event, target) {
  event.stopPropagation();
  var popup = document.getElementById(target);
  
  if (popup.className.indexOf("w3-show") == -1) {
    popup.style.display = 'block';
    popup.focus();

    function handleClickOutside(event) {
      if (!popup.contains(event.target)) {
        popup.style.display = 'none';
        document.removeEventListener('click', handleClickOutside);
      }
    }

    document.addEventListener('click', handleClickOutside);
  } else { 
    popup.style.display = 'none';
  }
}
</script>

<?php
include "$root/inc/footer.php";
?>

